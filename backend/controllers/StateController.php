<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\search\location\MstStateSearch;
use common\models\location\MstState;
use common\models\caching\ModelCache;
/**
 * Description of StateController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class StateController extends AdminController
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
        $searchModel = new MstStateSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
       
        $model = new MstState;
        if (Yii::$app->request->isPost) {
            $model->created_by = \Yii::$app->user->id;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('State has been created successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['state/index']));
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
             $model->modified_by = \Yii::$app->user->id;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                return $this->redirect(\yii\helpers\Url::toRoute(['state/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionStatus($guid)
    {
        try {
            $model = MstState::findByGuid($guid, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);

            if ($model === NULL) {
                throw new \components\exceptions\AppException("Invalid State!");
            }

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
        try {
            $model = $this->findModel($guid);
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            $this->setSuccessMessage('State has been deleted successfully.');
            return $this->redirect(\yii\helpers\Url::toRoute(['state/index']));
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    protected function findModel($guid)
    {
        $model = MstState::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access state doesn't exist or deleted.");
        }
        return $model;
    }

}
