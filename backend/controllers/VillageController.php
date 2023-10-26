<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\search\MstVillageSearch;
use common\models\caching\ModelCache;
use common\models\MstVillage;

class VillageController extends AdminController
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
        $searchModel = new MstVillageSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new MstVillage;
        if (Yii::$app->request->isPost) {
            $model->created_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("Village has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['village/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($guid)
    {
        $model = $this->findByModel($guid);
        if (Yii::$app->request->isPost) {
            $model->modified_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("Village has been updated successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['village/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($guid)
    {
        $model = $this->findByModel($guid);

        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = $this->userId;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            $this->setSuccessMessage("Village has been deleted successfully.");
            return $this->redirect(\yii\helpers\Url::toRoute(['village/index']));
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    protected function findByModel($guid)
    {
        $model = MstVillage::findByGuid($guid, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);

        if ($model === NULL) {
            throw new \components\exceptions\AppException("Oops! You are trying to access village doesn't exist or deleted.");
        }
        return $model;
    }

}
