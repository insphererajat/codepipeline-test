<?php

namespace components\integration\payment\axis;

use Yii;

/**
 * Description of CcAvenue
 *
 * @auther Amit Handa
 */
class Axis extends \yii\base\Component {

    const M_CBC = 'cbc';
    const M_CFB = 'cfb';
    const M_ECB = 'ecb';
    const M_NOFB = 'nofb';
    const M_OFB = 'ofb';
    const M_STREAM = 'stream';

    protected $key;
    protected $cipher;
    protected $data;
    protected $mode;
    protected $IV;
    public $paymentMethod;
    public $cid;
    public $checksum_key;
    public $encryption_key;
    public $paymentUrl = "";
    public $verifyPaymentUrl = "";
    public $response;
    public $ver = '';
    public $typ = '';
    public $cny = 'INR';
    public $re1 = 'MN';
    public $resSuccess = 000;
    public $resFailed = 111;
    public $resPending = 101;

    public function __construct($config = [])
    {
        $this->initParams($config);
    }

    public function initParams($config, $data = null, $key = null, $blockSize = null, $mode = null)
    {
        $this->cid = Yii::$app->params['axis']['cid'];
        $this->checksum_key = Yii::$app->params['axis']['checksum_key'];
        $this->encryption_key = Yii::$app->params['axis']['encryption_key'];
        $this->paymentUrl = Yii::$app->params['axis']['paymentUrl'];
        $this->verifyPaymentUrl = Yii::$app->params['axis']['verifyPaymentUrl'];
        $this->typ = Yii::$app->params['axis']['typ'];
        $this->ver = Yii::$app->params['axis']['ver'];
        
        $this->setData($data);
        $this->setKey($key);
        $this->setBlockSize($blockSize);
        $this->setMode($mode);
        $this->setIV("");
    }

    /**
     * 
     * @param type $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * 
     * @param type $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * 
     * @param type $blockSize
     */
    public function setBlockSize($blockSize) {
        switch ($blockSize) {
            case 128:
                $this->cipher = "MCRYPT_RIJNDAEL_128";
                break;

            case 192:
                $this->cipher = "MCRYPT_RIJNDAEL_192";
                break;

            case 256:
                $this->cipher = "MCRYPT_RIJNDAEL_256";
                break;
        }
    }

    /**
     * 
     * @param type $mode
     */
    public function setMode($mode) {
        switch ($mode) {
            case self::M_CBC:
                $this->mode = "MCRYPT_MODE_CBC";
                break;
            case self::M_CFB:
                $this->mode = "MCRYPT_MODE_CFB";
                break;
            case self::M_ECB:
                $this->mode = "MCRYPT_MODE_ECB";
                break;
            case self::M_NOFB:
                $this->mode = "MCRYPT_MODE_NOFB";
                break;
            case self::M_OFB:
                $this->mode = "MCRYPT_MODE_OFB";
                break;
            case self::M_STREAM:
                $this->mode = "MCRYPT_MODE_STREAM";
                break;
            default:
                $this->mode = "MCRYPT_MODE_ECB";
                break;
        }
    }

    /**
     * 
     * @return boolean
     */
    public function validateParams() {
        if ($this->data != null &&
                $this->key != null &&
                $this->cipher != null) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function setIV($IV) {
        $this->IV = $IV;
    }

    protected function getIV() {
        if ($this->IV == "") {
            $this->IV = mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
        }
        return $this->IV;
    }

    /**
     * @return type
     * @throws Exception
     */
    public function encrypt($data = null, $key = null, $blockSize = null, $mode = null) {
        if (extension_loaded('openssl')) {
            return $result = base64_encode(openssl_encrypt($data, 'AES-128-ECB', $key, OPENSSL_RAW_DATA));
        } elseif (extension_loaded('mcrypt')) {
            $this->setData($data);
            $this->setKey($key);
            $this->setBlockSize($blockSize);
            $this->setMode($mode);
            $this->setIV('');

            if ($this->validateParams()) {
                return trim(base64_encode(
                                mcrypt_encrypt(
                                        $this->cipher, $this->key, $this->pkcs5_pad($this->data), $this->mode, $this->getIV(''))));
            } else {
                throw new Exception('Invalid params!');
            }
        } else {
            throw new Exception('OpenSSL or Mcrypt Extension is required');
        }
    }

    /**
     * 
     * @return type
     * @throws Exception
     */
    public function decrypt($data = null, $key = null, $blockSize = null, $mode = null) {
        if (extension_loaded('openssl')) {
            return $result = openssl_decrypt(base64_decode($data), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
        } elseif (extension_loaded('mcrypt')) {
            $this->setData(trim($data));
            $this->setKey($key);
            $this->setBlockSize($blockSize);
            $this->setMode($mode);
            $this->setIV("");



            if ($this->validateParams()) {
                $depcryptedStr = trim(mcrypt_decrypt($this->cipher, $this->key, base64_decode($this->data), $this->mode, $this->getIV()));

                $unpaddedStr = $this->pkcs5_unpad(utf8_encode($depcryptedStr));

                if ($unpaddedStr) {
                    return $unpaddedStr;
                } else {
                    return $depcryptedStr;
                }
            } else {
                throw new Exception('Invalid params for decryption!!!');
            }
        } else {
            throw new Exception('OpenSSL or Mcrypt Extension is required');
        }
    }

    function pkcs5_pad($text, $blocksize = 16) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }

        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

}
