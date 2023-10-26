<?php
namespace common\components;

use common\models\MstConfiguration;
use Razorpay\Api\Api;
use Yii;
use components\Helper;

class Razorpay extends \yii\base\Component
{

    public $apiKey = null;
    public $apiSecret = null;

    private $_razorPay;


    public function init()
    {
        $mstConfigModel = MstConfiguration::findByType(MstConfiguration::TYPE_RAZORPAY);
        if(empty($mstConfigModel)) {
            throw new \components\exceptions\AppException("Invalid request.");
        }

        $mstConfigModel = $this->decryptValues($mstConfigModel);

        $this->apiKey = $mstConfigModel['config_val1']; //"rzp_test_7CGJtZjrA0PiXu";
        $this->apiSecret = $mstConfigModel['config_val2']; //"uA6oVvEW61GIx9eTvMwwaQVM";
        $this->_razorPay = new Api($this->apiKey, $this->apiSecret);
        return parent::init();
    }
    
    private function decryptValues($model)
    {
        $model['config_val1'] = !empty($model['config_val1']) ? Helper::decryptString($model['config_val1']) : '';
        $model['config_val2'] = !empty($model['config_val2']) ? Helper::decryptString($model['config_val2']) : '';
        $model['config_val3'] = !empty($model['config_val3']) ? Helper::decryptString($model['config_val3']) : '';
        $model['config_val4'] = !empty($model['config_val4']) ? Helper::decryptString($model['config_val4']) : '';
        $model['config_val5'] = !empty($model['config_val5']) ? Helper::decryptString($model['config_val5']) : '';
        $model['config_val6'] = !empty($model['config_val6']) ? Helper::decryptString($model['config_val6']) : '';
        $model['config_val7'] = !empty($model['config_val7']) ? Helper::decryptString($model['config_val7']) : '';

        return $model;
    }

    public function createOrder($params =[])
    {
        $params['currency'] = "INR";
        return $this->_razorPay->order->create($params);
    }

    public function refundAmount($paymentId)
    {
        return $this->_razorPay->refund->create(array('payment_id' => $paymentId));
    }
    public function verifySignature($params)
    {
        return $this->_razorPay->utility->verifyPaymentSignature($params);
    }

    public function refundPartialAmount($paymentId, $amount)
    {
        return $this->_razorPay->refund->create(array('payment_id' => $paymentId, 'amount' => $amount));
    }

    public function findRefundPayment($refundPaymentId)
    {
        return  $this->_razorPay->refund->fetch($refundPaymentId);
    }
    
    public function capturePayment($paymentId)
    {
        return $this->_razorPay->payment->fetch($paymentId);
    }

    public function getOrderPayment($orderId)
    {
        return $this->_razorPay->order->fetch($orderId)->payments();
    }

}