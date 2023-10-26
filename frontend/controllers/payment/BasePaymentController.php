<?php

namespace frontend\controllers\payment;

use Yii;
use common\models\Transaction;
use common\models\ApplicantFee;
use common\models\Applicant;
use components\exceptions\AppException;
use common\models\caching\ModelCache;
use components\Helper;
use frontend\models\ReviewForm;
use common\components\Razorpay;
use components\integration\payment\ccavenue\CcAvenue;
use Razorpay\Api\Errors\SignatureVerificationError;

/**
 * Description of BasePaymentController
 *
 * @author Amit Handa
 */
class BasePaymentController extends \yii\web\Controller
{

    protected $applicantId = null;
    protected $_applicantModel = null;
    protected $appModule = null;
    protected $integration = null; //payu,csc,icici
    protected $paymentMethod = null;
    protected $paymentMode = null;
    protected $postParams = null;
    protected $currencyRate = 1;
    protected $obj;
    private $sessionKey = '_payment';
    private $referrer;
    protected $ajaxRequest = false;

    const GATEWAY_CCAVENUE = 'ccavenue';
    const GATEWAY_CSC = 'csc';
    const GATEWAY_RAZORPAY = 'razorpay';
    const PAYMENT_SUCCESS = 'success';
    const ICICI_SUCCESS_CODE = 'E000';

    const BANK_OF_BRODA_SUCCESS = 'CAPTURED';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = FALSE;
        $this->ajaxRequest = Yii::$app->request->isAjax;
        
        $parentAction = parent::beforeAction($action);
        if (!Yii::$app->applicant->isGuest) {
            $this->applicantId = Yii::$app->applicant->identity->id;
            $this->_applicantModel = Yii::$app->applicant->identity;
        }
        elseif (!empty($this->postParams) && !empty($this->postParams['guid'])) {
            $applicantModel = Applicant::findByGuid($this->postParams['guid'], [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);
            $this->applicantId = $applicantModel->id;
            $this->_applicantModel = $applicantModel;
        }
        return $parentAction;
    }

    public function actionRequest($csc = NULL)
    {
        if (\yii\helpers\ArrayHelper::isIn($this->appModule, [ApplicantFee::MODULE_APPLICATION, ApplicantFee::MODULE_ESERVICE])) {
           
            if($this->_checkExistingPayment()){
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            $data = $this->getApplicantFeeIdAndAmount($this->appModule);
            $totalFee = $data['fee_amount'];
     
            $this->_validateApplication($this->applicantId, [
                'applicantFeeId' => $data['id']
            ]);
            
            return $this->__initTransaction($data['id'], $totalFee, $this->appModule, ['csc' => $csc]);
        }
    }

    public function actionSuccess($postParams = null)
    {
        $responseTransactionId = null;
        $sessionTransaction = $this->getTransaction();
        try {
            if ($this->integration === self::GATEWAY_CCAVENUE) {
                if (empty($postParams["orderNo"])) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway. empty order id");
                }
                
                if (empty($postParams['encResp'])) {
                    throw new AppException("Oops! We are unable to process this payment due to empty response from payment gateway");
                }
                
                $transactionModel = Transaction::findByTransactionId($postParams["orderNo"]);
                
                if (empty($transactionModel)) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway. invalid order id");
                }
                
                $this->__initPaymentClient($transactionModel['type']);
                $response = $this->obj->formatResponse($postParams['encResp']);
                
                $this->__responseTransaction($postParams["orderNo"], strtolower($response['order_status']), $response);
            }
            elseif ($this->integration === self::GATEWAY_CSC) {
                $responseTransactionId = isset($postParams["merchant_txn"]) ? $postParams["merchant_txn"] : null;
                if (!empty($sessionTransaction) && $responseTransactionId !== $sessionTransaction['transactionId']) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }
                $this->__responseTransaction($responseTransactionId, strtolower($postParams['txn_status_message']), $postParams);
            }
            elseif ($this->integration === self::GATEWAY_RAZORPAY) {

                $this->__initPaymentClient();
        
                $responseTransactionId = isset($postParams["razorpay_order_id"]) ? $postParams["razorpay_order_id"] : null;

                if (!empty($sessionTransaction) && $responseTransactionId != $sessionTransaction['transactionId']) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }

