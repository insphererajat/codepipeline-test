<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers\location;

use Yii;
use common\models\search\location\MstStateSearch;
use common\models\location\MstState;
use common\models\caching\ModelCache;
use common\models\Permission;
/**
 * Description of StateController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class StateController extends \backend\controllers\AdminController
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
        $model = new \common\models\location\MstState();
        $searchModel = new \common\models\search\location\MstStateSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new \common\models\location\MstState();
        if (\Yii::$app->request->isPost) {
            $model->created_by = \Yii::$app->user->id;
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage("State has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['location/state/index']));
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
                $this->setSuccessMessage("State has been updated successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['/location/state/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($guid)
    {
        $model = $this->findModel($guid);
        if (count($model->mstDistricts) > 0) {
            throw new \components\exceptions\AppException("Sorry, This state already assigned to district.");
        }

        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            $this->setSuccessMessage("State has been deleted successfully.");
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Unable to deleted this state. Please find below error :<br/><br/>" . $ex->getMessage());
        }

        return $this->redirect(\yii\helpers\Url::toRoute(['/location/state/index']));
    }

    protected function findModel($guid)
    {
        $model = \common\models\location\MstState::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access state doesn't exist or deleted.");
        }
        return $model;
    }

}
