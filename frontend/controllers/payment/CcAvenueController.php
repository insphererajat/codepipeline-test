<?php

namespace frontend\controllers\payment;

use Yii;
use components\exceptions\AppException;
use yii\helpers\ArrayHelper;
use common\models\ApplicantFee;
use common\models\Transaction;

/**
 * Description of CcAvenueController
 *
 * @author Amit Handa
 */
class CcAvenueController extends BasePaymentController
{
    protected $integration = 'ccavenue';
    
    public function beforeAction($action)
    {
        $this->paymentMethod = Yii::$app->request->post('paymentMethod');
        $this->postParams = Yii::$app->request->post();
        
        if(empty($this->paymentMethod)){
            $this->paymentMethod = Transaction::TYPE_HDFC;
        }
        
        if (empty($this->paymentMethod) && ArrayHelper::isIn($action->id, ['application'])) {
            throw new AppException('Please select a payment gateway to pay online.');
        }
        return parent::beforeAction($action);
    }

    public function actionApplication()
    {
        $this->appModule = ApplicantFee::MODULE_APPLICATION;
        return parent::actionRequest();
    }
    
    public function actionEservice()
    {
        $this->appModule = ApplicantFee::MODULE_ESERVICE;
        return parent::actionRequest();
    }
    
    public function actionSuccess($postParams = null)
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams)) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
        }
        
        return parent::actionSuccess($postParams);
    }
    
    public function actionFailed($postParams = null)
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams)) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
        }
        
        return parent::actionFailed($postParams);
    }
    
    public function actionFreeEservice()
    {
        if (Yii::$app->request->isPost) {
            
            $post = Yii::$app->request->post();
            $feeId = Yii::$app->security->validateData($post['feeId'], Yii::$app->params['hashKey']);

            $applicantFee = ApplicantFee::findById($feeId, [
                        'applicantId' => Yii::$app->applicant->id,
                        'module' => $post['appModule'],
                        'payStatus' => ApplicantFee::STATUS_UNPAID
            ]);

            if (empty($applicantFee)) {
                throw new AppException("Sorry, Applicant's application fee details doesn't exist or already paid. Please contact with administrator.");
            }

            $model = new \frontend\models\ReviewForm();
            $model->load(Yii::$app->request->post());
            $model->date = date('Y-m-d');

            if ($model->preference1 == $model->preference2) {
                throw new AppException('Oops! exam centre preference 1 and preference 2 can not be the same');
            }

            if (!$model->saveRecord(Yii::$app->applicant->id, [
                        'applicantPostId' => $applicantFee['applicant_post_id']
                    ])) {
                throw new AppException('Oops! Something went worong while saving review form');
            }
            
            $transactionModel = new Transaction;
            $orderId = $transactionModel->createTransactionId();

            // Create Order In Table while generate transaction
            $transactionData = [
                'transaction_id' => $orderId,
                'type' => Transaction::TYPE_HDFC,
                'applicant_fee_id' => $applicantFee['id'],
                'applicant_id' => Yii::$app->applicant->id,
                'is_consumed' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
                'status' => Transaction::TYPE_STATUS_PAID,
                'amount' => $applicantFee['fee_amount'],
            ];

            $transactionId = $transactionModel->createTransaction($transactionData);
            Transaction::processAfterTransaction(Yii::$app->applicant->id, $transactionId);
            
            $transaction = Transaction::findById($transactionId, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
            return $this->redirect(['/payment/base-payment/thank-you', 'guid' => $transaction->applicantFee->applicantPost->guid]);
        }

    }
}