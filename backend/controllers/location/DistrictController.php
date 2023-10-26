<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers\location;

use Yii;
use common\models\location\MstDistrict;
use common\models\search\location\MstDistrictSearch;
use common\models\caching\ModelCache;
use common\models\Permission;

/**
 * Description of DistrictController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class DistrictController extends  \backend\controllers\AdminController
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

        $model = new MstDistrict();
        $searchModel = new MstDistrictSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new \common\models\location\MstDistrict();
        if (\Yii::$app->request->isPost) {
            $model->created_by = \Yii::$app->user->id;
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("District has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['location/district/index']));
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($guid)
    {

        $model = $this->findModel($guid);
        if (\Yii::$app->request->isPost) {
            $model->modified_by = \Yii::$app->user->id;
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("District has been updated successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['location/district/index']));
            }
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionDelete($guid)
    {

        $model = $this->findModel($guid);
        if (count($model->mstTehsils) > 0) {
            throw new \components\exceptions\AppException("Sorry, This district already assigned to tehsils.");
        }
        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            $this->setSuccessMessage("District has been deleted successfully.");
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Unable to deleted this district. Please find below error :<br/><br/>" . $ex->getMessage());
        }

        return $this->redirect(\yii\helpers\Url::toRoute(['/location/district/index']));
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
