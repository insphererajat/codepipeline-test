<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers\location;

use Yii;
use common\models\search\location\MstTehsilSearch;
use common\models\location\MstTehsil;
use common\models\caching\ModelCache;
use common\models\Permission;
/**
 * Description of TehsilController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class TehsilController extends \backend\controllers\AdminController
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
        $model = new \common\models\location\MstTehsil();
        $searchModel = new \common\models\search\location\MstTehsilSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new \common\models\location\MstTehsil();
        if (\Yii::$app->request->isPost) {
            $model->created_by = \Yii::$app->user->id;
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("Tehsil has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['location/tehsil/index']));
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
                $this->setSuccessMessage("Tehsil has been updated successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['/location/tehsil/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionStatus($guid)
    {

        try {
            $model = MstTehsil::findByGuid($guid, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);

            if ($model === NULL) {
                throw new \components\exceptions\AppException("Invalid Tehsil!");
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
            if (count($model->mstDistricts) > 0) {
                throw new \components\exceptions\AppException("Tehsil assigned to the district.");
            }


            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            return \components\Helper::outputJsonResponse(['success' => 1]);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Unable to deleted this resource. Please find below error :<br/><br/>" . $ex->getMessage());
        }
    }

    protected function findModel($guid)
    {
        $model = \common\models\location\MstTehsil::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access state doesn't exist or deleted.");
        }
        return $model;
    }

}
