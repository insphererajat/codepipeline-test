<?php

namespace components\integration\payment\cscwallet;

use Yii;
use yii\base\Component;
use components\Helper;
use common\models\MstConfiguration;
use common\models\caching\ModelCache;

class CscWallet extends Component
{

    private $cscObj;
    private $defaultParameters;
    private $merchantKey = '';
    private $publicKey = '';
    private $privateKey = '';
    public $clientId;
    public $clientSecret;
    public $clientToken;
    public $cscConnectRedirectUrl;
    public $redirectUrl;
    public $paymentMethod;
    public $surlFee;
    public $furlFee;
    public $productId;
    public $authorizationEndpointUrl = "https://connectuat.csccloud.in/account/authorize"; //"https://connect.csc.gov.in/account/authorize";
    public $tokenEndpointUrl = "https://connectuat.csccloud.in/account/token";
    public $resourceUrl = "https://connectuat.csccloud.in/account/resource";

    public function __construct($config = [])
    {
        if (!isset($config['paymentMethod']) || empty($config['paymentMethod'])) {
            throw new exceptions\AppException("Invalid Payu Payment Method.");
        }
        
        $mstConfigModel = MstConfiguration::findByType(MstConfiguration::TYPE_CSC, ['isActive' => ModelCache::IS_ACTIVE_YES]);
        if(empty($mstConfigModel)) {
            throw new AppException("Invalid request.");
        }
        $mstConfigModel = MstConfiguration::decryptValues($mstConfigModel);
        $this->cscObj = new CscBridge([
            'publicKey' => $mstConfigModel['config_val9'],
            'privateKey' => $mstConfigModel['config_val10']
        ]);

        $this->paymentMethod = $config['paymentMethod'];
        $this->clientId = $mstConfigModel['config_val1'];
        $this->clientSecret = $mstConfigModel['config_val2'];
        $this->clientToken = $mstConfigModel['config_val3'];
        $this->productId = $mstConfigModel['config_val4'];
        $this->cscConnectRedirectUrl = $mstConfigModel['config_val5'];
        $this->surlFee = $mstConfigModel['config_val6'];
        $this->furlFee = $mstConfigModel['config_val7'];
        $this->merchantKey = $mstConfigModel['config_val8'];
        $this->publicKey = $mstConfigModel['config_val9'];
        $this->privateKey = $mstConfigModel['config_val10'];
        $this->authorizationEndpointUrl = $mstConfigModel['config_val11'];
        $this->tokenEndpointUrl = $mstConfigModel['config_val12'];
        $this->resourceUrl = $mstConfigModel['config_val13'];
        $this->walletUrl = $mstConfigModel['config_val14'];

        $this->defaultParameters = $this->initParams();
    }

    public function initParams()
    {
        return [
            'merchant_id' => $this->merchantKey,
            'merchant_txn' => 'P121' . time() . rand(10, 99),
            'merchant_txn_date_time' => \components\Helper::convertNetworkTimeZone(time(), 'Y-m-d H:i:s', Yii::$app->timeZone, 'Asia/Kolkata'), //'2016-06-21 18:11:58',
            'product_id' => $this->productId,
            'product_name' => 'UKSSSC',
            'txn_amount' => '1150',
            'amount_parameter' => 'NA',
            'txn_mode' => 'D',
            'txn_type' => 'D',
            'merchant_receipt_no' => \components\Helper::convertNetworkTimeZone(time(), 'Y-m-d H:i:s', Yii::$app->timeZone, 'Asia/Kolkata'),
            'csc_share_amount' => '0',
            'pay_to_email' => 'insphere.nitish@gmail.com',
            'return_url' => '',
            'cancel_url' => '',
            'Currency' => 'INR',
            'Discount' => '0',
            'param_1' => 'NA',
            'param_2' => 'NA',
            'param_3' => 'NA',
            'param_4' => 'NA'
        ];
    }

    public function set_params($params)
    {
        foreach ($params as $p => $v) {
            $this->defaultParameters[$p] = $v;
        }
    }

    public function get_parameter_string()
    {
        $message_text = '';
        foreach ($this->defaultParameters as $p => $v) {
            $message_text .= $p . '=' . $v . '|';
        }
        $message_cipher = $this->cscObj->encrypt_message_for_wallet($message_text, FALSE);
        return $this->defaultParameters['merchant_id'] . '|' . $message_cipher;
    }

    public function get_bridge_message()
    {
        $d = "Invalid Bridge message";
        if ($_POST['bridgeResponseMessage']) {
            $c = @$this->cscObj->decrypt_wallet_message($_POST['bridgeResponseMessage'], $d, FALSE);
            if (!$c)
                return $_POST['bridgeResponseMessage'];
        }
        return $d;
    }