                if (empty($postParams['razorpay_payment_id']) || empty($postParams['razorpay_signature'])) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }

              
                $transactionDetails = Transaction::findByTransactionId($responseTransactionId, [
                    'selectCols' => ['transaction.*']
                ]);

                if (empty($transactionDetails)) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }
             
             
                try {
                    $attributes = [
                        'razorpay_order_id' => $transactionDetails['transaction_id'],
                        'razorpay_payment_id' => $postParams['razorpay_payment_id'],
                        'razorpay_signature' => $postParams['razorpay_signature']
                    ];

                    $this->obj->verifySignature($attributes);
                } catch (SignatureVerificationError $e) {
                    throw new AppException("Invalid Payment signature :" . $e->getMessage());
                }

            
                $paymentDetails = $this->obj->capturePayment($postParams['razorpay_payment_id']);

                if ($paymentDetails->order_id != $transactionDetails['transaction_id']) {
                    throw new AppException(" Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }

                if ($transactionDetails['id'] != $paymentDetails->notes->transactionId) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }

                $postParams['gatewayId'] = $paymentDetails->id;
                $postParams['amount'] = $paymentDetails->amount / 100;
                $postParams['paymentStatus'] = $paymentDetails->status;
                $postParams['error'] = $paymentDetails->status;
                $postParams['status'] = Transaction::PAYMENT_FAILED;
                $postParams['response'] = \yii\helpers\Json::encode($paymentDetails);
                if ($postParams['paymentStatus'] == Transaction::PAYMENT_CAPTURED) {
                    $postParams['status'] = Transaction::PAYMENT_SUCCESS;
                }

                $this->__responseTransaction($responseTransactionId, $postParams['status'], $postParams);
            }
        }
        catch (\Exception $ex) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
        }
        
        return $this->redirect($this->referrer);
    }

    public function actionFailed($postParams = null)
    {
        $responseTransactionId = null;
        $sessionTransaction = $this->getTransaction();
        try {
            if ($this->integration === self::GATEWAY_CCAVENUE) {
                
                if (empty($postParams["orderNo"])) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway. empty order id");
                }
                
                if (empty($postParams['encResp'])) {
                    throw new AppException("Oops! We are unable to process this payment due to empty response from payment gateway");
                }
                
                $transactionModel = Transaction::findByTransactionId($postParams["orderNo"]);
                
                if (empty($transactionModel)) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway. invalid order id");
                }
                
                $this->__initPaymentClient($transactionModel['type']);
                $response = $this->obj->formatResponse($postParams['encResp']);
                
                $this->__responseTransaction($postParams["orderNo"], $response['order_status'], $response);
            }
            elseif ($this->integration === self::GATEWAY_CSC) {
                $responseTransactionId = isset($postParams["merchant_txn"]) ? $postParams["merchant_txn"] : null;
                if (!empty($sessionTransaction) && $responseTransactionId !== $sessionTransaction['transactionId']) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }
                $this->__responseTransaction($responseTransactionId, 'failure', $postParams);
            }
            elseif ($this->integration === self::GATEWAY_RAZORPAY) {
                $responseTransactionId = isset($postParams["txnid"]) ? $postParams["txnid"] : null;
                if (!empty($sessionTransaction) && $responseTransactionId != $sessionTransaction['txnid']) {
                    throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
                }
                $this->__responseTransaction($sessionTransaction['transactionId'], 'failure', $postParams);
            }
            
        }
        catch (\Exception $ex) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
        }
        
        Yii::$app->session->setFlash('error', 'Oops! We are unable to process this payment');
        
        return $this->redirect($this->referrer);
    }
    
    protected function __initTransaction($applicantFeeId, $amount, $module, $params = [])
    {
        if ($amount == 0 || empty($amount)) {
            throw new AppException("Sorry, you can not initiate 0 Rs transaction.");
        }

        $this->__initPaymentClient($this->paymentMethod, [
            'appModule' => $module
        ]);
        $transactionModel = new Transaction;
        $orderId = $transactionModel->createTransactionId();
        
        // Create Order In Table while generate transaction
        $transactionData = [
            'transaction_id' => $orderId,
            'type' => $this->paymentMethod,
            'applicant_fee_id' => $applicantFeeId,
            'applicant_id' => $this->_applicantModel->id,
            'status' => Transaction::TYPE_STATUS_FOR_CREATED,
            'amount' => $amount,
        ];
        
        if($this->integration === self::GATEWAY_RAZORPAY){

            $requestedParams = [
                'receipt' => $orderId,
                'amount' => ($amount * 100), //// 2000 rupees in paise
                'currency' => 'INR',
                'payment_capture' => 1
            ];

            $razorPayOrder = $this->obj->createOrder($requestedParams);
            $requestedParams['order_id'] = $razorPayOrder['id'];
            $transactionData['transaction_id'] = $requestedParams['order_id'];
        }
        if($this->integration === self::GATEWAY_CCAVENUE){
            $requestedParams = [
                'merchant_id' => $this->obj->merchantId,
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => 'INR',
                'redirect_url' => Yii::$app->params['rootHttpPath'] . '/payment/cc-avenue/success',
                'cancel_url' => Yii::$app->params['rootHttpPath'] . '/payment/cc-avenue/failed',
                'language' => 'EN',
                'merchant_param1' => $this->appModule,
                'merchant_param2' => $this->_applicantModel->name,
                'merchant_param3' => $this->_applicantModel->mobile,
                'merchant_param4' => ApplicantFee::getClassifiedName($applicantFeeId),
                'merchant_param5' => $this->_applicantModel->email
            ];
        }
        $requestedParams['referrer'] = Yii::$app->request->referrer;
        
        if ($this->integration === self::GATEWAY_CSC) {
            if (empty($params['csc'])) {
                throw new AppException("Oops CSC Id can not be blank.");
            }
            $requestedParams = [
                'csc_id' => $params['csc'],
                'merchant_receipt_no' => $orderId,
                'txn_amount' => ($params['csc'] == '500100100013') ? 35.40 : $amount,
                'return_url' => $this->obj->surlFee,
                'cancel_url' => $this->obj->furlFee,
                'product_id' => $this->obj->productId,
                'merchant_txn' => $orderId,
                'productinfo' => $this->appModule,
                'pay_to_email' => $this->_applicantModel->email,
                'param_1' => $this->_applicantModel->name,
                'param_2' => $this->_applicantModel->mobile,
                'param_3' => ApplicantFee::getClassifiedName($applicantFeeId),
                'param_4' => ''
            ];

            $requestedParams['referrer'] = Yii::$app->request->referrer;
        }
        $transactionData['requested_data'] = \yii\helpers\Json::encode($requestedParams);
        $transactionId = $transactionModel->createTransaction($transactionData);

        if (!$transactionId) {
            throw new AppException("Sorry, While processing payment has been raise error. Please try again.");
        }

        if ($this->integration === self::GATEWAY_CCAVENUE) {
            
            $merchantData = '';
            foreach ($requestedParams as $key => $value){
                $merchantData.=$key.'='.urlencode($value).'&';
            }
            
            $encryptedData= $this->obj->encrypt($merchantData, $this->obj->workingKey);

            return $this->renderPartial('ccavenue-redirect', [
                        'action' => $this->obj->paymentUrl,
                        'encryptedData' => $encryptedData,
                        'accessCode' => $this->obj->accessCode
            ]);
        }
        
        if($this->integration === self::GATEWAY_CSC){
            $connectResponseData = Yii::$app->session->get($this->applicantId.'_connectResponseData');
            if(!empty($connectResponseData)){
                $transactionData['connectResponse'] = $connectResponseData;
                Yii::$app->session->remove($this->applicantId.'_connectResponseData');
            }
        }
        
        // clear session information
        $this->clearTransaction();
        $this->setTransaction([
            'transactionId' => $orderId,
            'referrer' => Yii::$app->request->referrer
        ]);
        
        if ($this->integration === self::GATEWAY_CSC) {
            $this->obj->set_params($requestedParams);
            $encText = $this->obj->get_parameter_string();
            $frac = $this->obj->get_fraction();
            return $this->renderPartial('csc-redirect', [
                        'encText' => $encText,
                        'frac' => $frac,
                        'url' => $this->obj->walletUrl
            ]);
        }
        
        if ($this->integration === self::GATEWAY_RAZORPAY) {

            // clear session information
            $this->clearTransaction();
            $this->setTransaction([
                'txnid' => $transactionId,
                'transactionId' => $transactionData['transaction_id'],
                'referrer' => Yii::$app->request->referrer
            ]);
            
            $requestedParams['key'] = $this->obj->apiKey;
            $requestedParams['name'] = "HPSLSA";
            //$requestedParams['payment_method'] = "cards";
            $requestedParams['description'] = ApplicantFee::getClassifiedName($applicantFeeId);
            $requestedParams['notes']['transactionId'] = $transactionId;
            $requestedParams['notes']['orderId'] = $transactionData['transaction_id'];
            /*$requestedParams['method']['netbanking'] = "0";
            $requestedParams['method']['card'] = "1";
            $requestedParams['method']['upi'] = "0";
            $requestedParams['method']['wallet'] = "0";*/
            $requestedParams['prefill']['name'] = $this->_applicantModel->name;
            $requestedParams['prefill']['email'] = $this->_applicantModel->email;
            $requestedParams['prefill']['contact'] = $this->_applicantModel->mobile;
            if (!empty($this->paymentMode)) {
                $requestedParams['prefill']['method'] = $this->paymentMode;
                $requestedParams['theme']['hide_topbar'] = "true";
            }
            if($this->ajaxRequest) {
                return Helper::outputJsonResponse(['success' => 1, 'paramsLists' => $requestedParams]);
            }
            
            return $this->renderPartial('_razor-pay', [
                        'paramList' => $requestedParams
            ]);
        }
    }

    protected function __responseTransaction($transactionId, $status, $responseParams = [])
    {
        $sessionTransaction = $this->getTransaction();
        $transactionModel = Transaction::findByTransactionId($transactionId, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if (empty($transactionModel)) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
        }
        
        if($transactionModel->status == Transaction::TYPE_STATUS_PAID) {
            throw new AppException("Oops! This transaction already paid.");
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $transStatus = Transaction::TYPE_STATUS_FAILED;
            if ($status == Transaction::PAYMENT_SUCCESS) {
                $transStatus = Transaction::TYPE_STATUS_PAID;
                Yii::$app->session->setFlash('success', 'Your payment has been successfully procesed');
            }else{
                Yii::$app->session->setFlash('error', 'Oops! We are unable to process this payment due to invalid response from payment gateway.');
            }

            if ($this->integration == self::GATEWAY_CCAVENUE) {
                $transactionModel->response_amount = !empty($responseParams['amount']) ? $responseParams['amount'] : NULL;
                $transactionModel->gateway_id = !empty($responseParams['tracking_id']) ? $responseParams['tracking_id'] : NULL;
                $transactionModel->failed_msg = !empty($responseParams['status_message']) ? $responseParams['status_message'] : NULL;
                
                if (\yii\helpers\ArrayHelper::isIn($status, Transaction::hdfcSuccessStatus())) {
                    $transStatus = Transaction::TYPE_STATUS_PAID;
                    $transactionModel->is_consumed = Transaction::checkIsConsumed($transactionModel['applicant_fee_id']);
                    Yii::$app->session->setFlash('success', 'Your payment has been successfully procesed');
                }

                if (\yii\helpers\ArrayHelper::isIn($status, Transaction::hdfcFailedStatus())) {
                    $transStatus = Transaction::TYPE_STATUS_FAILED;
                    Yii::$app->session->setFlash('success', 'Your payment has failed.');
                }

                if (\yii\helpers\ArrayHelper::isIn($status, Transaction::hdfcPendingStatus())) {
                    $transStatus = Transaction::TYPE_STATUS_PENDING;
                    Yii::$app->session->setFlash('error', 'Oops! Order status is still pending.');
                }

                $requestedData = \yii\helpers\Json::decode($transactionModel->requested_data);
                if ((isset($responseParams['amount']) && $transactionModel->amount != $responseParams['amount']) || 
                    (isset($responseParams['merchant_param1']) && $responseParams['merchant_param1'] != $requestedData['merchant_param1']) ||
                    (isset($responseParams['merchant_param2']) && isset($requestedData['merchant_param2']) && $requestedData['merchant_param2'] != $responseParams['merchant_param2']) ||
                    (isset($responseParams['merchant_param3']) && isset($requestedData['merchant_param3']) && $requestedData['merchant_param3'] != $responseParams['merchant_param3']) ||
                    (isset($responseParams['merchant_param4']) && isset($requestedData['merchant_param4']) && $requestedData['merchant_param4'] != $responseParams['merchant_param4']) ||
                    (isset($responseParams['merchant_param5']) && isset($requestedData['merchant_param5']) && $requestedData['merchant_param5'] != $responseParams['merchant_param5'])) {
                    $transStatus = Transaction::TYPE_STATUS_FAILED;
                    $transactionModel->failed_msg = 'Response parameters did not matched.';
                }
            }
            elseif ($this->integration == self::GATEWAY_CSC) {
                $transactionModel->response_amount = !empty($responseParams['txn_amount']) ? $responseParams['txn_amount'] : NULL;
                $transactionModel->gateway_id = !empty($responseParams['csc_txn']) ? $responseParams['csc_txn'] : NULL;
                $transactionModel->failed_msg = !empty($responseParams['status_message']) ? $responseParams['status_message'] : NULL;
            }
            elseif ($this->integration == self::GATEWAY_RAZORPAY) {
                $transactionModel->response_amount = !empty($responseParams['amount']) ? $responseParams['amount'] : NULL;
                $transactionModel->gateway_id = !empty($responseParams['gatewayId']) ? $responseParams['gatewayId'] : NULL;
                $transactionModel->failed_msg = !empty($responseParams['error']) ? $responseParams['error'] : NULL;
                $transactionModel->response = (isset($responseParams['response']) && !empty($responseParams['response'])) ?  $responseParams['response'] : \yii\helpers\Json::encode($responseParams);;
            }

            if(!empty($transactionModel->gateway_id)) {
                $gatewayIdExist = Transaction::findByGatewayId($transactionModel->gateway_id, ['selectCols' => ['id'], 'countOnly' => true]);
                if ($gatewayIdExist > 0) {
                    $transStatus = Transaction::TYPE_STATUS_FAILED;
                    $transactionModel->failed_msg = 'Duplicate tracking_id in response.';
                    $transactionModel->gateway_id = NULL;
                }
            }
            
            $transactionModel->status = $transStatus;
            if($transStatus == Transaction::TYPE_STATUS_PAID) {
                $transactionModel->is_consumed = Transaction::checkIsConsumed($transactionModel->applicant_fee_id);
                $transactionModel->is_processed = Transaction::IS_PROCESSED;
            }
            $transactionModel->response = \yii\helpers\Json::encode($responseParams);
            if ($transactionModel->save()) {
                
                $transaction->commit();
                if ($transactionModel->status == Transaction::TYPE_STATUS_PAID) {
                    //After Success Payment Perform some action
                    Transaction::processAfterTransaction($transactionModel->applicant_id, $transactionModel->id);
                }
            }
            
            if(Yii::$app->applicant->isGuest){
                $applicantModel = Applicant::findById($transactionModel->applicant_id, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                ]);
                Yii::$app->applicant->login($applicantModel, 3600 * 24 * 30);
            }
            
            $transactionRequest = \yii\helpers\Json::decode($transactionModel->requested_data);
            if ($status == Transaction::PAYMENT_SUCCESS) {
                $this->referrer = \yii\helpers\Url::toRoute(['/payment/base-payment/thank-you', 'guid' => $transactionModel->applicantFee->applicantPost->guid]);
            }
            else {
                $this->referrer = $transactionRequest['referrer'];
            }
            
            if (empty($this->referrer) && isset($sessionTransaction['referrer']) && !empty($sessionTransaction['referrer'])) {
                $this->referrer = $sessionTransaction['referrer'];
            }

            $this->clearTransaction();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            $this->clearTransaction();
            throw new AppException("Oops! During payment gateway raise error. Please find below error :<br/><br/>" . $ex->getMessage());
        }
    }

    protected function _validateApplication($applicantId, $params = [])
    {
        $model = new ReviewForm;
        $model->load($this->postParams);
        $model->date = date('Y-m-d');
        
        if (false && isset($this->postParams['classifed_id']) && \yii\helpers\ArrayHelper::isIn($this->postParams['classifed_id'], [\frontend\models\RegistrationForm::SCENARIO_4])) {
            if ($model->preference1 == $model->preference2 || $model->preference1 == $model->preference3 || $model->preference2 == $model->preference3) {
                throw new AppException('Oops! exam centre preference 1 and preference 2 and preference 3 can not be the same');
            }
        }

        $applicantFeeModel = ApplicantFee::findById($params['applicantFeeId']);
        if(empty($applicantFeeModel)){
            throw new AppException('Oops! applicant fee model empty while saving review form');
        }
        
        if(!$model->saveRecord($applicantId, [
            'applicantPostId' => $applicantFeeModel['applicant_post_id']
        ])){
            throw new AppException('Oops! Something went worong while saving review form');
        }
    }
    
    protected function _checkExistingPayment()
    {
        $params = [
            'selectCols' => ['transaction.id', 'transaction.type'],
            'isProcessed' => Transaction::IS_NOT_PROCESS,
            'inPayStatus' => [
                Transaction::TYPE_STATUS_FOR_CREATED,
                Transaction::TYPE_STATUS_PENDING,
            ],
            'inType' => [
                Transaction::TYPE_RAZORPAY,
                Transaction::TYPE_CSC
            ],
            'orderBy' => [
                'id' => SORT_DESC
            ],
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
        ];
        
        $transactionModel = Transaction::findByApplicantId($this->applicantId, $params);
        
        if(!empty($transactionModel)){
            foreach ($transactionModel as $transaction){
                $transactionObj = new Transaction;
                $transactionObj->processScheduler($transaction['id'], $transaction['type']);
            }
            Yii::$app->session->setFlash('warning', 'We are currently updating your previous payments. if your payments do not processed in 30 minutes then try to pay again.');
            
            return true;
        }
        
        false;
    }

    protected function __initPaymentClient($paymentMethod = NULL, $params = [])
    {
        if ($this->integration === self::GATEWAY_CCAVENUE) {
            $this->_setCcavenueConfig($paymentMethod);
        }
        else if ($this->integration === self::GATEWAY_CSC) {
            $this->_setCscConfig($paymentMethod, $params);
        }
        else if ($this->integration === self::GATEWAY_RAZORPAY) {
            $this->_setRazorPayConfig();
        }
    }
    
    /**
     * Set payu configuration
     */
    private function _setAxisConfig()
    {
        $this->obj = new \components\axis\Axis();
    }
    
    /**
     * Set payu configuration
     */
    private function _setCcavenueConfig($paymentMethod)
    {
        $this->obj = new CcAvenue(['paymentMethod' => $paymentMethod]);
    }
    
    /**
     * Set Razorpay configuration
     */
    private function _setRazorPayConfig()
    {
        $this->obj = new Razorpay();
    }

    /**
     * Set csc-wallet configuration
     */
    private function _setCscConfig($paymentMethod, $params = [])
    {
        $dataParams = ['paymentMethod' => $paymentMethod];
        if(!empty($params) && !empty($params['appModule'])){
            $dataParams['appModule'] = $params['appModule'];
        }
        $this->obj = new \components\cscwallet\CscWallet($dataParams);
    }
    
    protected function findApplicantModel($guid)
    {
        $applicantModel = Applicant::findByGuid($guid, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);

        if (empty($applicantModel)) {
            throw new \components\exceptions\AppException("Sorry, you are trying to access this applicant doesn't exist.");
        }

        return $applicantModel;
    }

    private function clearTransaction()
    {
        if (\Yii::$app->session->has($this->sessionKey)) {
            \Yii::$app->session->remove($this->sessionKey);
        }
        return true;
    }

    public function setTransaction($data)
    {
        if (\Yii::$app->session->has($this->sessionKey)) {
            \Yii::$app->session->remove($this->sessionKey);
        }
        \Yii::$app->session->set($this->sessionKey, $data);
        return true;
    }

    public function getTransaction()
    {
        if (\Yii::$app->session->has($this->sessionKey)) {
            return \Yii::$app->session->get($this->sessionKey);
        }
        return [];
    }

    protected function getApplicantFeeIdAndAmount($appModule)
    {
        $feeId = Yii::$app->security->validateData($this->postParams['feeId'], Yii::$app->params['hashKey']);
        
        $model = ApplicantFee::findById($feeId, [
                    'applicantId' => $this->applicantId,
                    'module' => $appModule,
                    'payStatus' => ApplicantFee::STATUS_UNPAID
        ]);
        
        if (empty($model)) {
            throw new AppException("Sorry, Applicant's application fee details doesn't exist or already paid. Please contact with administrator.");
        }

        return $model;
    }
    
    /**
     * csc connect
     * @return type
     * @throws AppException
     */
    public function actionConnect()
    {
        $this->__initPaymentClient($this->postParams['paymentMethod']);
        
        $state = time();
        $authParameters = "response_type=code&client_id=".$this->obj->clientId."&redirect_uri=".$this->obj->cscConnectRedirectUrl."&state=".$state;
        $url = $this->obj->authorizationEndpointUrl . "?" . $authParameters;
        Yii::$app->session->set('csc-connect-state', $state);
        return $this->redirect($url);
    }
    
    /**
     * csc connect response
     * @param type $code
     * @param type $state
     * @return type
     * @throws AppException
     */
    public function actionConnectResponse($code, $state)
    {
        
        if (empty($code) || empty($state)) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from csc connect.");
        }
        $this->__initPaymentClient(Transaction::TYPE_CSC);
        if(!$state || $state != Yii::$app->session->get('csc-connect-state')){
            throw new AppException("Oops! STATE mismatch.");
        }
        $connectResponseData = [];
        $connectResponseData['code'] = $code;
        //fetch token
        $postData = [
            'code' => $code,
            'redirect_uri' => $this->obj->cscConnectRedirectUrl,
            'grant_type' => 'authorization_code',
            'client_id' => $this->obj->clientId,
            'client_secret' => $this->obj->connectEncrypt($this->obj->clientSecret)
        ];        
        
        $token_resp = $this->obj->fetch_data($this->obj->tokenEndpointUrl, $postData, false);
        $token_resp_data = (array) \yii\helpers\Json::decode($token_resp);
        
        $access_token = $token_resp_data && isset($token_resp_data['access_token']) ? $token_resp_data['access_token'] : false;

        if (!$access_token) {
            throw new AppException("Oops! No token");
        }
        
        $header_data = [
            'Authorization' => 'Bearer ' . $access_token
        ];

        $response = $this->obj->fetch_data($this->obj->resourceUrl . '?access_token=' . $access_token, false, $header_data);
        $response = \yii\helpers\Json::decode($response);
        if (!isset($response['User']['email']) || !isset($response['User']['username'])) {
            Yii::$app->session->setFlash('error', 'Invalid CSC Login!');
            return $this->redirect(['/auth/login']);
        }
        $response = $response['User'];
        $connectResponseData['token_resp_data'] = $token_resp_data;
        $connectResponseData['user_resp_data'] = $response;
        if(!empty($connectResponseData)){
            Yii::$app->session->set('_connectData', \yii\helpers\Json::encode($connectResponseData));
        }
        Yii::$app->session->setFlash('success', 'Congratulations, You have successfully logged in CSC connect.');
        $referrer = '/';
        if (\Yii::$app->session->has('_referrer')) {
            $referrer = \Yii::$app->session->get('_referrer');
        }
        return $this->redirect($referrer);
    }
    
    public function actionThankYou($guid) {
        
        $model = \common\models\ApplicantPost::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'paymentStatus' => \common\models\ApplicantPost::STATUS_PAID]);
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant post doesn't exists or deleted.");
        }
        return $this->render('thank-you', ['model' => $model]);
    }
    
}
