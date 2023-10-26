<?php

namespace frontend\controllers\payment;

use common\models\ApplicantFee;
use Yii;
use components\exceptions\AppException;
use yii\helpers\ArrayHelper;
use common\models\Transaction;

/**
 * Description of RazorPayController
 *
 * @author Azam
 */
class RazorPayController extends BasePaymentController
{
    protected $integration = BasePaymentController::GATEWAY_RAZORPAY;
    
    public function beforeAction($action)
    {
        $this->paymentMethod = Yii::$app->request->post('paymentMethod');
        $this->appModule = Yii::$app->request->post('appModule');
        $this->paymentMode = Yii::$app->request->post('paymentMode');
        $this->postParams = Yii::$app->request->post();
        
        if(empty($this->paymentMethod)){
            $this->paymentMethod = Transaction::TYPE_RAZORPAY;
        }
        if(empty($this->appModule)){
            $this->appModule = ApplicantFee::MODULE_APPLICATION;
        }        
        
        if (empty($this->paymentMethod) && ArrayHelper::isIn($action->id, ['application'])) {
            throw new AppException('Please select a payment gateway to pay online.');
        }
        return parent::beforeAction($action);
    }

    public function actionApplication()
    {
        return parent::actionRequest();
    }

    public function actionSuccess($postParams = null)
    {
        if (!Yii::$app->request->isPost) {
            throw new AppException("Invalid Request.");
        }

        $postParams = Yii::$app->request->post();
        return parent::actionSuccess($postParams);
    }

    public function actionFailed($txnid = null)
    {
        if (!$txnid) {
            throw new AppException("Invalid Request.");
        }
        $postParams['txnid'] = $txnid;
        return parent::actionFailed($postParams);
    }
}
