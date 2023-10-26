<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\MstConfiguration;
use common\models\search\MstConfigurationSearch;
use common\models\caching\ModelCache;
use components\exceptions\AppException;
use components\Helper;
use backend\models\MstConfigurationForm;

/**
 * Description of ThirdPartyController
 *
 * @author Azam
 */
class ThirdPartyController extends AdminController
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
        $searchModel = new MstConfigurationSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new MstConfigurationForm;
        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());

            $model = $this->encryptValues($model);

            if ($model->validate() && $model->save()) {
                $this->setSuccessMessage('Data saved successfully.');
                return $this->redirect('index');
            }
            $this->setErrorMessage(Helper::convertModelErrorsToString($model->getErrors()));
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($guid)
    {
        $model = new MstConfigurationForm();
        $mstConfigration = $model->getData($guid);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model = $this->encryptValues($model);
            if ($model->validate() && $model->save()) {
                $this->setSuccessMessage('Data saved successfully.');
                return $this->redirect('index');
            }
            $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->getErrors()));
        }

        $model = $this->decryptValues($model);
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    private function encryptValues($model)
    {
        $model->config_val1 = !empty($model->config_val1) ? Helper::encryptString($model->config_val1) : '';
        $model->config_val2 = !empty($model->config_val2) ? Helper::encryptString($model->config_val2) : '';
        $model->config_val3 = !empty($model->config_val3) ? Helper::encryptString($model->config_val3) : '';
        $model->config_val4 = !empty($model->config_val4) ? Helper::encryptString($model->config_val4) : '';
        $model->config_val5 = !empty($model->config_val5) ? Helper::encryptString($model->config_val5) : '';
        $model->config_val6 = !empty($model->config_val6) ? Helper::encryptString($model->config_val6) : '';
        $model->config_val7 = !empty($model->config_val7) ? Helper::encryptString($model->config_val7) : '';
        $model->config_val8 = !empty($model->config_val8) ? Helper::encryptString($model->config_val8) : '';
        $model->config_val9 = !empty($model->config_val9) ? Helper::encryptString($model->config_val9) : '';
        $model->config_val10 = !empty($model->config_val10) ? Helper::encryptString($model->config_val10) : '';
        $model->config_val11 = !empty($model->config_val11) ? Helper::encryptString($model->config_val11) : '';
        $model->config_val12 = !empty($model->config_val12) ? Helper::encryptString($model->config_val12) : '';
        $model->config_val13 = !empty($model->config_val13) ? Helper::encryptString($model->config_val13) : '';
        $model->config_val14 = !empty($model->config_val14) ? Helper::encryptString($model->config_val14) : '';
        $model->config_val15 = !empty($model->config_val15) ? Helper::encryptString($model->config_val15) : '';        

        return $model;
    }

    private function decryptValues($model)
    {
        $model->config_val1 = !empty($model->config_val1) ? Helper::decryptString($model->config_val1) : '';
        $model->config_val2 = !empty($model->config_val2) ? Helper::decryptString($model->config_val2) : '';
        $model->config_val3 = !empty($model->config_val3) ? Helper::decryptString($model->config_val3) : '';
        $model->config_val4 = !empty($model->config_val4) ? Helper::decryptString($model->config_val4) : '';
        $model->config_val5 = !empty($model->config_val5) ? Helper::decryptString($model->config_val5) : '';
        $model->config_val6 = !empty($model->config_val6) ? Helper::decryptString($model->config_val6) : '';
        $model->config_val7 = !empty($model->config_val7) ? Helper::decryptString($model->config_val7) : '';
        $model->config_val8 = !empty($model->config_val8) ? Helper::decryptString($model->config_val8) : '';
        $model->config_val9 = !empty($model->config_val9) ? Helper::decryptString($model->config_val9) : '';
        $model->config_val10 = !empty($model->config_val10) ? Helper::decryptString($model->config_val10) : '';
        $model->config_val11 = !empty($model->config_val11) ? Helper::decryptString($model->config_val11) : '';
        $model->config_val12 = !empty($model->config_val12) ? Helper::decryptString($model->config_val12) : '';
        $model->config_val13 = !empty($model->config_val13) ? Helper::decryptString($model->config_val13) : '';
        $model->config_val14 = !empty($model->config_val14) ? Helper::decryptString($model->config_val14) : '';
        $model->config_val15 = !empty($model->config_val15) ? Helper::decryptString($model->config_val15) : '';
        
        return $model;
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    protected function findModel($guid)
    {
        $model = MstConfiguration::findByGuid($guid, [
            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);
        if ($model == null) {
            throw new AppException("Oops! You trying to access manager doesn't exist or deleted.");
        }
        return $model;
    }

}
