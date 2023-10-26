<?php

namespace backend\controllers;

use Yii;
use common\models\ListType;
use common\models\caching\ModelCache;

/**
 * Description of QualificationController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class QualificationController extends AdminController
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
        $searchModel = new \common\models\search\MstQualificationSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new \backend\models\QualificationForm();
        if (Yii::$app->request->isPost) {
            $model->created_by = Yii::$app->user->id;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('List type has been added successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['qualification/index']));
            }
             
            $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($guid)
    {
        $qualificationModel = \common\models\MstQualification::findByGuid($guid);

        if ($qualificationModel === NULL) {
            throw new \yii\web\HttpException("Sorry, invalid qualification model.");
        }

        $qualificationSubjects = \common\models\MstQualificationSubject::findByQualificationId($qualificationModel['id'], ['selectCols' => ['subject_id'], 'resultCount' => ModelCache::RETURN_ALL]);
        $subjectIds = \yii\helpers\ArrayHelper::getColumn($qualificationSubjects, 'subject_id');

        $model = new \backend\models\QualificationForm();
        $model->subjects = $subjectIds;
        $model->setAttributes($qualificationModel);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('Qualification has been added successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['qualification/index']));
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($guid)
    {
        $model = \common\models\MstQualification::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            throw new \yii\web\HttpException("Sorry, Model not found.");
        }
        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);

            $this->setSuccessMessage('Qualification has been deleted successfully.');
            return $this->redirect(\yii\helpers\Url::toRoute(['qualification/index']));
        }
        catch (\Exception $ex) {
            throw new \yii\web\HttpException($ex->getMessage());
        }
    }

    public function actionStatus($guid)
    {
        try {
            $model = \common\models\MstQualification::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            $status = ($model->is_active == ModelCache::IS_ACTIVE_YES) ? ModelCache::IS_ACTIVE_NO : ModelCache::IS_ACTIVE_YES;
            $model->is_active = $status;
            $model->save(TRUE, ['is_active']);

            return \components\Helper::outputJsonResponse(['success' => 1, 'status' => $status]);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Oops! You are trying to access list type doesn't exist or deleted.");
        }
    }

}
