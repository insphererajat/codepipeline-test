<?php

namespace components;

use Yii;
use yii\base\Component;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use common\models\MstConfiguration;
use common\models\caching\ModelCache;
use components\Helper;

class AmazonS3 extends Component
{
    public $key;
    public $secret;
    public $bucket;
    public $region;
    public $minutes;
    
    public $s3BucketName;
    private $s3Client;
    public $result;
    public $error;
    
    public function init()
    {
        $configuration = MstConfiguration::findByType(MstConfiguration::TYPE_S3, ['isActive' => ModelCache::IS_ACTIVE_YES]);
        if (empty($configuration)) {
            throw new \yii\base\InvalidConfigException("Invalid SMS configuration.");
        }
        $this->key = Helper::decryptString($configuration['config_val1']);;
        $this->secret = Helper::decryptString($configuration['config_val2']);;
        $this->region = Helper::decryptString($configuration['config_val3']);;
        $this->bucket = Helper::decryptString($configuration['config_val4']);;
        $this->minutes = Helper::decryptString($configuration['config_val5']);;

        // Instantiate the S3 client with your AWS credentials
        $this->s3Client = new S3Client(array(
            'credentials' => array(
                'key' => $this->key,
                'secret' => $this->secret
            ),
            'region' => $this->region,
            'version' => 'latest'
        ));

        return parent::init();
    }
    
    public function getPrivateMediaUrl($mediaPath) {

        $parseUrl = parse_url($mediaPath, PHP_URL_PATH);
        $key = ltrim($parseUrl, "/");

        $url_creator = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        $minutes = $this->minutes;
        $request = $this->s3Client->createPresignedRequest($url_creator, "+$minutes minutes");
        return (string) $request->getUri();
    }
    
    public function getS3DocumentObject($mediaPath)
    {
        $parseUrl = parse_url($mediaPath, PHP_URL_PATH);
        $key = ltrim($parseUrl, "/");

        $s3Object = $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        return $s3Object;
    }

    public function uploadFile($bucketKeyPath, $absoluteSourceFilePath, $acl = 'public-read')
    {
        try {
            
            $fileContent = file_get_contents($absoluteSourceFilePath);
            $this->result = $this->s3Client->putObject(array(
                'Bucket' => $this->bucket,
                'Key'    => $bucketKeyPath,
                'Body'   => $fileContent,
                //'ACL'    => $acl,
                'CacheControl'  => 'max-age=172800',
                'ContentType' => mime_content_type($absoluteSourceFilePath)
            ));
            
            return $this->result;
        } 
        catch (S3Exception $e) {
            $this->error = $e->getMessage();
        }
        
        return false;
    }
    
    public function deleteFile($bucketKeyPath)
    {
        try {
            $this->result = $this->s3Client->deleteObject(array(
                'Bucket' => $this->bucket,
                'Key'    => $bucketKeyPath,
            ));
            
            return true;
        } 
        catch (S3Exception $e) {
            $this->error = $e->getMessage();
        }
        
        return false;
    }
    
    /**
     * 
     * @param type $bucketKeyPathArr [['key' => our Object Key1]['key' => our Object Key1]]
     * @return boolean
     */
    public function deleteMultipleFiles($bucketKeyPathArr)
    {
         try {
            $this->result = $this->s3Client->deleteObjects(array(
                'Bucket' => $this->bucket,
                'Delete' => [
                    'Objects' => $bucketKeyPathArr
                ],
            ));
            
            return true;
        } 
        catch (S3Exception $e) {
            $this->error = $e->getMessage();
        }
        
        return false;
    }
}