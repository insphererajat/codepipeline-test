<?php

namespace components\integration\payment\ccavenue;

use Yii;
use common\models\MstConfiguration;
use components\exceptions\AppException;
use common\models\caching\ModelCache;

/**
 * Description of CcAvenue
 *
 * @author Amit Hand
 */
class CcAvenue extends \yii\base\Component
{

    public $merchantId;
    public $accessCode;
    public $workingKey;
    public $paymentMethod;
    public $paymentUrl = "";
    public $verifyPaymentUrl = "";
    public $response;

    public function __construct($config = [])
    {
        $this->initParams($config);
    }

    public function initParams($config)
    {
        if (!isset($config['paymentMethod']) || empty($config['paymentMethod'])) {
            throw new AppException("Invalid Payu Payment Method.");
        }
        
        $mstConfigModel = MstConfiguration::findByType(MstConfiguration::TYPE_CCAVENUE_HDFC, ['isActive' => ModelCache::IS_ACTIVE_YES]);
        if(empty($mstConfigModel)) {
            throw new AppException("Invalid request.");
        }

        $mstConfigModel = MstConfiguration::decryptValues($mstConfigModel);

        $this->paymentMethod = $config['paymentMethod'];
        $this->merchantId = $mstConfigModel['config_val1'];
        $this->accessCode = $mstConfigModel['config_val2'];
        $this->workingKey = $mstConfigModel['config_val3'];
        $this->paymentUrl = $mstConfigModel['config_val4'];
        $this->verifyPaymentUrl = $mstConfigModel['config_val5'];
    }

    /*
     * @param1 : Plain String
     * @param2 : Working key provided by CCAvenue
     * @return : Decrypted String
     */

    public function encrypt($plainText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    /*
     * @param1 : Encrypted String
     * @param2 : Working key provided by CCAvenue
     * @return : Plain String
     */

    public function decrypt($encryptedText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    public function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            }
            else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }

    public function formatResponse($encResp)
    {
        $responseString = $this->decrypt($encResp, $this->workingKey);
        $decryptValues = explode('&', $responseString);
        $list = [];
        foreach ($decryptValues as $key => $decryptValue) {
            $arr = explode('=', $decryptValue);
            $list[$arr[0]] = $arr[1];
        }

        return $list;
    }

    public function verifyPayment($txnId)
    {
        $merchantJsonData = [
            'order_no' => $txnId,
            'page_number' => 1
        ];

        $merchantData = json_encode($merchantJsonData);

        $encryptedData = $this->encrypt($merchantData, $this->workingKey);

        $finalData = "request_type=JSON&access_code=" . $this->accessCode . "&command=orderLookup&version=1.2&response_type=JSON&enc_request=" . $encryptedData;
        
        return $this->apiRequest($this->verifyPaymentUrl, $finalData, 'Verify Payment');
    }
    
    public function refundPayment($gatewayId)
    {
        $refundRefNo = md5(microtime() . rand());
        
        $merchantJsonData = [
            'reference_no' => $gatewayId,
            'refund_amount' => '1.00',
            'refund_ref_no' => $refundRefNo,
        ];

        $merchantData = json_encode($merchantJsonData);

        $encryptedData = $this->encrypt($merchantData, $this->workingKey);

        $finalData = "request_type=JSON&access_code=" . $this->accessCode . "&command=refundOrder&version=1.1&response_type=JSON&enc_request=" . $encryptedData;
        
        return $this->apiRequest($this->verifyPaymentUrl, $finalData, 'Refund Payment');
    }
    
    private function apiRequest($url, $postQueryString, $type)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postQueryString);

            $result = curl_exec($ch);
            
            if (curl_errno($ch)) {
                throw new AppException(curl_error($ch));
            }
            curl_close($ch);

            $information = explode('&', $result);
            $status1 = explode('=', $information[0]);
            $status2 = explode('=', $information[1]);
            
            if ($status1[1] == '1') {
                return $recordData = $status2[1];
            } else {
                $status = $this->decrypt(trim($status2[1]), $this->workingKey);
                $allData = \yii\helpers\Json::decode($status);
                if(!empty($allData['order_Status_List'])){
                    foreach($allData['order_Status_List'] as $finalResult){
                        $this->response = $finalResult;
                        return $finalResult;
                    }
                }else{
                    $this->response = $allData;
                    return $allData;
                }
            }
            
        }
        catch (\Exception $ex) {
            throw new AppException("ccavenue {$type} Payment Error - " . $ex->getMessage());
        }
    }

}