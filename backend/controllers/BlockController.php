<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use backend\models\BlockForm;
use common\models\search\BlockSearch;
use common\models\caching\ModelCache;
use common\models\MstBlock;

/**
 * Description of BlockController
 *
 * @author Ashish
 */
class BlockController extends AdminController
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
        $searchModel = new BlockSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new BlockForm;
        if (Yii::$app->request->isPost) {
            $model->created_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveData()) {
                $this->setSuccessMessage("Block has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['block/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($guid)
    {
        $blockModel = $this->findModel($guid);
        $model = new BlockForm;

        $model->attributes = $blockModel->attributes;

        if (Yii::$app->request->isPost) {
            $model->modified_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveData()) {
                $this->setSuccessMessage("Block has been update successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['block/index']));
            }
            else {
                throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($guid)
    {
        $model = $this->findModel($guid);
        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = $this->userId;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            $this->setSuccessMessage("Block has been deleted successfully.");
            return $this->redirect(\yii\helpers\Url::toRoute(['block/index']));
        }
        catch (Exception $ex) {
            throw new \components\exceptions\AppException("Oops! You trying to delete block doesn't exist or deleted.");
        }
    }

    protected function findModel($guid)
    {
        $model = MstBlock::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access block doesn't exist or deleted.");
        }
        return $model;
    }

}
