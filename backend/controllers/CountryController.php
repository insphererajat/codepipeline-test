<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\MstCountry;
use common\models\search\MstCountrySearch;
use common\models\caching\ModelCache;

/**
 * Description of CountryController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class CountryController extends AdminController
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
        $searchModel = new MstCountrySearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new MstCountry;
        if (Yii::$app->request->isPost) {
            $model->created_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("Country has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['country/index']));
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
                $this->setSuccessMessage("Country has been updated successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['country/index']));
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
            $this->setSuccessMessage("Country has been deleted successfully.");
            return $this->redirect(\yii\helpers\Url::toRoute(['country/index']));
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    protected function findByModel($guid)
    {
        $model = MstCountry::findByGuid($guid, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);

        if ($model === NULL) {
            throw new \components\exceptions\AppException("Oops! You are trying to access country doesn't exist or deleted.");
        }
        return $model;
    }

}
