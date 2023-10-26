<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

/**
 * Description of ClassifiedController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class ClassifiedController extends AdminController
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
        $searchModel = new \common\models\search\MstClassifiedSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new \backend\models\ClassifiedForm;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('Classified has been added successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['classified/index']));
            }
            $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($guid)
    {
        $userModel = $this->findByModel($guid);
        $model = new \backend\models\ClassifiedForm;
        $model->attributes = $userModel->attributes;

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('Classified has been updated successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['classified/index']));
            }
            else {
                $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
            }
        }
        return $this->render('create', ['model' => $model]);
    }
    
    protected function findByModel($guid)
    {
        $model = \common\models\MstClassified::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access classified doesn't exists or deleted.");
        }
        return $model;
    }

}
