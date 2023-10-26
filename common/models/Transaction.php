<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use components\bob\iPay24Pipe;
use common\models\base\Transaction as BaseTransaction;

/**
 * Description of Transaction
 *
 * @author Amit Handa
 */
class Transaction extends BaseTransaction
{

    const TYPE_PAYU = 'PAYU';
    const TYPE_ICICI = 'ICICI';
    const TYPE_CSC = 'CSC';
    const TYPE_BOB = 'BOB';
    const TYPE_HDFC = 'HDFC';
    const TYPE_AXIS = 'AXIS';
    const TYPE_RAZORPAY = 'RAZORPAY';
    const TYPE_STATUS_PAID = 'PAID';
    const TYPE_STATUS_FAILED = 'FAILED';
    const TYPE_STATUS_PENDING = 'PENDING';
    const TYPE_STATUS_FOR_CREATED = 'CREATED';
    const PAYMENT_CAPTURED = 'captured';
    const PAYMENT_SUCCESS = 'success';
    const PAYMENT_FAILED = 'failed';
    const CSC_STATUS_SUCCESS = 'Success';
    const IS_NOT_PROCESS = 0;
    const IS_PROCESSED = 1;
    const IS_READY_TO_PROCESS = 2;
    const IS_PROCESSED_FAILED = -1;
    const IS_CONSUMED_YES = 1;
    const IS_CONSUMED_NO = 0;
    const IS_CONSUMED_REFUND = 2;

    // CC-Avenue(HDFC) Success
    const HDFC_PAYMENT_SHIPPED = 'shipped';
    const HDFC_PAYMENT_SUCCESSFUL = 'successful';
    // CC-Avenue(HDFC) Failed Status
    const HDFC_PAYMENT_ABORTED = 'Aborted';
    const HDFC_PAYMENT_FAILURE = 'Failure';
    const HDFC_PAYMENT_AUTO_CANCELLED = 'Auto-Cancelled';
    const HDFC_PAYMENT_CANCELLED = 'Cancelled';
    const HDFC_PAYMENT_INVALID = 'Invalid';
    const HDFC_PAYMENT_FRAUD = 'Fraud';    
    const HDFC_PAYMENT_UNSUCCESSFUL = 'Unsuccessful';
    const HDFC_PAYMENT_TIMEOUT = 'Timeout';
    
    // CC-Avenue(HDFC) Refund
    const HDFC_PAYMENT_REFUNDED = 'Refunded';
    const HDFC_PAYMENT_CHARGEBACK = 'Chargeback';
    const HDFC_PAYMENT_SYSTEMREFUND = 'Systemrefund';
    // CC-Avenue(HDFC) Pending
    const HDFC_PAYMENT_AWAITED = 'Awaited';
    const HDFC_PAYMENT_INITIATED = 'Initiated';
    
    const LIMIT = 5;
    
