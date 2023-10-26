<?php

namespace backend\controllers\common;

/**
 * Description of UploadFileController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class UploadFileController extends \backend\controllers\AdminController
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
            ]
        ];

        return \yii\helpers\ArrayHelper::merge($controllerBehaviors, parent::behaviors());
    }

    public function actionUploadModal()
    {
        return $this->renderPartial('/layouts/partials/_upload-modal.php');
    }

    public function actionUpload()
    {
        $response = [
            'success' => 0, 
            'url' => ''
        ];
        if (\Yii::$app->request->isPost) {

            $file = \yii\web\UploadedFile::getInstanceByName('file');


            $uploadDir = \Yii::$app->params['upload.dir'] . "/" . \Yii::$app->params['upload.dir.tempFolderName'];
            $fileName = time() . '_' . $file->name;
            $filePath = $uploadDir . '/' . $fileName;
            $extension = $file->extension;

            //saving the file to temporary folder
            if ($file->saveAs($filePath)) {

                $options = [
                    'ProcessLocalFile' => true,
                    'RenameFile' => true,
                ];
                $filePath = \Yii::getAlias('@webroot') . \Yii::$app->params['upload.baseHttpPath.relative'] . '/' . \Yii::$app->params['upload.dir.tempFolderName'] . '/' . $fileName;

                $model = new \common\models\Media;
                $awsResponse = $model->processLocalFileAndCreateNewMedia($filePath, $options);

                $response = ['success' => 1, 'extension' => $extension, 'fileName' => $file->name, 'media' => $awsResponse];
            }
            else {
                $response = ['success' => 0, 'error' => 'Unable to saving image or file.'];
            }
        }

        return \components\Helper::outputJsonResponse($response);
    }

}
