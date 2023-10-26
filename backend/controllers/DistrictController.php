<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\location\MstDistrict;
use common\models\search\location\MstDistrictSearch;
use common\models\caching\ModelCache;

/**
 * Description of DistrictController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class DistrictController extends AdminController
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
        $searchModel = new MstDistrictSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new MstDistrict;
        if (Yii::$app->request->isPost) {
            $model->created_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('District has been created successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['district/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($guid)
    {
        $model = $this->findModel($guid);
        if (Yii::$app->request->isPost) {
            $model->modified_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('District has been updated successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['district/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionStatus($guid)
    {
        $model = $this->findModel($guid);
        try {
            $status = ($model->is_active == ModelCache::IS_ACTIVE_YES) ? ModelCache::IS_ACTIVE_NO : ModelCache::IS_ACTIVE_YES;
            $model->is_active = $status;
            $model->save(TRUE, ['is_active']);

            return \components\Helper::outputJsonResponse(['success' => 1, 'status' => $status]);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    public function actionDelete($guid)
    {
        $model = $this->findModel($guid);

        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = $this->userId;
            $model->save(TRUE, ['is_deleted', 'modified_by']);

            $this->setSuccessMessage('District has been deleted successfully.');
            return $this->redirect(\yii\helpers\Url::toRoute(['district/index']));
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    protected function findModel($guid)
    {
        $model = MstDistrict::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access district doesn't exist or deleted.");
        }
        return $model;
    }

}
