<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers\api;

use Yii;
use components\Helper;
use Exception;
use common\models\Applicant;

/**
 * Description of UploadFileController
 *
 * @author Amit Handa
 */
class UploadFileController extends \frontend\controllers\api\ApiController
{
    /**
     * Access Control
     *
     * @return type
     */
    public function behaviors()
    {
        $controllerBehaviors = [
            'ajax' => [
                'class' => \components\filters\AjaxFilter::className(),
                'only' => ['upload-modal', 'upload'],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'user' => 'applicant',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!Applicant::checkSessionHijackingPreventions(Applicant::FRONTEND_LOGIN_KEY, Applicant::FRONTEND_FIXATION_COOKIE, Applicant::FRONTEND_SESSION_VALUE)) {
                                Yii::$app->applicant->logout();
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
        ];

        return \yii\helpers\ArrayHelper::merge($controllerBehaviors, parent::behaviors());
    }
    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionUploadModal()
    {
        $id = Yii::$app->request->post('id');
        $id = Helper::clean($id);
        return $this->renderPartial('upload-modal', ['id' => strip_tags($id)]);
    }

    public function actionUpload()
    {

        $response = ['success' => 0, 'url' => ''];

        if (\Yii::$app->request->isPost) {

            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $uploadDir = \Yii::$app->params['upload.dir'] . "/" . \Yii::$app->params['upload.dir.tempFolderName'];
            $fileName = time() . '_' . $file->name;
            $filePath = $uploadDir . '/' . $fileName;
            $extension = $file->extension;
            
            $mime_type = mime_content_type($file->tempName);
            if (!\yii\helpers\ArrayHelper::isIn($extension, \Yii::$app->params['allowed.extension'])) {
                throw new \components\exceptions\AppException("Invalid file extension.");
            }
            if (!\yii\helpers\ArrayHelper::isIn($mime_type, \Yii::$app->params['allowed.mime'])) {
                throw new \components\exceptions\AppException("Invalid file type.");
            }

            //saving the file to temporary folder
            try {
                if ($file->saveAs($filePath)) {
                    $options = [
                        'RenamePrefix' => isset(Yii::$app->applicant->id) ? Yii::$app->applicant->id : '',
                        'ProcessLocalFile' => true,
                        'RenameFile' => true,
                        'isImage' => \yii\helpers\ArrayHelper::isIn($extension, Yii::$app->params['allowed.extension'])
                    ];
                    $filePath = \Yii::getAlias('@webroot') . \Yii::$app->params['upload.baseHttpPath.relative'] . '/' . \Yii::$app->params['upload.dir.tempFolderName'] . '/' . $fileName;

                    $model = new \common\models\Media;
                    $awsResponse = $model->processLocalFileAndCreateNewMedia($filePath, $options);

                    $response = ['success' => 1, 'extension' => $extension, 'fileName' => $file->name, 'media' => $awsResponse,'cdnPath'=>Yii::$app->amazons3->getPrivateMediaUrl($awsResponse['cdnPath'])];
                }
                else {
                    throw new \components\exceptions\AppException($file->error);
                }
            }
            catch (Exception $ex) {
                $error = $ex->getMessage();
                $response = ['success' => 0, 'error' => $error];
            }
        }

        return \components\Helper::outputJsonResponse($response);
    }
    
    public function actionCropMediaModal($mediaId)
    {
        if (empty($mediaId)) {
            throw new \components\exceptions\AppException("Invalid Request.");
        }

        $mediaModel = \common\models\Media::findById($mediaId);
        if ($mediaModel == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access media doesn't exist or deleted.");
        }

        return $this->renderAjax('crop-image-modal.php', ['media' => $mediaModel]);
    }

    public function actionCropMedia()
    {
        $postParams = Yii::$app->request->post();

        if (empty($postParams['mediaId'])) {
            throw new \components\exceptions\AppException("Invalid Request.");
        }

        $mediaModel = \common\models\Media::findById($postParams['mediaId']);
        if ($mediaModel == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access media doesn't exist or deleted.");
        }

        $x = (int) round((float) $postParams['x'], 0, PHP_ROUND_HALF_DOWN);
        $y = (int) round((float) $postParams['y'], 0, PHP_ROUND_HALF_DOWN);
        $width = (int) round((float) $postParams['width'], 0, PHP_ROUND_HALF_UP);
        $height = (int) round((float) $postParams['height'], 0, PHP_ROUND_HALF_UP);
        $rotate = (int) $postParams['rotate'];

        $x = $x < 0 ? 0 : $x;
        $y = $y < 0 ? 0 : $y;

        $cropOptions = [
            'width' => $width,
            'height' => $height,
            'x' => $x,
            'y' => $y,
            'rotate' => $rotate
        ];

        $newMediaModel = new \common\models\Media;
        $mediaOptions = [
            'croppingOptions' => $cropOptions,
            'oldMediaId' => $mediaModel['id']
        ];

        $response = ['success' => 0];
        $mediaIds = $newMediaModel->processRemoteFileAndCreateNewMedia($mediaModel['cdn_path'], $mediaOptions);
        if (isset($mediaIds['orig']) && !empty($mediaIds['orig'])) {
            $response = ['success' => 1, 'image' => $mediaIds['cdnPath']];
        }

        return \components\Helper::outputJsonResponse($response);
    }

}