    public function get_fraction($ddhhmm = "")
    {
        $time_format = "ymdHis";
        $algo_num = "883";
        if (!$ddhhmm)
            $ddhhmm = date($time_format, time());
        $frac = $this->large_op1($ddhhmm, $algo_num);
        $frac = $this->large_op2($frac, "" . (1000 - $algo_num));
        return $frac;
    }

    public function large_op1($n0, $x0)
    {
        $n = '' . $n0;
        $x = '' . $x0;
        $sz = strlen('' . $n);
        $vals = array();
        $tens = 0;
        for ($i = 0; $i < $sz; $i++) {
            $d = $n[$sz - $i - 1];
            $res = $d * $x + $tens;
            $ones = $res % 10;
            $tens = (int) ($res / 10);
            array_unshift($vals, $ones);
        }
        if ($tens > 0)
            array_unshift($vals, $tens);
        return implode("", $vals);
    }

    public function large_op2($n0, $x0)
    {
        $n = '' . $n0;
        $x = '' . $x0;
        $sz = strlen('' . $n);
        $vals = array();
        $tens = 0;
        for ($i = 0; $i < $sz; $i++) {
            $d = $n[$sz - $i - 1];
            if ($i == 0)
                $res = $d + $x;
            else
                $res = $d + $tens;
            $ones = $res % 10;
            $tens = (int) ($res / 10);
            array_unshift($vals, $ones);
        }
        if ($tens > 0)
            array_unshift($vals, $tens);
        return implode("", $vals);
    }

    //API CALLS
    public function set_mid($mid)
    {
        $this->merchant_id = $mid;
    }

    public function get_enquiry($tid)
    {
        if (!isset($this->merchant_id)) {
            throw new \components\exceptions\AppException("Merchant ID not set. Please call set_mid first.");
        }
        $data = [
            'merchant_txn' => $tid
        ];
        $result = $this->_call_cscwallet_api('transaction/enquiry', $data);
        echo json_encode($result);
        return 1;
    }

    public function get_status($tid)
    {
        if (!isset($this->merchantKey)) {
            throw new \components\exceptions\AppException("Merchant ID not set. Please call set_mid first.");
        }
        $data = [
            'merchant_id' => $this->merchantKey,
            'merchant_txn' => $tid,
            'csc_txn' => 'N'
        ];
        
        return $this->_call_cscwallet_api('transaction/status', $data);
    }

    public function get_reverse($tid, $merchant_txn_datetime)
    {
        if (!isset($this->merchantKey)) {
            throw new \components\exceptions\AppException("Merchant ID not set. Please call set_mid first.");
        }
        $data = [
            'merchant_txn' => $tid,
            'merchant_txn_datetime' => $merchant_txn_datetime
        ];
        $result = $this->_call_cscwallet_api('transaction/reverse', $data);

        echo json_encode($result);
        return 1;
    }

    public function refund_log($params)
    {
        if (!isset($this->merchantKey)) {
            throw new \components\exceptions\AppException("Merchant ID not set. Please call set_mid first.");
        }
        $data = [
            'csc_txn' => $params['csc_txn'],
            'merchant_txn' => $params['merchant_txn'],
            'merchant_txn_param' => 'N',
            'merchant_txn_status' => 'S',
            'merchant_reference' => rand(0, 999999),
            'refund_deduction' => $params['amount'],
            'refund_mode' => 'F',
            'refund_type' => 'R',
            'refund_trigger' => 'M',
            'refund_reason' => 'unable to deliver service',
        ];
        
        return $this->_call_cscwallet_api('refund/log', $data);
    }

    public function refund_status($tid, $csc_txn, $refund_reference)
    {
        if (!isset($this->merchantKey)) {
            throw new \components\exceptions\AppException("Merchant ID not set. Please call set_mid first.");
        }
        $data = array(
            'merchant_txn' => $tid,
            'csc_txn' => $csc_txn,
            'refund_reference' => $refund_reference
        );
        
        return $this->_call_cscwallet_api('refund/status', $data);
    }

    public function recon_log($params)
    {
        if (!isset($this->merchantKey)) {
            throw new \components\exceptions\AppException("Merchant ID not set. Please call set_mid first.");
        }
        
        return $this->_call_cscwallet_api('recon/log', $params);
    }

    private function _call_cscwallet_api($method, $data)
    {
        $data = array_merge(['merchant_id' => $this->merchantKey], $data);
        $message_text = '';
        foreach ($data as $p => $v) {
            $message_text .= $p . '=' . $v . '|';
        }
        $message_cipher = $this->cscObj->encrypt_message_for_wallet($message_text, FALSE);
        $json_data_array = array(
            'merchant_id' => $this->merchantKey,
            'request_data' => $message_cipher
        );
        $json_data = json_encode($json_data_array);
        $url = 'https://bridge.csccloud.in/v2/' . trim($method, '/');
        return $this->_do_curl_req($url, $json_data, false);
    }

