<?php

namespace backend\controllers;

use Yii;
use common\models\Subject;
use common\models\caching\ModelCache;
use common\models\Permission;

/**
 * Description of SubjectController
 *
 * @author Azam
 */
class UniversityController extends AdminController
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->hasAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $searchModel = new \common\models\search\MstUniversitySearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {

        $model = new \common\models\MstUniversity();
        $model->setScenario(\common\models\MstUniversity::SCENARIO_CHILD_UNIVERSITY);
        if (Yii::$app->request->isPost) {
            $model->created_by = Yii::$app->user->id;
            $model->modified_by = Yii::$app->user->id;

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('University saved successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['university/index']));
            }
            $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
        }

        return $this->render('create', ['model' => $model]);
    }
    
    public function actionUpdate($guid)
    {
        $model = \common\models\MstUniversity::findByGuid($guid, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);

        if ($model === NULL) {
            throw new \yii\web\HttpException("Invalid Model!");
        }
        $model->setScenario(\common\models\MstUniversity::SCENARIO_CHILD_UNIVERSITY);
        if (Yii::$app->request->isPost) {
            $model->modified_by = Yii::$app->user->id;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('University has been added successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['university/index']));
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($guid)
    {

        $model = \common\models\MstUniversity::findByGuid($guid, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);

        if ($model === NULL) {
            throw new \yii\web\HttpException("Invalid Model!");
        }

        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);

            $this->setSuccessMessage('University deleted successfully.');
            return $this->redirect(\yii\helpers\Url::toRoute(['university/index']));
        }
        catch (\Exception $ex) {
            throw new \yii\web\HttpException($ex->getMessage());
        }
    }

    public function actionStatus($guid)
    {

        try {
            $model = \common\models\MstUniversity::findByGuid($guid, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);

            if ($model === NULL) {
                throw new \yii\web\HttpException("Oops! model not found");
            }

            $status = ($model->is_active == ModelCache::IS_ACTIVE_YES) ? ModelCache::IS_ACTIVE_NO : ModelCache::IS_ACTIVE_YES;
            $model->is_active = $status;
            $model->save(TRUE, ['is_active']);

            return \components\Helper::outputJsonResponse(['success' => 1, 'status' => $status]);
        }
        catch (\Exception $ex) {
            throw new \yii\web\HttpException("Invalid request.");
        }
    }

}
