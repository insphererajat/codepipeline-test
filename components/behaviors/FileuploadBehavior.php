<?php

namespace components\behaviors;

use yii\base\Behavior;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use \Imagine\Image\Point;
use \Imagine\Image\Box;
use components\exceptions\AppException;

/**
 * Description of FileuploadBehavior
 *
 * @author Pawan Kumar
 */
class FileuploadBehavior extends Behavior
{

    const DIRECTORY_LIMITER = 2000;
    const DIR_MODE = 0755;

    public $uploadDir;
    public $uploadResponse;
    public $localRelativePath;
    public $localAbsFilePath;
    public $uniqueFileName;
    public $imageProperties;
    public $isImage;
    public $appendedDir;
    public $dirPath;
    public $s3Result;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->uploadResponse = [];
        $this->isImage = false;

        if (empty($this->uploadDir)) {
            $this->uploadDir = \Yii::$app->params['upload.dir'];
        }
    }
    
    public function processLocalFileAndSaveToS3($localFilePath, $options = array())
    {
        $options["ProcessLocalFile"] = TRUE;
        $options["LocalFilePath"] = $localFilePath;

        return $this->uploadAndSaveToS3(new \yii\web\UploadedFile(), $options);
    }
    
    public function processRemoteFileAndSaveToS3($localFilePath, $options = array())
    {
        $options["ProcessRemoteFile"] = TRUE;
        $options["LocalFilePath"] = $localFilePath;

        return $this->uploadAndSaveToS3(new \yii\web\UploadedFile(), $options);
    }

    /**
     * Function to upload file to local storage and also put it to s3 at the same time
     * 
     * @param \yii\web\UploadedFile $attribute
     */
    public function uploadAndSaveToS3(\yii\web\UploadedFile $attribute, $options = array())
    {
        if ($this->uploadFile($attribute, $options)) {
            foreach ($this->uploadResponse as $imagePrefix => $image) {
                if (isset($options['delete_orig']) && $options['delete_orig'] == 1 && $imagePrefix == 'orig') {
                    continue;
                }
                //uploading the file to S3 Bucket
                $s3Result = $this->uploadFiletoS3($image['localAbsFilePath'], $image['localRelativePath']);
                if ($s3Result !== FALSE) {
                    $image['s3Result'] = $s3Result;
                    $this->uploadResponse[$imagePrefix] = $image;
                }
            }

            return true;
        }

        return false;
    }
    
    //https://hotexamples.com/examples/yii.imagine/Image/getImagine/php-image-getimagine-method-examples.html
    public function uploadFile(\yii\web\UploadedFile $attribute, $options = array())
    {
      
        //lets create the directory structure
        $this->_createDirectoryByLimiter();

        if (isset($options['appendedDir']) && !empty($options['appendedDir'])) {
            $this->appendedDir = $options['appendedDir'];
        }
        if (isset($options['dirPath']) && !empty($options['dirPath'])) {
            $this->dirPath = $options['dirPath'];
        }

        if (!is_dir($this->dirPath)) {
            FileHelper::createDirectory($this->dirPath);
        }
        
        $renamePrefix = '';
        if (isset($options['ProcessLocalFile']) && is_file($options['LocalFilePath'])) {
            $filePathInfo = pathinfo($options['LocalFilePath']);

            $renamePrefix = (isset($options['RenamePrefix'])) ? $options['RenamePrefix'] . '_' : '';
            $uniqueFileName = (isset($options['RenameFile']) && $options['RenameFile'] === FALSE) ? (isset($options['uniqueName'])) ? $options['uniqueName'] : $filePathInfo['basename'] : $this->generateRandomFileName($this->dirPath, $filePathInfo['extension']);
            $origLocalAbsFilePath = $this->dirPath . DIRECTORY_SEPARATOR . $renamePrefix . $uniqueFileName;
            $origFileName = $filePathInfo['basename'];
            
            //lets move the source file
            rename($options['LocalFilePath'], $origLocalAbsFilePath);

            if (isset($options['delete_orig']) && $options['delete_orig'] == 1) {
                @unlink($options['LocalFilePath']);
            }
        }


        //lets check for EXIF rotation
        $this->fixImageExifOrientation($origLocalAbsFilePath);
        
        //local relative path
        $localRelativePath = $this->appendedDir . '/' . $renamePrefix . $uniqueFileName;

        $this->uploadResponse['orig'] = [
            'origFileName' => $origFileName,
            'uniqueFileName' => $uniqueFileName,
            'localRelativePath' => $localRelativePath,
            'localAbsFilePath' => $origLocalAbsFilePath,
            'fileSize' => filesize($origLocalAbsFilePath),
            'uploaded' => TRUE,
            'isImage' => 0,
            'imageProperties' => [],
            's3Result' => NULL
        ];

        //lets go through all the resize array and create thumbnails
        if (is_array($options) && isset($options['resize']) && is_array($options['resize'])) {

            foreach ($options['resize'] as $imagePrefix => $resizeDimensions) {

                $thumbFileName = $imagePrefix . '_' . $uniqueFileName;
                $thumbLocalAbsFilePath = $this->dirPath . DIRECTORY_SEPARATOR . $thumbFileName;
                $thumbLocalRelativePath = $this->appendedDir . '/' . $thumbFileName;

                Image::thumbnail($origLocalAbsFilePath, $resizeDimensions['w'], $resizeDimensions['h'])
                        ->save($thumbLocalAbsFilePath);

                $this->uploadResponse[$imagePrefix] = [
                    'origFileName' => $origFileName,
                    'uniqueFileName' => $thumbFileName,
                    'localRelativePath' => $thumbLocalRelativePath,
                    'localAbsFilePath' => $thumbLocalAbsFilePath,
                    'fileSize' => filesize($thumbLocalAbsFilePath),
                    'uploaded' => TRUE,
                    'isImage' => 0,
                    'imageProperties' => [],
                    's3Result' => NULL
                ];
            }
        }
        
        //lets go through all the resize array and create thumbnails
        if (is_array($options) && isset($options['croppingOptions']) && is_array($options['croppingOptions'])) {
            
            //Croping feature
            $imageProcessObj = Image::getImagine()->open($origLocalAbsFilePath);
            if (!empty($options['croppingOptions']['rotate'])) {
                $imageProcessObj->rotate($options['croppingOptions']['rotate']);
            }

            $point = new Point($options['croppingOptions']['x'], $options['croppingOptions']['y']);
            $box = new Box($options['croppingOptions']['width'], $options['croppingOptions']['height']);

            $imageProcessObj->crop($point, $box);
            $imageProcessObj->save($origLocalAbsFilePath, ['quality' => 80]);
        }
        
        foreach ($this->uploadResponse as $imagePrefix => $image) {
            if (($imageProperties = getimagesize($image['localAbsFilePath']))) {
                $image['isImage'] = 1;
                $image['imageProperties'] = $imageProperties;

                $this->uploadResponse[$imagePrefix] = $image;
            }
        }
        return true;
    }

    /**
     * Function to upload a local file to Amazon S3
     * @param type $localFilePath
     * @param type $s3PathUniqueKey
     * 
     * @throws \Exception
     */
    public function uploadFiletoS3($localFilePath, $s3PathUniqueKey)
    {
        $s3Result = \Yii::$app->amazons3->uploadFile($s3PathUniqueKey, $localFilePath);
        if ($s3Result === false) {
            throw new AppException("Oops! While saving file to remote CDN occur error. error:" . \Yii::$app->amazons3->error);
        }
        return $s3Result;
    }

    public function deleteFileFromS3($s3PathUniqueKey)
    {
        return \Yii::$app->amazons3->deleteFile($s3PathUniqueKey);
    }

    public function deleteLocalFile($relativeFilePath)
    {
        $filePath = $this->uploadDir . '/' . $relativeFilePath;
        @unlink($filePath);
    }

    private function _createDirectoryByLimiter()
    {
        $this->appendedDir = date('Y/M/d');
        $this->dirPath = $this->uploadDir . '/' . $this->appendedDir;
        $this->createDirectory($this->dirPath);
    }

    private function createDirectory($dirPath = "")
    {
        if ($dirPath != '' && !is_dir($dirPath)) {
            if (!FileHelper::createDirectory($dirPath)) {
                \Yii::error('Error creating directory to upload: ' . $dirPath);
                throw new AppException('Error creating directory to upload');
            }
        }
    }

    public function generateRandomFileName($dirPath, $extension, $fileNameLength = 20, $prefix = '')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        while (true)
        {
            $randomString = '';
            for ($i = 0; $i < $fileNameLength; $i++) {
                $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            if (!file_exists($dirPath . DIRECTORY_SEPARATOR . $randomString . $extension)) {
                break;
            }
        }


        return $randomString . "." . $extension;
    }
    
    public function fixImageExifOrientation($imagePath)
    {
        $exifData = (function_exists('exif_read_data') ? @exif_read_data($imagePath) : FALSE);
        if (empty($exifData['Orientation'])) {
            return;
        }

        //http://www.impulseadventure.com/photo/exif-orientation.html
        //fix the Orientation if EXIF data exist
        $rotateImage = 0;
        switch ($exifData['Orientation']) {
            case 8:
                $rotateImage = -90;
                break;
            case 3:
                $rotateImage = 180;
                break;
            case 6:
                $rotateImage = 90;
                break;
        }

        if ($rotateImage == 0) {
            return;
        }

        $imagine = Image::getImagine();
        $imagine->open($imagePath)->rotate($rotateImage)->save($imagePath);
    }

}
