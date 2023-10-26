<?php

namespace components;

use Yii;
use yii\bootstrap\Html;
use common\models\MstConfiguration;

/**
 * Description of Payu
 *
 * @author Pawan Kumar
 */
class Payu extends \yii\base\Component
{

    private $merchantKey = '';
    private $merchantSalt = '';
    private $paymentMethod = 'UBI';
    private $verifyPaymentUrl = "https://info.payu.in/merchant/postservice?form=2";
    private $paymentDomain = 'https://secure.payu.in/_payment';
    private $successUrl;
    private $failureUrl;
    public $response;
    private $_config = [];
    private $postData = [];

    const EVENT_PAYU_FORM_DATA = 'setPayuFormData';

    public function init()
    {
        $configuration = MstConfiguration::findByType(MstConfiguration::PAYMENT_PAYU, [
                    'selectCols' => [
                        'mst_configuration.config_val1', 'mst_configuration.config_val2', 'mst_configuration.config_val3'
                    ],
                    'resultCount' => \common\models\caching\ModelCache::RETURN_ALL
        ]);
        if (empty($configuration)) {
            throw new \yii\base\InvalidConfigException("Invalid payu configuration.");
        }

        foreach ($configuration as $config) {
            $this->_config[strtoupper($config['config_val1'])] = $config;
        }

        return parent::init();
    }

    public function setCredentials($mode = 'UBI')
    {
        $this->merchantKey = $this->_config[$mode]['config_val2'];
        $this->merchantSalt = $this->_config[$mode]['config_val3'];
    }

    public function hashSalt($params = [])
    {
        $hash = '';
        $hashParams = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if (empty($params['hash']) && sizeof($params) > 0) {
            if (empty($params['key']) || empty($params['txnid']) || empty($params['amount']) || empty($params['firstname']) || empty($params['email']) || empty($params['phone']) || empty($params['productinfo']) || empty($params['surl']) || empty($params['furl']) || empty($params['service_provider'])) {
                $hashStr = '';
                $hashVarsSeq = explode('|', $hashParams);
                foreach ($hashVarsSeq as $index) {
                    $hashStr .= isset($params[$index]) ? $params[$index] : '';
                    $hashStr .= '|';
                }
                $hashStr .= $this->merchantSalt;
                $hash = strtolower(hash('sha512', $hashStr));
            }
        }
        return $hash;
    }

    public function createTransactionId()
    {
        // Generate random transaction id
        return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    }

    public function verifyPayment($transactionId)
    {
        $command = "verify_payment";
        $transactionDetails = is_array($transactionId) ? implode('|', $transactionId) : $transactionId;
        $hashStr = $this->merchantKey . '|' . $command . '|' . $transactionDetails . '|' . $this->merchantSalt;

        $hash = strtolower(hash('sha512', $hashStr));
        $postParams = [
            'key' => $this->merchantKey,
            'hash' => $hash,
            'var1' => $transactionDetails,
            'command' => $command
        ];

        $postQueryString = http_build_query($postParams);
        $url = $this->verifyPaymentUrl;

        try {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postQueryString);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $this->response = \yii\helpers\Json::decode(curl_exec($curl), TRUE);
            if (curl_errno($curl)) {
                throw new \yii\web\HttpException(curl_error($curl), 500);
            }

            curl_close($curl);
        }
        catch (\Exception $ex) {
            throw new \yii\web\HttpException('Payu Verify Payment Error - ' . $ex->getMessage(), 500);
        }
    }

    public function setFormPayment($paymentMethod, $amount, $params = [])
    {
        $this->setPaymentMethod($paymentMethod);

        $this->postData = [
            'key' => $this->merchantKey,
            'txnid' => $this->createTransactionId(),
            'amount' => $amount,
        ];

        $this->trigger(self::EVENT_PAYU_FORM_DATA);

        $this->postData['hash'] = $this->hashSalt($this->postData);
        $this->postData['surl'] = $this->getSuccessUrl();
        $this->postData['furl'] = $this->getFailureUrl();
    }

    public function redirectOnPayment()
    {
        echo Html::beginForm($this->paymentDomain, 'POST', ['id' => 'payuPaymentForm', 'name' => 'payu']);
        foreach ($this->postData as $name => $value) {
            echo Html::input('hidden', $name, $value);
        }
        Html::submitButton('Pay', ['class' => 'btn btn-primary']);
        echo '<script>document.payu.submit();</script>';
    }

    public function setFormPaymentData($data)
    {
        $this->postData = \yii\helpers\ArrayHelper::merge($this->postData, $data);
    }

    public function getFormPaymentData()
    {
        return $this->postData;
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setSuccessUrl($url)
    {
        $this->successUrl = $url;
    }

    public function getSuccessUrl()
    {
        return $this->successUrl;
    }

    public function setFailureUrl($url)
    {
        $this->failureUrl = $url;
    }

    public function getFailureUrl()
    {
        return $this->failureUrl;
    }

}
