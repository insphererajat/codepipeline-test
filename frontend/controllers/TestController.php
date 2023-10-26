<?php

namespace frontend\controllers;

use Yii;
use components\bob\iPay24Pipe;
use common\models\Transaction;

/**
 * Description of TestController
 *
 * @author Amit Handa
 */
class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        (new Transaction)->createSchedulerJob(Transaction::TYPE_HDFC);
//        return $this->render('index');
    }
    
    public function actionVerify()
    {
        $obj = new \components\CcAvenue(['paymentMethod' => 'HDFC']);
        $response = $obj->verifyPayment('7ace8575446a307ca7aa');
        pr($response);
    }
    
    public function actionRefund()
    {
        $obj = new \components\CcAvenue(['paymentMethod' => 'HDFC']);
        $response = $obj->refundPayment('109971155356');
        pr($response);
    }
    
    public function actionBobVerify()
    {
        $obj = new iPay24Pipe;
        $resourcePath =  dirname(dirname(dirname(__DIR__))) . "/service-commission/components/bob/cgnfile/";
        $obj->setResourcePath(trim($resourcePath));
        $obj->setKeystorePath(trim($resourcePath));
        
        $obj->setAlias(Yii::$app->params['bob.alias']);
        $obj->setaction(8); //2 for refund 8 for status
        $obj->setAmt(1.00);
        $obj->setTransId('c9b1e60b904e5ff13fc2');
        $obj->setUdf5('TrackID');
        $obj->setTrackId(substr(number_format(time() * rand(),0,'',''),0,10));
        
        if(trim($obj->performTransaction())!=0)  {
            echo "Error sending TranPipe Request: ". $obj->getDebugMsg();die;
        }
        $gatewayParams = [
            'status' => $obj->getResult(),
            'gateway_status' => $obj->getResult(),
            'transaction_date' =>  $obj->getDates(),
            'transaction_reference_id' => $obj->getRef(),
            'transaction_track_id' => $obj->getTrackId(),
            'transaction_td' => $obj->getTransId(),
            'payment_id' => $obj->getPaymentId(),
            'amount' => $obj->getAmt(),
            'udf5' => $obj->getUdf5(),
            'udf6' => $obj->getUdf6(),
            'udf7' => $obj->getUdf7(),
            'udf8' => $obj->getUdf8()
        ];
        
        pr($gatewayParams);
        
    }
    
    public function actionAppicationNo()
    {
        (new \common\models\ApplicantPost)->createSchedulerJob();
    }
    
    public function actionIp()
    {
        echo $_SERVER['SERVER_ADDR'];die;
    }
    
    public function actionScheduler()
    {
        (new Transaction)->createSchedulerJobByManual(Transaction::TYPE_RAZORPAY);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_HDFC);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_BOB);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_CSC);
    }
    
    public function actionAxis()
    {
        (new Transaction)->processAxisScheduler(323420, Transaction::TYPE_AXIS);
    }
    
    public function actionSms()
    {
        //Yii::$app->email->sendWelcomeApplicantEmail(39801, 'Samsung7');
        //\Yii::$app->sms->sendOtp(8872726621, ['otp' => 302010]);
        Yii::$app->email->sendCancelPostOtpEmail('insphere.amit@gmail.com', 'Amit', 898989, \common\models\MstMessageTemplate::SERVICE_CANCEL_POST_OTP);
    }
}
