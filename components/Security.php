<?php

namespace components;

use Yii;
use yii\base\Component;

class Security extends Component 
{
    const CIPHER = 'AES-128-CBC';
    const INIT_VECTOR_LENGTH = 16;
    /**
     * Encoded/Decoded data
     *
     * @var null|string
     */
    protected $data;
    /**
     * Initialization vector value
     *
     * @var string
     */
    protected $initVector;
    /**
     * Error message if operation failed
     *
     * @var null|string
     */
    protected $errorMessage;
    /**
     * AesCipher constructor.
     *
     * @param string $initVector        Initialization vector value
     * @param string|null $data         Encoded/Decoded data
     * @param string|null $errorMessage Error message if operation failed
     */
    public function __construct($initVector, $data = null, $errorMessage = null)
    {
        $this->initVector = $initVector;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }
    /**
     * Encrypt input text by AES-128-CBC algorithm
     *
     * @param string $secretKey 16/24/32 -characters secret password
     * @param string $plainText Text for encryption
     *
     * @return self Self object instance with data or error message
     */
    public static function encrypt($secretKey, $plainText)
    {
        try {
            // Check secret length
            if (!static::isKeyLengthValid($secretKey)) {
                throw new \InvalidArgumentException("Secret key's length must be 128, 192 or 256 bits");
            }
            // Get random initialization vector
            $initVector = bin2hex(openssl_random_pseudo_bytes(static::INIT_VECTOR_LENGTH / 2));
            // Encrypt input text
            $raw = openssl_encrypt(
                $plainText,
                static::CIPHER,
                $secretKey,
                OPENSSL_RAW_DATA,
                $initVector
            );
            // Return base64-encoded string: initVector + encrypted result
            $result = base64_encode($initVector . $raw);
            if ($result === false) {
                // Operation failed
                return new static($initVector, null, openssl_error_string());
            }
            // Return successful encoded object
            return new static($initVector, $result);
        } catch (\Exception $e) {
            // Operation failed
            return new static(isset($initVector), null, $e->getMessage());
        }
    }
    /**
     * Decrypt encoded text by AES-128-CBC algorithm
     *
     * @param string $secretKey  16/24/32 -characters secret password
     * @param string $cipherText Encrypted text
     *
     * @return self Self object instance with data or error message
     */
    public static function decrypt($secretKey, $cipherText)
    {
        try {
            // Check secret length
            if (!static::isKeyLengthValid($secretKey)) {
                throw new \InvalidArgumentException("Secret key's length must be 128, 192 or 256 bits");
            }
            // Get raw encoded data
            $encoded = base64_decode($cipherText);
            // Slice initialization vector
            $initVector = substr($encoded, 0, static::INIT_VECTOR_LENGTH);
            // Slice encoded data
            $data = substr($encoded, static::INIT_VECTOR_LENGTH);
            // Trying to get decrypted text
            $decoded = openssl_decrypt(
                $data,
                static::CIPHER,
                $secretKey,
                OPENSSL_RAW_DATA,
                $initVector
            );
            if ($decoded === false) {
                // Operation failed
                return new static(isset($initVector), null, openssl_error_string());
            }
            // Return successful decoded object
            return new static($initVector, $decoded);
        } catch (\Exception $e) {
            // Operation failed
            return new static(isset($initVector), null, $e->getMessage());
        }
    }
    /**
     * Check that secret password length is valid
     *
     * @param string $secretKey 16/24/32 -characters secret password
     *
     * @return bool
     */
    public static function isKeyLengthValid($secretKey)
    {
        $length = strlen($secretKey);
        return $length == 16 || $length == 24 || $length == 32;
    }
    /**
     * Get encoded/decoded data
     *
     * @return string|null
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * Get initialization vector value
     *
     * @return string|null
     */
    public function getInitVector()
    {
        return $this->initVector;
    }
    /**
     * Get error message
     *
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    /**
     * Check that operation failed
     *
     * @return bool
     */
    public function hasError()
    {
        return $this->errorMessage !== null;
    }
    /**
     * To string return resulting data
     *
     * @return null|string
     */
    public function __toString()
    {
        return $this->getData();
    }
    
    public static function cryptoAesDecrypt($ciphertext, $password) 
    {
        $ciphertext = base64_decode($ciphertext);
        if (substr($ciphertext, 0, 8) != "Salted__") {
            return false;
        }
        $salt = substr($ciphertext, 8, 8);
        $keyAndIV = self::evpKDF($password, $salt);
        $decryptPassword = openssl_decrypt(
                substr($ciphertext, 16), 
                "aes-256-cbc",
                $keyAndIV["key"], 
                OPENSSL_RAW_DATA, // base64 was already decoded
                $keyAndIV["iv"]);

        return $decryptPassword;
    }
    
    public static function evpKDF($password, $salt, $keySize = 8, $ivSize = 4, $iterations = 1, $hashAlgorithm = "md5") 
    {
        $targetKeySize = $keySize + $ivSize;
        $derivedBytes = "";
        $numberOfDerivedWords = 0;
        $block = NULL;
        $hasher = hash_init($hashAlgorithm);
        while ($numberOfDerivedWords < $targetKeySize) {
            if ($block != NULL) {
                hash_update($hasher, $block);
            }
            hash_update($hasher, $password);
            hash_update($hasher, $salt);
            $block = hash_final($hasher, TRUE);
            $hasher = hash_init($hashAlgorithm);

            // Iterations
            for ($i = 1; $i < $iterations; $i++) {
                hash_update($hasher, $block);
                $block = hash_final($hasher, TRUE);
                $hasher = hash_init($hashAlgorithm);
            }

            $derivedBytes .= substr($block, 0, min(strlen($block), ($targetKeySize - $numberOfDerivedWords) * 4));

            $numberOfDerivedWords += strlen($block)/4;
        }

        return array(
            "key" => substr($derivedBytes, 0, $keySize * 4),
            "iv"  => substr($derivedBytes, $keySize * 4, $ivSize * 4)
        );
    }

}
