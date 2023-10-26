<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
// use components\behaviors\FileuploadBehavior;
use common\models\base\Media as BaseMedia;

/**
 * Description of Media
 *
 * @author Amit Handa
 */
class Media extends BaseMedia
{

    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_ATTACHMENT = 'attachment';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            \components\behaviors\GuidBehavior::className(),
            \components\behaviors\FileuploadBehavior::className(),
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['filename', 'filepath', 'filetype', 'caption', 'cdn_path']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'filename' => 'cleanEncodeUTF8',
                        'filepath' => 'cleanEncodeUTF8',
                        'filetype' => 'cleanEncodeUTF8',
                        'caption' => 'cleanEncodeUTF8',
                        'cdn_path' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'filename' => 'cleanEncodeUTF8',
                        'filepath' => 'cleanEncodeUTF8',
                        'filetype' => 'cleanEncodeUTF8',
                        'caption' => 'cleanEncodeUTF8',
                        'cdn_path' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }


        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public function afterDelete()
    {
        if ($this->cdn_uploaded) {
            $this->deleteFileFromS3($this->filepath);
        }
        return parent::afterDelete();
    }

    public function processLocalFileAndCreateNewMedia($localFilePath, $options = [])
    {
        $options["ProcessLocalFile"] = TRUE;
        $options["LocalFilePath"] = $localFilePath;
        return $this->uploadAndCreateNewMedia(new \yii\web\UploadedFile(), $options);
    }

    public function processRemoteFileAndCreateNewMedia($localFilePath, $options = [])
    {
        $options["ProcessRemoteFile"] = TRUE;
        $options["LocalFilePath"] = $localFilePath;
        return $this->uploadAndCreateNewMedia(new \yii\web\UploadedFile(), $options);
    }

    public function uploadAndCreateNewMedia(\yii\web\UploadedFile $file, $options = array())
    {
        try {
            $mediaIds = [];
            $uploadToS3 = Yii::$app->params['upload.uploadToS3'];
            if ($uploadToS3) {
                if (isset($options["ProcessLocalFile"]) && $options["ProcessLocalFile"]) {
                    $this->processLocalFileAndSaveToS3($options["LocalFilePath"], $options);
                }
                else if (isset($options["ProcessRemoteFile"]) && $options["ProcessRemoteFile"]) {
                    $this->processRemoteFileAndSaveToS3($options["LocalFilePath"], $options);
                }
                else {
                    $this->uploadAndSaveToS3($file, $options);
                }
            }
            else {
               
                if (isset($options["ProcessLocalFile"]) && $options["ProcessLocalFile"]) {
                    $this->uploadFile(new \yii\web\UploadedFile, $options);
                }
                if (isset($options["ProcessRemoteFile"]) && $options["ProcessRemoteFile"]) {
                    $this->processLocalFileAndSaveToS3($options["LocalFilePath"], $options);
                }
                /* else {
                  $this->uploadFile($file, $options);
                  } */
            }
            foreach ($this->uploadResponse as $imagePrefix => $image) {
                
                if (isset($options['mediaModel']) && $options['mediaModel'] instanceof Media) {
                    $mediaClass = get_class($options['mediaModel']);
                    $mediaModel = new $mediaClass();
                }
                elseif (isset($options['oldMediaId']) && $options['oldMediaId'] > 0) {
                    $mediaModel = Media::findOne($options['oldMediaId']);
                    if ($mediaModel == null) {
                        $mediaModel = new Media;
                        $mediaModel->isNewRecord = TRUE;
                    }
                }
                else {
                    $mediaModel = new Media;
                    $mediaModel->isNewRecord = TRUE;
                }

                $mediaModel->filename = $image['origFileName'];
                $mediaModel->filesize = $image['fileSize'];
                $mediaModel->filetype = is_array($image['imageProperties']) && count($image['imageProperties']) ? image_type_to_mime_type($image['imageProperties'][2]) : $file->type;
                if (Yii::$app->params['upload.uploadToS3']) {
                    $mediaModel->cdn_path = $image['s3Result']['ObjectURL'];
                    $mediaModel->cdn_uploaded = 1;
                }
                else {
                    $mediaModel->cdn_path = \Yii::$app->params['upload.baseHttpPath'] . '/' . $image['localRelativePath'];
                    $mediaModel->cdn_uploaded = 0;
                }

                $mediaModel->media_type = self::TYPE_ATTACHMENT;
                $mediaModel->filepath = $image['localRelativePath'];
                if ($image['isImage']) {
                    $mediaModel->media_type = self::TYPE_IMAGE;
                    $mediaModel->width = $image['imageProperties'][0]; // width
                    $mediaModel->height = $image['imageProperties'][1]; // height
                }
                
                if (!$mediaModel->save()) {
                    throw new \Exception('Error creating Media record.');
                }

                $mediaIds[$imagePrefix] = $mediaModel->id;
                $mediaIds['uniqueName'] = $image['uniqueFileName'];
                $mediaIds['cdnPath'] = $mediaModel->cdn_path;
                $mediaIds['guid'] = $mediaModel->guid;
            }
            // lets delete the source files if we are uploading it to CDN
            if (\Yii::$app->params['upload.deletelocalfile.afterUploadToS3'] && Yii::$app->params['upload.uploadToS3']) {
                foreach ($this->uploadResponse as $imagePrefix => $image) {
                    if (isset($image['s3Result']['ObjectURL']) && !empty($image['s3Result']['ObjectURL']) && is_file($image['localAbsFilePath'])) {
                        gc_collect_cycles();
                        $this->deleteLocalFile($image['localRelativePath']);
                    }
                }
            }
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $mediaIds;
    }

    public static function saveOnS3($fileName, $extension)
    {
        $options = [
            'ProcessLocalFile' => true,
            'RenameFile' => true
        ];
        $filePath = \Yii::getAlias('@webroot') . \Yii::$app->params['upload.baseHttpPath.relative'] . '/' . \Yii::$app->params['upload.dir.tempFolderName'] . '/' . $fileName;

        $model = new \common\models\Media;
        $awsResponse = $model->processLocalFileAndCreateNewMedia($filePath, $options);
        return $response = ['success' => 1, 'extension' => $extension, 'fileName' => $fileName, 'media' => $awsResponse];
    }

    public static function downloadRemoteMedia($mediaModel)
    {
        try {

            $uploadDir = \Yii::$app->params['upload.dir'] . "/downloads";
            $imgAbsoluteLocalPath = $uploadDir . "/" . $mediaModel['filename'];

            //Download File Locally
            \components\Helper::DownloadRemoteFile($mediaModel['cdn_path'], $imgAbsoluteLocalPath);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
        return true;
    }

    public static function getMediaRelatedPath($mediaName)
    {
        return \Yii::getAlias('@webroot') . \Yii::$app->params['upload.baseHttpPath.relative'] . '/' . \Yii::$app->params['upload.dir.tempFolderName'] . '/' . $mediaName;
    }

    public static function getMediaUploadDirectory()
    {
        return \Yii::$app->params['upload.dir'] . "/" . \Yii::$app->params['upload.dir.tempFolderName'];
    }
    
    public static function getEmbededCode($cdnpath)
    {
        if(!$cdnpath){
            return false;
        }
        
        $filePathInfo = pathinfo($cdnpath);
        $localAbsFilePath = \Yii::$app->params['upload.dir'] . "/" . \Yii::$app->params['upload.dir.tempFolderName'] . DIRECTORY_SEPARATOR . $filePathInfo['basename'];
        $s3object = Yii::$app->amazons3->getS3DocumentObject($cdnpath, true);
        file_put_contents($localAbsFilePath, $s3object['Body']->getContents());
        
        $type = pathinfo($localAbsFilePath, PATHINFO_EXTENSION);
        $data = file_get_contents($localAbsFilePath);
        @unlink($localAbsFilePath);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    public static function getDocMediaUrl($media, $options = [])
    {
        $uploadToS3 = Yii::$app->params['upload.uploadToS3'];
        
        $cdn = '';
        if (is_object($media)) {
            $ext = pathinfo(parse_url($media->cdn_path)['path'], PATHINFO_EXTENSION);
            if (isset($uploadToS3) && $uploadToS3) {
                $cdn = Yii::$app->amazons3->getPrivateMediaUrl($media->cdn_path);
            } else {
                $cdn = $media->cdn_path;
            }
        } else {
            $ext = pathinfo(parse_url($media['cdn_path'])['path'], PATHINFO_EXTENSION);
            if (isset($uploadToS3) && $uploadToS3) {
                $cdn = Yii::$app->amazons3->getPrivateMediaUrl($media['cdn_path']);
            } else {
                $cdn = $media['cdn_path'];
            }
        }
       
        $thumbFile = "preview";
        switch(strtolower($ext)) {
            case "pdf":
                $thumbFile = "pdf";
                break;
            case "doc":
            case "docx":    
            case "odt":
                $thumbFile = "doc";
                break;
            case "csv":
                $thumbFile = "csv";
                break;
            case "ppt":
            case "pptx":
            case "odp":
                $thumbFile = "ppt";
                break;
            case "xls":
            case "xlsx":
            case "ods":
                $thumbFile = "xls";
                break;
            case "html":
            case "htm":
                $thumbFile = "html";
                break;
            case "txt":
                $thumbFile = "txt";
                break;
            default:
                return $cdn;
        }

        $staticPath = Yii::$app->params['staticHttpPath'];
        if(isset(Yii::$app->params['isAdmin']) && Yii::$app->params['isAdmin']) {
            $staticPath = Yii::$app->params['staticHttpPath']."/".Yii::$app->network->getThemeFolder();
        }
        
        return $staticPath . "/dist/images/icons/$thumbFile.svg";
    }

    /**
     * Validate delete by same user who created
     */
    public static function validateDelete($prefix, $cdnpath)
    {
        $filePathInfo = pathinfo($cdnpath);
        $explode = explode('_', $filePathInfo['basename']);
        if(count($explode) > 1) {
            if($explode[0] != $prefix)
            {
                return false;
            }
        }
        return true;
    }
}