<?php

namespace components\integration\payment\payu;

use Yii;

/**
 * Description of PayuMoney
 *
 * @author Amit Handa
 */
class PayuMoney extends \yii\base\Component

{
    const BLEAP_PAYMENT_METHOD = 'BLEAP';
    public $merchantKey;
    public $merchantSalt;
    public $paymentMethod;
    public $parentMerchantId;
    public $childMerchantId;
    public $childSplitPercent;
    public $parentSplitPercent;

    public $verifyPaymentUrl = "https://info.payu.in/merchant/postservice?form=2";

    public $response;

    public function __construct($config = [])
    {
        $this->initParams($config);
    }

    public function initParams($config)
    {
        if (!isset($config['paymentMethod']) || empty($config['paymentMethod'])) {
            throw new exceptions\AppException("Invalid Payu Payment Method.");
        }

        $this->paymentMethod = $config['paymentMethod'];
        $this->merchantKey = Yii::$app->params['payu'][$this->paymentMethod]['merchantKey'];
        $this->merchantSalt = Yii::$app->params['payu'][$this->paymentMethod]
            ['merchantSalt'];
        if ($this->paymentMethod == self::BLEAP_PAYMENT_METHOD) {
            $this->parentMerchantId = Yii::$app->params['payu'][$this->paymentMethod]['parentMerchantId'];
            $this->childMerchantId = Yii::$app->params['payu'][$this->paymentMethod]['childMerchantId'];
            $this->parentSplitPercent = Yii::$app->params['payu'][$this->paymentMethod]['parentSplitPercent'];
            $this->childSplitPercent = Yii::$app->params['payu'][$this->paymentMethod]['childSplitPercent'];
        }

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

    public function hashSaltv2($params = [])
    {
        $hash = '';
        // $hashParams = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $hashParams = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|||||";
        if (empty($params['hash']) && sizeof($params) > 0) {
            if (empty($params['key']) || empty($params['txnid']) || empty($params['amount']) || empty($params['firstname']) || empty($params['email']) || empty($params['phone']) || empty($params['productinfo']) || empty($params['surl']) || empty($params['furl']) || empty($params['service_provider'])) {
                $hashStr = '';
                $hashVarsSeq = explode('|', $hashParams);
                foreach ($hashVarsSeq as $index) {
                    $hashStr .= isset($params[$index]) ? $params[$index] : '';
                    $hashStr .= '|';
                }
                $hashStr .= $this->merchantSalt;
                // echo $hashStr; die;
                // $hash = strtolower(hash('sha512', $hashStr));
                $hash = (hash('sha512', $hashStr));
            }
        }
        return $hash;
    }

    public function validateHash($params = [])
    {
        // $hashParams = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $hashParams = "status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key";
        if (sizeof($params) > 0) {
            if (empty($params['key']) || empty($params['txnid']) || empty($params['amount']) || empty($params['firstname']) || empty($params['email']) || empty($params['phone']) || empty($params['productinfo']) || empty($params['surl']) || empty($params['furl'])) {
                $hashStr = '';
                if (isset($params['additionalCharges'])) {
                    $hashStr .= $params['additionalCharges'] . '|';
                }

                $hashStr .= $this->merchantSalt;
                $hashVarsSeq = explode('|', $hashParams);
                foreach ($hashVarsSeq as $index) {
                    $hashStr .= '|';
                    $hashStr .= isset($params[$index]) ? $params[$index] : '';
                }

                // echo $hashStr; die;
                // $hash = strtolower(hash('sha512', $hashStr));
                if ($params['hash'] == hash('sha512', $hashStr)) {
                    return true;
                }
                return false;
            }
        }

        return false;
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
            'command' => $command,
        ];

        $postQueryString = http_build_query($postParams);
        $url = $this->verifyPaymentUrl;

        return $this->apiRequest($url, $postQueryString, 'Verify Payment');
    }

    public function refundPayment($payuId, $merchantTxn, $amount)
    {
        $command = "cancel_refund_transaction";
        $hashStr = $this->merchantKey . '|' . $command . '|' . $payuId . '|' . $this->merchantSalt;

        $hash = strtolower(hash('sha512', $hashStr));
        $postParams = [
            'key' => $this->merchantKey,
            'hash' => $hash,
            'var1' => $payuId,
            'var2' => $merchantTxn,
            'var3' => $amount,
            'command' => $command,
        ];

        $postQueryString = http_build_query($postParams);
        $url = $this->verifyPaymentUrl;

        return $this->apiRequest($url, $postQueryString, 'Refund');
    }

    public function checkRefundStatusByPayuId($payuId)
    {
        $command = "check_action_status";
        $hashStr = $this->merchantKey . '|' . $command . '|' . $payuId . '|' . $this->merchantSalt;

        $hash = strtolower(hash('sha512', $hashStr));
        $postParams = [
            'key' => $this->merchantKey,
            'hash' => $hash,
            'var1' => $payuId,
            'var2' => 'payuid',
            'command' => $command,
        ];

        $postQueryString = http_build_query($postParams);
        $url = $this->verifyPaymentUrl;

        return $this->apiRequest($url, $postQueryString, 'Refund Check');
    }

    public function checkRefundStatusByRequestId($requestId)
    {
        $command = "check_action_status";
        $hashStr = $this->merchantKey . '|' . $command . '|' . $requestId . '|' . $this->merchantSalt;

        $hash = strtolower(hash('sha512', $hashStr));
        $postParams = [
            'key' => $this->merchantKey,
            'hash' => $hash,
            'var1' => $requestId,
            'command' => $command,
        ];

        $postQueryString = http_build_query($postParams);
        $url = $this->verifyPaymentUrl;

        return $this->apiRequest($url, $postQueryString, 'Refund Check');
    }

    private function apiRequest($url, $postQueryString, $type)
    {
        try {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postQueryString);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $this->response = \yii\helpers\Json::decode(curl_exec($curl), true);
            if (curl_errno($curl)) {
                throw new exceptions\AppException(curl_error($curl));
            }

            curl_close($curl);
        } catch (\Exception$ex) {
            throw new exceptions\AppException("Payu {$type} Payment Error - " . $ex->getMessage());
        }
    }

}