    private function _do_curl_req($url, $post, $headers = false)
    {
        if (!$headers)
            $headers = array('Content-Type: application/json');

        if (isset($this->junk)) {
            $url = $this->junk;
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_VERBOSE => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLINFO_HEADER_OUT => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
//			CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post
        ]);
        $result = curl_exec($curl);
        if (!$result) {
            $httpcode = curl_getinfo($curl);
            print_r(array('Error code' => $httpcode, 'URL' => $url, 'post' => $post, 'LOG' => ""));
            exit("Error: 378972");
        }
        curl_close($curl);
        return $this->_parse_api_resp_to_array($result);
        //return $this->_parse_serialized_api_resp_to_array($result);
    }

    private function _parse_api_resp_to_array($serv_resp)
    {
        $xml_response = simplexml_load_string($serv_resp);
        if ($xml_response->response_data == "NA") {
            return $xml_response;
        }
        $p = $this->decrypt($xml_response->response_data);
        $p = explode('|', $p);
        $fine_params = [];
        foreach ($p as $param) {
            if (empty($param)) {
                continue;
            }

            $param = explode('=', $param);
            if (isset($param[0])) {
                $fine_params[$param[0]] = !empty($param[1]) ? $param[1] : $param[0];
            }
        }
        return $fine_params;
    }

    private function _parse_serialized_api_resp_to_array($serv_resp)
    {
        if (!$serv_resp)
            return null;
        $vals = (array) unserialize($serv_resp);
        $ret = array();
        if (TRUE || count($vals) > 0) {
            foreach ($vals as $k => $v) {
                if ($k) {
                    if ($k == "response_data") {
                        if (trim($v)) {
                            $_POST['bridgeResponseMessage'] = $v;
                            $v = $this->get_bridge_message();
                        }
                    }
                    $ret[trim($k)] = trim($v);
                }
            }
        }
        return $ret;
    }

    public function decrypt($bridgeResponseMessage)
    {
        $d = "Invalid Bridge message";
        if ($bridgeResponseMessage) {
            $c = @$this->cscObj->decrypt_wallet_message($bridgeResponseMessage, $d, FALSE);
            if (!$c)
                return $bridgeResponseMessage;
        }
        return $d;
    }

    public function encrypt($message_text)
    {
        return $this->cscObj->encrypt_message_for_wallet($message_text, FALSE);
    }

    /**
     * 
     * @param type $url
     * @param type $post
     * @param type $heads
     * @return type
     */
    public function fetch_data($url, $post, $heads)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)');
        curl_setopt($curl, CURLOPT_POST, 1);

        if ($post && is_array($post) && count($post) > 0) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }

        if ($heads && is_array($heads) && count($heads) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $heads);
        }

        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new exceptions\AppException(curl_error($curl));
        }
        curl_close($curl);


        return $result;
    }

    public function connectEncrypt($in_t)
    {
        $key = $this->clientToken;
        $pre = ":";
        $post = "@";
        $plaintext = rand(10, 99) . $pre . $in_t . $post . rand(10, 99);
        $iv = "0000000000000000";
        $pval = 16 - (strlen($plaintext) % 16);
        $ptext = $plaintext . str_repeat(chr($pval), $pval);
        $dec = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $ptext, MCRYPT_MODE_CBC, $iv);
        return bin2hex($dec);
    }

    public function createTransactionId()
    {
        // Generate random transaction id
        return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    }
    
    function statusEncrypt($plaintext, $key )
    {
        $iv = "0000000000000000";
        $method = 'AES-256-CBC'; // AES-128-CBC
        $encrypted = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
        $encrypted1 = base64_encode($encrypted);
        return $encrypted1;
    }
    
    function statusDecrypt($ciphertext, $key) 
    {
        $iv = "0000000000000000";
        $method = 'AES-256-CBC'; // AES-128-CBC
        $dec = base64_decode($ciphertext);
        $plaintext = openssl_decrypt($dec, $method, $key, OPENSSL_RAW_DATA, $iv);
        
        return $this->unpad($plaintext);
    }
    
    private function unpad($dec) 
    {
        $last = substr($dec, strlen($dec) - 1, 1);
        $pad_n = ord($last);
        if ($pad_n > 0 && $pad_n <= 16) {
            //Unpad this number
            $index = strlen($dec) - $pad_n;
            $new_str = substr($dec, 0, $index);
            return $new_str;
        }
        return $dec;
    }

}