    public $prevData = null;
    public $schedulerJobError;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
        ];
    }

    public static function hdfcFailedStatus()
    {
        return [
            self::HDFC_PAYMENT_ABORTED,
            self::HDFC_PAYMENT_FAILURE,
            self::HDFC_PAYMENT_AUTO_CANCELLED,
            self::HDFC_PAYMENT_CANCELLED,
            self::HDFC_PAYMENT_INVALID,
            self::HDFC_PAYMENT_FRAUD,
            self::HDFC_PAYMENT_UNSUCCESSFUL,
            self::HDFC_PAYMENT_TIMEOUT            
        ];
    }

    public static function hdfcSuccessStatus()
    {
        return [
            self::PAYMENT_SUCCESS,
            self::HDFC_PAYMENT_SHIPPED,
            self::HDFC_PAYMENT_SUCCESSFUL
        ];
    }

    public static function hdfcPendingStatus()
    {
        return [
            self::HDFC_PAYMENT_AWAITED,
            self::HDFC_PAYMENT_INITIATED
        ];
    }

    public static function getStatusDropdown()
    {
        return [
            self::TYPE_STATUS_PAID => 'PAID',
            self::TYPE_STATUS_FAILED => 'FAILED',
            self::TYPE_STATUS_PENDING => 'PENDING'
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }
        
        if (isset($params['transactionId'])) {
            $modelAQ->andWhere($tableName . '.transaction_id =:transactionId', [':transactionId' => $params['transactionId']]);
        }

        if (isset($params['gatewayId'])) {
            $modelAQ->andWhere($tableName . '.gateway_id =:gatewayId', [':gatewayId' => $params['gatewayId']]);
        }
        
        if (isset($params['applicantId'])) {
            $modelAQ->andWhere($tableName . '.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
        }
        
        if (isset($params['payStatus'])) {
            $modelAQ->andWhere($tableName . '.status =:payStatus', [':payStatus' => $params['payStatus']]);
        }
        
        if (isset($params['isConsumed'])) {
            $modelAQ->andWhere($tableName . '.is_consumed =:isConsumed', [':isConsumed' => $params['isConsumed']]);
        }
        
        if (isset($params['type'])) {
            $modelAQ->andWhere($tableName . '.type =:type', [':type' => $params['type']]);
        }
        
        if (isset($params['inPayStatus'])) {
            $modelAQ->andWhere(['IN', $tableName.'.status', $params['inPayStatus']]);
        }
        
        if (isset($params['inType'])) {
            $modelAQ->andWhere(['IN', $tableName.'.type', $params['inType']]);
        }
        
        if (isset($params['isProcessed'])) {
            $modelAQ->andWhere($tableName . '.is_processed =:isProcessed', [':isProcessed' => $params['isProcessed']]);
        }
        
        if (isset($params['lessCreatedOn'])) {
            $modelAQ->andWhere(['<=', $tableName.'.created_on', $params['lessCreatedOn']]);
        }
        
        if (isset($params['greaterCreatedOn'])) {
            $modelAQ->andWhere(['>=', $tableName.'.created_on', $params['greaterCreatedOn']]);
        }
        
        if (isset($params['joinWithApplicantFee']) && in_array($params['joinWithApplicantFee'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantFee']}('applicant_fee', 'applicant_fee.id = transaction.applicant_fee_id');

            if (isset($params['applicantPostId'])) {
                $modelAQ->andWhere('applicant_fee.applicant_post_id = :applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
            }
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByGatewayId($gatewayId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['gatewayId' => $gatewayId], $params));
    }
    
    public static function findByKeys($params = [])
    {
        return self::findByParams($params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByTransactionId($transactionId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['transactionId' => $transactionId], $params));
    }
    
    public static function findByApplicantId($applicantId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantId' => $applicantId], $params));
    }
    
    public function updateFailedTransaction($id, $message = NULL)
    {
        $model = Transaction::findById($id);

        $updateAttr = [
            'is_processed' => self::IS_PROCESSED_FAILED,
            'failed_msg' => $message
        ];

        Transaction::updateAll($updateAttr, 'id=:id', [':id' => $id]);
    }

    public function createTransaction($data)
    {
        try {
            $model = new Transaction;
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if (!$model->save()) {
                return FALSE;
            }
        }
        catch (\Exception $ex) {
            return FALSE;
        }
        return $model->id;
    }
    
    public function createTransactionId()
    {
        while (true)
        {
            $transactionId = substr(hash('sha256', mt_rand() . microtime()), 0, 20);;
            $exists = Transaction::find()->where('transaction_id = :transactionId ', [':transactionId' => $transactionId])->exists();
            if (!$exists) {
                break;
            }
        }
        return $transactionId;
    }
    
    public static function processAfterEserviceTransaction($applicantId, $transactionId)
    {
        $model = self::findById($transactionId, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        $applicantModel = Applicant::findById($applicantId, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if (empty($model) || empty($applicantModel)) {
            return false;
        }

        $applicantFeesModel = ApplicantFee::findById($model->applicant_fee_id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if (empty($applicantFeesModel)) {
            return false;
        }
        $applicantFeesModel->status = ApplicantFee::STATUS_PAID;
        if($applicantFeesModel->save(TRUE, ['status'])){
            if ($applicantFeesModel->module == ApplicantFee::MODULE_APPLICATION) {
                $applicantPostModel = \common\models\ApplicantPost::findById($applicantFeesModel->applicant_post_id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
                ]);
                $applicantPostModel->payment_status = \common\models\ApplicantPost::STATUS_PAID;
                $applicantPostModel->application_status = \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED;
                $applicantPostModel->save(true, ['payment_status', 'application_status']);
                ApplicantPost::generateApplicationNo($applicantPostModel->id);
                ApplicantPost::cloneMasterProfile($applicantPostModel->id);
                Applicant::updateFormStep($applicantPostModel->applicant_id, Applicant::FORM_STEP_SUBMITTED);
                \Yii::$app->email->sendPaymentSuccessEmail($applicantPostModel->id);
            }
        } 
    }
    
    public static function processAfterTransaction($applicantId, $transactionId)
    {
        $model = self::findById($transactionId, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        $applicantModel = Applicant::findById($applicantId, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if (empty($model) || empty($applicantModel)) {
            return false;
        }

        $applicantFeesModel = ApplicantFee::findById($model->applicant_fee_id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if (empty($applicantFeesModel)) {
            return false;
        }
        $applicantFeesModel->status = ApplicantFee::STATUS_PAID;
        if($applicantFeesModel->save(TRUE, ['status'])){
            if ($applicantFeesModel->module == ApplicantFee::MODULE_APPLICATION) {
                $applicantPostModel = \common\models\ApplicantPost::findById($applicantFeesModel->applicant_post_id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
                ]);
                $applicantPostModel->payment_status = \common\models\ApplicantPost::STATUS_PAID;
                $applicantPostModel->application_status = \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED;
                $applicantPostModel->save(true, ['payment_status', 'application_status']);
                ApplicantPost::generateApplicationNo($applicantPostModel->id);
                ApplicantPost::cloneMasterProfile($applicantPostModel->id);
                Applicant::updateFormStep($applicantPostModel->applicant_id, Applicant::FORM_STEP_SUBMITTED);
                \Yii::$app->email->sendPaymentSuccessEmail($applicantPostModel->id);
            }
            else if ($applicantFeesModel->module == ApplicantFee::MODULE_ESERVICE) {
                $applicantPostModel = \common\models\ApplicantPost::findById($applicantFeesModel->applicant_post_id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
                ]);
                $applicantPostModel->payment_status = \common\models\ApplicantPost::STATUS_PAID;
                $applicantPostModel->application_status = \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED;
                $applicantPostModel->save(true, ['payment_status', 'application_status']);
                
                $parentApplicantPostModel = \common\models\ApplicantPost::findById($applicantPostModel->parent_applicant_post_id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
                ]);
                ApplicantPost::generateApplicationNo($applicantPostModel->id);
                $parentApplicantPostModel->application_status = \common\models\ApplicantPost::APPLICATION_STATUS_ESERVICE;
                $parentApplicantPostModel->save(true, ['application_status']);
                \Yii::$app->email->sendPaymentSuccessEmail($applicantPostModel->id);
            }
        } 
    }
    
    public function createSchedulerJob($paymentGateway)
    {
        try {
            $params = [
                'selectCols' => 'transaction.id',
                'isProcessed' => self::IS_NOT_PROCESS,
                'inPayStatus' => [
                    self::TYPE_STATUS_FOR_CREATED,
                    self::TYPE_STATUS_PENDING,
                    //self::TYPE_STATUS_FAILED,
                ],
                'type' => $paymentGateway,
                'limit' => self::LIMIT,
                'orderBy' => [
                    'id' => SORT_DESC
                ],
                'lessCreatedOn' => strtotime('-4 hours'),
                'resultCount' => caching\ModelCache::RETURN_ALL,
            ];

            $transactionModel = self::findByParams($params);
            if ($transactionModel == NULL) {
                return FALSE;
            }

            foreach ($transactionModel as $transaction) {
                $transactionModel = new \common\models\Transaction;
                $transactionModel->processScheduler($transaction['id'], $paymentGateway);
                /*$sqsJobId = \common\models\SqsJob::createPaymentSchedulerJob(['transactionData' => $transaction['id'], 'paymentApi' => $paymentGateway]);
                Transaction::updateAll([
                    'is_processed' => self::IS_READY_TO_PROCESS,
                    'sqs_job_id' => $sqsJobId
                ], 'id=:id', [':id' => $transaction['id']]);*/
            }
        }
        catch (\Exception $ex) {
            \Yii::error('Transaction Scheduler Job Error - ' . $ex->getMessage());
        }
        return TRUE;
    }
    
    
    public function createSchedulerJobByManual($paymentGateway)
    {
        try {
            $params = [
                'selectCols' => 'transaction.id',
                'isProcessed' => self::IS_NOT_PROCESS,
                'inPayStatus' => [
                    self::TYPE_STATUS_FOR_CREATED,
                    self::TYPE_STATUS_PENDING,
                    self::TYPE_STATUS_FAILED,
                ],
                'type' => $paymentGateway,
                'limit' => self::LIMIT,
                'orderBy' => [
                    'id' => SORT_DESC
                ],
                'lessCreatedOn' => strtotime('-4 hours'),
                'resultCount' => caching\ModelCache::RETURN_ALL,
            ];

            $transactionModel = self::findByParams($params);
            if ($transactionModel == NULL) {
                return FALSE;
            }

            foreach ($transactionModel as $transaction) {
                $transactionModel = new \common\models\Transaction;
                $transactionModel->processScheduler($transaction['id'], $paymentGateway);
                /*$sqsJobId = \common\models\SqsJob::createPaymentSchedulerJob(['transactionData' => $transaction['id'], 'paymentApi' => $paymentGateway]);
                Transaction::updateAll([
                    'is_processed' => self::IS_READY_TO_PROCESS,
                    'sqs_job_id' => $sqsJobId
                ], 'id=:id', [':id' => $transaction['id']]);*/
            }
        }
        catch (\Exception $ex) {
            \Yii::error('Transaction Scheduler Job Error - ' . $ex->getMessage());
        }
        return TRUE;
    }

    public function processScheduler($transactionIds, $paymentApi)
    {
        if (empty($transactionIds) || empty($paymentApi)) {
            return FALSE;
        }
        
        $transactionModel = Transaction::findByParams([
                    'selectCols' => [
                        'transaction.id', 'transaction.status'
                    ],
                    'id' => $transactionIds
        ]);
        
        if ($transactionModel == NULL || empty($transactionModel)) {
            throw new \components\exceptions\AppException('Sorry, Transaction Model cannot be null');
        }
        
        if ($transactionModel['status'] == self::TYPE_STATUS_PAID) {
            return TRUE;
        }

        try {
            if ($paymentApi == self::TYPE_RAZORPAY) {
                $this->processRazorpayScheduler($transactionIds, $paymentApi);
            } elseif ($paymentApi == self::TYPE_CSC) {
                $this->processCscScheduler($transactionIds, $paymentApi);
            } elseif($paymentApi == self::TYPE_HDFC){
                $this->processCcAvenueScheduler($transactionIds, $paymentApi);
            }
            /*elseif($paymentApi == self::TYPE_BOB){
                $this->processBobScheduler($transactionIds, $paymentApi);
            }elseif($paymentApi == self::TYPE_AXIS){
                $this->processAxisScheduler($transactionIds, $paymentApi);
            } */
        }
        catch (\Exception $ex) {
            $this->schedulerJobError = 'Transaction Error - ' . $ex->getMessage();
           // Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id IN(' . implode(',', $transactionIds) . ')');
            Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED],'id=:id', [':id' => $transactionIds]);
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Payment Verification by payu
     * https://documentation.payubiz.in/check-status-apis/
     * 
     * @param type $transactionIds
     * @param type $paymentApi
     * @return boolean
     */
    public function processCcAvenueScheduler($transactionId, $paymentApi)
    {
        $transactionModel = Transaction::findByParams([
                    'selectCols' => [
                        'id', 'transaction.transaction_id', 'applicant_fee_id', 'requested_data', 'amount'
                    ],
                    //'inIds' => $transactionIds,
                    'id' => $transactionId
        ]);
        
        if ($transactionModel == NULL || empty($transactionModel)) {
            throw new \components\exceptions\AppException('Sorry, Transaction Model cannot be null');
        }

        $payuObj = new CcAvenue(['paymentMethod' => $paymentApi]);
        $payuObj->verifyPayment($transactionModel['transaction_id']);
        // empty payu response
        if (empty($payuObj->response)) {
            Transaction::updateAll(['failed_msg' => 'No Response Captured from gateway', 'status' => self::TYPE_STATUS_FAILED, 'is_processed' => self::IS_PROCESSED_FAILED], 'id =:id', [':id' => $transactionId]);
            return false;
        }
        
        $flag = true;
        $failedMsg = '';
        $response = $payuObj->response;
        $requestedData = \yii\helpers\Json::decode($transactionModel['requested_data']);
        if ((isset($response['order_amt']) && $transactionModel['amount'] != $response['order_amt']) ||
                (isset($response['order_no']) && $transactionModel['transaction_id'] != $response['order_no']) ||
                (isset($response['merchant_param1']) && isset($requestedData['merchant_param1']) && $requestedData['merchant_param1'] != $response['merchant_param1']) ||
                (isset($response['merchant_param2']) && isset($requestedData['merchant_param2']) && $requestedData['merchant_param2'] != $response['merchant_param2']) ||
                (isset($response['merchant_param3']) && isset($requestedData['merchant_param3']) && $requestedData['merchant_param3'] != $response['merchant_param3']) ||
                (isset($response['merchant_param4']) && isset($requestedData['merchant_param4']) && $requestedData['merchant_param4'] != $response['merchant_param4']) ||
                (isset($response['merchant_param5']) && isset($requestedData['merchant_param5']) && $requestedData['merchant_param5'] != $response['merchant_param5'])) {
            $flag = false;
            $failedMsg = 'Response parameters did not matched.';
        }
        if (isset($response['reference_no'])) {
            $gatewayIdExist = Transaction::findByGatewayId($response['reference_no'], ['selectCols' => ['id'], 'countOnly' => true]);
            if ($gatewayIdExist > 0) {
                $flag = false;
                $failedMsg = 'Duplicate tracking_id in response.';
            }
        }

        if(!$flag) {
            /*Transaction::updateAll([
                'status' => self::TYPE_STATUS_FAILED,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
                'failed_msg' => $failedMsg
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);*/

            $transactionMM = Transaction::findOne(['id' => $transactionModel['id']]);
            $transactionMM->status = self::TYPE_STATUS_FAILED;
            $transactionMM->is_processed = self::IS_PROCESSED;
            $transactionMM->response = \yii\helpers\Json::encode($response);
            $transactionMM->failed_msg = $failedMsg;
            $transactionMM->save();
        }
        else if (isset($response['order_status']) && \yii\helpers\ArrayHelper::isIn(strtolower($response['order_status']), Transaction::hdfcSuccessStatus())) {

            $model = Transaction::findByParams(['transactionId' => $response['order_no']]);
            
            $updateAttr = [
                'gateway_id' => $response['reference_no'],
                'response_amount' => $response['order_amt'],
                'status' => self::TYPE_STATUS_PAID,
                'is_processed' => self::IS_PROCESSED,
                'is_consumed' => self::checkIsConsumed($model['applicant_fee_id']),
                'response' => \yii\helpers\Json::encode($response),
                'failed_msg' => !empty($failedMsg) ? $failedMsg : $response['order_bank_response']
            ];

            Transaction::updateAll($updateAttr, 'id=:id', [':id' => $model['id']]);

            try {
                self::processAfterTransaction($model['id']);
            }
            catch (\Exception $ex) {
                \Yii::error('SQS Transaction Error - ' . $model['id'] . ':' . $ex->getMessage());
                $this->updateFailedTransaction($model['id'],  $ex->getMessage());
            }
        }
        else if (isset($response['order_status']) && \yii\helpers\ArrayHelper::isIn(strtolower($response['order_status']), Transaction::hdfcFailedStatus())) {
            
            /*Transaction::updateAll([
                'status' => self::TYPE_STATUS_FAILED,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
                'gateway_id' => !empty($response['reference_no']) ? $response['reference_no'] : NULL,
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);*/

            $transactionMM = Transaction::findOne(['id' => $transactionModel['id']]);
            $transactionMM->status = self::TYPE_STATUS_FAILED;
            $transactionMM->is_processed = self::IS_PROCESSED;
            $transactionMM->response = \yii\helpers\Json::encode($response);
            $transactionMM->gateway_id = !empty($response['reference_no']) ? $response['reference_no'] : NULL;
            $transactionMM->save();
        }
        else {
            /*Transaction::updateAll([
                'status' => self::TYPE_STATUS_PENDING,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
                'gateway_id' => !empty($response['reference_no']) ? $response['reference_no'] : NULL,
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);*/

            $transactionMM = Transaction::findOne(['id' => $transactionModel['id']]);
            $transactionMM->status = self::TYPE_STATUS_PENDING;
            $transactionMM->is_processed = self::IS_PROCESSED;
            $transactionMM->response = \yii\helpers\Json::encode($response);
            $transactionMM->gateway_id = !empty($response['reference_no']) ? $response['reference_no'] : NULL;
            $transactionMM->save();
        }
        
        return TRUE;   
    }
    
    /**
     * Payment Verification by BOB
     * 
     * @param type $transactionIds
     * @param type $paymentApi
     * @return boolean
     */
    public function processBobScheduler($transactionId, $paymentApi)
    {
        $transactionModel = Transaction::findByParams([
                    'selectCols' => [
                        'transaction.id', 
                        'transaction.transaction_id',
                        'transaction.amount',
                    ],
                    'id' => $transactionId
        ]);
        
        if ($transactionModel == NULL || empty($transactionModel)) {
            throw new \components\exceptions\AppException('Sorry, Transaction Model cannot be null');
        }
        
        $bobObj = new iPay24Pipe;
        $resourcePath =  dirname(dirname(__DIR__)) . "/components/bob/cgnfile/";
        $bobObj->setResourcePath(trim($resourcePath));
        $bobObj->setKeystorePath(trim($resourcePath));
        
        $bobObj->setAlias(Yii::$app->params['bob.alias']);
        $bobObj->setaction(8); //2 for refund 8 for status
        $bobObj->setAmt($transactionModel['amount']);
        $bobObj->setTransId($transactionModel['transaction_id']);
        $bobObj->setUdf5('TrackID');
        $bobObj->setTrackId(substr(number_format(time() * rand(),0,'',''),0,10));
        
        if(trim($bobObj->performTransaction())!=0)  {
            echo "Error sending TranPipe Request: ". $obj->getDebugMsg();die;
        }
        $response = [
            'status' => strtolower($bobObj->getResult()),
            'gateway_status' => $bobObj->getResult(),
            'transaction_date' =>  $bobObj->getDates(),
            'transaction_reference_id' => $bobObj->getRef(),
            'transaction_track_id' => $bobObj->getTrackId(),
            'transaction_td' => $bobObj->getTransId(),
            'payment_id' => $bobObj->getPaymentId(),
            'amount' => $bobObj->getAmt(),
            'udf5' => $bobObj->getUdf5(),
            'udf6' => $bobObj->getUdf6(),
            'udf7' => $bobObj->getUdf7(),
            'udf8' => $bobObj->getUdf8()
        ];
        // empty payu response
        if (empty($response)) {
            Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id =:id', [':id' => $transactionId]);
           // Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id IN(' . implode(',', $transactionIds) . ')');
            throw new \components\exceptions\AppException('No Response Captured from payu');
        }
        
        if (isset($response['status']) && strtolower($response['status']) == self::PAYMENT_SUCCESS) {

            $model = Transaction::findByParams(['transactionId' => $transactionModel['transaction_id']]);
            
            $updateAttr = [
                'gateway_id' => $response['transaction_td'],
                'response_amount' => $response['amount'],
                'status' => self::TYPE_STATUS_PAID,
                'is_processed' => self::IS_PROCESSED,
                'is_consumed' => self::checkIsConsumed($model['applicant_fee_id']),
                'response' => \yii\helpers\Json::encode($response)
            ];

            Transaction::updateAll($updateAttr, 'id=:id', [':id' => $model['id']]);

            try {
                self::processAfterTransaction($model['applicant_id'], $model['id']);
            }
            catch (\Exception $ex) {
                \Yii::error('SQS Transaction Error - ' . $model['id'] . ':' . $ex->getMessage());
                $this->updateFailedTransaction($model['id'],  $ex->getMessage());
            }
        }
        else {
            Transaction::updateAll([
                'status' => self::TYPE_STATUS_FAILED,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
                'gateway_id' => !empty($response['transaction_td']) ? $response['transaction_td'] : NULL,
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);
        }
        
        return TRUE;
    }
    
    /**
     * process Axis Scheduler
     * @param type $transactionId
     * @param type $paymentApi
     * @return boolean
     * @throws \components\exceptions\AppException
     */
    public function processAxisScheduler($transactionId, $paymentApi)
    {
        $transactionModel = Transaction::findByParams([
                    'selectCols' => [
                        'transaction.id', 'transaction.transaction_id'
                    ],
                    //'inIds' => $transactionIds,
                    'id' => $transactionId
        ]);
        
        if ($transactionModel == NULL || empty($transactionModel)) {
            throw new \components\exceptions\AppException('Sorry, Transaction Model cannot be null');
        }

        $axisObj = new \components\axis\Axis();
        $enquiryObj = new \components\axis\Enquiry($axisObj->verifyPaymentUrl, $axisObj->checksum_key, $axisObj->encryption_key);
        $result = $enquiryObj->callEasyPayEnquiry($axisObj->cid, $transactionModel['transaction_id'], $transactionModel['transaction_id'], $axisObj->ver, $axisObj->typ);
        
        $gatewayParams = explode("&", $result);

        $response = [];
        foreach ($gatewayParams as $param) {
            $explode = explode("=", $param);
            $response[$explode[0]] = $explode[1];
        }
        // empty payu response
        if (empty($response)) {
            Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id =:id', [':id' => $transactionId]);
           // Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id IN(' . implode(',', $transactionIds) . ')');
            throw new \components\exceptions\AppException('No Response Captured from payu');
        }
        
        if (isset($response['STC']) && $response['STC'] == $axisObj->resSuccess) {

            $model = Transaction::findByParams(['transactionId' => $response['RID']]);
            
            $updateAttr = [
                'gateway_id' => $response['RID'],
                'response_amount' => $response['AMT'],
                'status' => self::TYPE_STATUS_PAID,
                'is_processed' => self::IS_PROCESSED,
                'is_consumed' => self::checkIsConsumed($model['applicant_fee_id']),
                'response' => \yii\helpers\Json::encode($response)
            ];

            Transaction::updateAll($updateAttr, 'id=:id', [':id' => $model['id']]);

            try {
                self::processAfterTransaction($model['applicant_id'], $model['id']);
            }
            catch (\Exception $ex) {
                \Yii::error('SQS Transaction Error - ' . $model['id'] . ':' . $ex->getMessage());
                $this->updateFailedTransaction($model['id'],  $ex->getMessage());
            }
        }
        else {
            Transaction::updateAll([
                'status' => self::TYPE_STATUS_FAILED,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
                'gateway_id' => !empty($response['RID']) ? $response['RID'] : NULL,
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);
        }
        
        return TRUE;   
    }
    
    /**
     * process Axis Scheduler
     * @param type $transactionId
     * @param type $paymentApi
     * @return boolean
     * @throws \components\exceptions\AppException
     */
    public function processRazorpayScheduler($transactionId, $paymentApi)
    {
        $transactionModel = Transaction::findByParams([
                    'selectCols' => [
                        'transaction.id', 'transaction.transaction_id'
                    ],
                    //'inIds' => $transactionIds,
                    'id' => $transactionId
        ]);
        
        if ($transactionModel == NULL || empty($transactionModel)) {
            throw new \components\exceptions\AppException('Sorry, Transaction Model cannot be null');
        }

        $obj = new \common\components\Razorpay;
        $response = $obj->getOrderPayment($transactionModel['transaction_id']);

        // empty payu response
        if (empty($response)) {
            Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id =:id', [':id' => $transactionId]);
           // Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id IN(' . implode(',', $transactionIds) . ')');
            throw new \components\exceptions\AppException('No Response Captured from RazorPay');
        }

        if (isset($response->count) && $response->count > 0) {
            
            foreach ($response->items as $item) {
                if ($item->status == self::PAYMENT_CAPTURED) {
                    break;
                }
            }
            $model = Transaction::findByParams(['transactionId' => $item->order_id]);
            
            $updateAttr = [
                'gateway_id' => $item->id,
                'response_amount' => $item->amount / 100,
                'status' => self::TYPE_STATUS_FAILED,
                'response' => \yii\helpers\Json::encode($response),
                'failed_msg' => $item->status,
            ];
            if ($item->status == self::PAYMENT_CAPTURED) {
                $updateAttr['status'] = self::TYPE_STATUS_PAID;
                $updateAttr['is_processed'] = self::IS_PROCESSED;
                $updateAttr['is_consumed'] = self::checkIsConsumed($model['applicant_fee_id']);
            }

            Transaction::updateAll($updateAttr, 'id=:id', [':id' => $model['id']]);

            try {
                if ($item->status == self::PAYMENT_CAPTURED) {
                    self::processAfterTransaction($model['applicant_id'], $model['id']);
                }
            }
            catch (\Exception $ex) {
                \Yii::error('SQS Transaction Error - ' . $model['id'] . ':' . $ex->getMessage());
                $this->updateFailedTransaction($model['id'],  $ex->getMessage());
            }
        }
        else {
            /*Transaction::updateAll([
                'status' => self::TYPE_STATUS_FAILED,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
                'gateway_id' => NULL,
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);*/

            $transactionMM = Transaction::findOne(['id' => $transactionModel['id']]);
            $transactionMM->status = self::TYPE_STATUS_FAILED;
            $transactionMM->is_processed = self::IS_PROCESSED;
            $transactionMM->response = \yii\helpers\Json::encode($response);
            $transactionMM->gateway_id = NULL;
            $transactionMM->save();
        }
        
        return TRUE;   
    }
    
    public static function getIsconsumedStatus()
    {
        return [
            self::IS_CONSUMED_YES => 'Yes',
            self::IS_CONSUMED_NO => 'No',
        ];
    }
    
    public static function getStatus()
    {
        return [
            self::TYPE_STATUS_PAID => 'Paid',
            self::TYPE_STATUS_FAILED => 'Failed',
        ];
    }
    
    public static function getTypeDropdown()
    {
        return [
            Transaction::TYPE_BOB => 'BOB',
            Transaction::TYPE_CSC => 'CSC',
            Transaction::TYPE_HDFC => 'HDFC',
            Transaction::TYPE_AXIS => 'AXIS'
        ];
    }
    
    public static function checkIsConsumed($applicantFeeId)
    {
        $applicantFeeModel = ApplicantFee::findById($applicantFeeId);
        
        $isModulePaid = ApplicantFee::findByModule($applicantFeeModel['module'], [
            'applicantId' => $applicantFeeModel['applicant_id'],
            'applicantPostId' => $applicantFeeModel['applicant_post_id'],
            'payStatus' => ApplicantFee::STATUS_PAID,
            'existOnly' => true
        ]);
        
        if($isModulePaid){
            return self::IS_CONSUMED_NO;
        }
        
        return self::IS_CONSUMED_YES;
    }
    
    public static function getConsumeDropdown()
    {
        return [
            Transaction::IS_CONSUMED_YES => 'Yes',
            Transaction::IS_CONSUMED_NO => 'No',
            Transaction::IS_CONSUMED_REFUND => 'Refund',
        ];
    }
    
    /**
     * Payment Verification by CSC
     * 
     * @param type $transactionId
     * @param type $paymentApi
     * @return boolean
     */
    public function processCscScheduler($transactionId, $paymentApi)
    {
        $transactionModel = Transaction::findByParams([
                    'selectCols' => [
                        'id', 'transaction.transaction_id', 'order_detail_id', 'requested_data', 'amount'
                    ],
                    'id' => $transactionId
        ]);

        if ($transactionModel == NULL || empty($transactionModel)) {
            throw new \components\exceptions\AppException('Sorry, Transaction Model cannot be null');
        }

        $Obj = new \components\cscwallet\CscWallet([
            'paymentMethod' => $paymentApi
        ]);

        $response = $Obj->get_status($transactionModel['transaction_id']);
        //echo '<pre>';print_r($response);die;
        // empty CSC response
        if (empty($response)) {
            Transaction::updateAll(['is_processed' => self::IS_PROCESSED_FAILED], 'id =:id', [':id' => $transactionIds]);
        }
        if (isset($response['response_status']) && !empty($response['response_status']) && in_array($response['response_status'], [self::CSC_STATUS_SUCCESS])) {
            $model = Transaction::findByParams(['transactionId' => $response['merchant_txn']]);

            $updateAttr = [
                'gateway_id' => $response['csc_txn'],
                'response_amount' => $response['txn_amount'],
                'status' => self::TYPE_STATUS_PAID,
                'is_processed' => self::IS_PROCESSED,
                'is_consumed' => self::checkIsConsumed($model['order_detail_id']),
                'failed_msg' => $response['response_message']
            ];

            Transaction::updateAll($updateAttr, 'id=:id', [':id' => $model['id']]);

            try {
                self::processAfterTransaction($model['applicant_id'], $model['id']);
            } catch (\Exception $ex) {
                \Yii::error('SQS Transaction Error - ' . $model['id'] . ':' . $ex->getMessage());
                $this->updateFailedTransaction($model['id'], $ex->getMessage());
            }
        } else {
            /*Transaction::updateAll([
                'status' => self::TYPE_STATUS_FAILED,
                'is_processed' => self::IS_PROCESSED,
                'response' => \yii\helpers\Json::encode($response),
            ], 'transaction_id =:transactionId', [':transactionId' => $transactionModel['transaction_id']]);*/

            $transactionMM = Transaction::findOne(['id' => $transactionModel['id']]);
            $transactionMM->status = self::TYPE_STATUS_FAILED;
            $transactionMM->is_processed = self::IS_PROCESSED;
            $transactionMM->response = \yii\helpers\Json::encode($response);
            $transactionMM->gateway_id = NULL;
            $transactionMM->save();
        }
        return TRUE;
    }

    public function afterSave($insert, $changedAttributes)
    {
        try {
            if(!$insert) {
                if(!empty($this->response) && isset($changedAttributes['response'])) {
                    $logTransation = new \common\models\LogTransaction();
                    $logTransation->gateway_id = (string)$this->gateway_id;
                    $logTransation->transaction_id = $this->id;
                    $logTransation->response_amount = $this->response_amount;
                    $logTransation->status = $this->status;
                    $logTransation->response = $this->response;
                    $logTransation->save();
                }
            }
            
        }
        catch (\Exception $ex) {}
        
        return parent::afterSave($insert, $changedAttributes);
    }
}