<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace components\integration\payment\icici;

/**
 * Description of Icici
 *
 * @author Amit Handa
 */
class Icici extends \yii\base\Component
{

    public $merchantKey = '242198';
    public $aesKey = '2415800721901001';
    public $submerchantKey = '1234';
    public $returnUrl;
    public $environment = 'PROD'; //PROD
    public $payMode;
    public $defaultStatus;
    public $verifyUrl = 'https://eazypay.icicibank.com/EazyPGVerify';
    public $refundUrl = 'https://eazypay.icicibank.com/OnlineRefundService/rest/OnlineRefundService/OnlineRefundDetails';
    public $response;
    public $postData;
    public $url;
    public $referenceNo;
    public $amount;

    public function __construct($config = [])
    {
        $this->initParams($config);
    }

    public function initParams($config)
    {
        if(!isset($config['paymentMethod']) || empty($config['paymentMethod'])) {
            throw new exceptions\AppException("Invalid ICICI Payment Method.");
        }
        
        //$this->environment = \Yii::$app->params['icici.enabled.payment.gateway'];
        //$this->merchantKey = \Yii::$app->params['icici']['merchantKey'];
        //$this->submerchantKey = \Yii::$app->params['icici']['submerchantKey'];
//        $this->aesKey = \Yii::$app->params['icici'][\Yii::$app->params['icici.enabled.payment.gateway']]['aesKey'];
        $this->url = \Yii::$app->params['icici']['url'];
        $this->payMode = \Yii::$app->params['icici']['paymentMode'];
        $this->defaultStatus = \Yii::$app->params['icici']['defaultStatus'];
        $this->response = \Yii::$app->params['rootHttpPath'] . '/payment/icici/response';
    }
    
    public function aes128Encrypt($str, $key)
    {
        $block = mcrypt_get_block_size('rijndael_128', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .=str_repeat(chr($pad), $pad);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_ECB));
    }
    
    public function retrieveTransactionDetail($transactionId, $params = [])
    {
        $retreiveUrl = $this->verifyUrl . "?ezpaytranid=&amount=&paymentmode=&merchantid={$this->merchantKey}&trandate=&pgreferenceno={$transactionId}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $retreiveUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        if(!empty($response)){
            $data = [];
            $response = explode('&', $response);
            foreach ($response as $key => $value) {
                $arr = explode('=', $value);
                $data[$arr[0]] = $arr[1];
            }
        }
        return $data;
    }
    
    public function getPaymentUrl($params = [])
    {
        $query = '';
        foreach ($params as $key => $val) {
            $query .= $key . "=" . $val . "&";
        }
        $this->postData = rtrim($query, "&");
    }
    
    public function refund($transactionId, $params = [])
    {
        $inputData = [
            'Paymode' => $params['payMode'],
            'TransactionID' => $transactionId,
            'TransactionDate' => $params['transactionDate'],
            'MerchantId' => $this->merchantKey,
            'UserId' => $params['name'],
            'RefundAmount' => $params['amount'],
            'signature' => hash("sha512", $this->merchantKey. "|". $transactionId. "|" .$params['amount']),
        ];
        
        $data = [
            'MerchantId' => $this->merchantKey,
            'inputdata' => $this->aes128Encrypt(json_encode($inputData), $this->aesKey)
        ];
        $data = json_encode($data);
        
        try {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->refundUrl);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $this->response = curl_exec($curl);
            if (curl_errno($curl)) {
                throw new exceptions\AppException(curl_error($curl));
            }

            curl_close($curl);
        }
        catch (\Exception $ex) {
            throw new exceptions\AppException('ICICI Refund Payment Error - ' . $ex->getMessage());
        }
    }

}