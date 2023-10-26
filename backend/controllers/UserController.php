<?php

namespace backend\controllers;

use backend\models\UserForm;
use Yii;
use common\models\User;
use common\models\caching\ModelCache;
use common\models\Media;
use common\models\Permission;
use common\models\UserRole;
use components\exceptions\AppException;
use components\Helper;

/**
 * Description of UserController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class UserController extends AdminController
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
        $searchModel = new \common\models\search\UserSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($guid)
    {
        $userModel = $this->findByModel($guid);
        return $this->render('view', [
            'model' => $userModel
        ]);
    }

    public function actionCreate()
    {

        // if (!Yii::$app->user->hasPermission(Permission::CREATE_UPDATE_USER)) {
        //     throw new AppException(Yii::t('app', 'forbidden.access'));
        // }

        $model = new UserForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('User has been added successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['user/index']));
            }
            $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($guid)
    {

        // if (!Yii::$app->user->hasPermission(Permission::CREATE_UPDATE_USER)) {
        //     throw new AppException(Yii::t('app', 'forbidden.access'));
        // }

        $userModel = $this->findByModel($guid);

        $model = new UserForm;
        $model->setScenario(UserForm::SCENARIO_USER_UPDATE);
      
        $model->loadUserData($userModel);
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('User has been updated successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['user/index']));
            } else {
                $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($guid)
    {

        // if (!Yii::$app->user->hasPermission(Permission::DELETE_USER)) {
        //     throw new AppException(Yii::t('app', 'forbidden.access'));
        // }

        $model = $this->findByModel($guid);
        if ($model->id === Yii::$app->user->id) {
            throw new \components\exceptions\AppException(Yii::t('app', 'self.account.delete'));
        }
        try {
            $model->is_deleted = ModelCache::IS_DELETED_YES;
            $model->modified_by = Yii::$app->user->id;
            $model->save(TRUE, ['is_deleted', 'modified_by']);
            $this->setSuccessMessage(Yii::t('app', 'success.delete', ['title' => 'User']));
            return $this->redirect(\yii\helpers\Url::toRoute(['user/index']));
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    public function actionDeleteMedia()
    {

        // if (!Yii::$app->user->hasPermission(Permission::CREATE_UPDATE_USER)) {
        //     throw new AppException(Yii::t('app', 'forbidden.access'));
        // }

        $postParams = Yii::$app->request->post();
        if (empty($postParams['mediaId'])) {
            throw new \components\exceptions\AppException("Invalid request.");
        }

        $mediaModel = Media::findById($postParams['mediaId'], ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($mediaModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access media doesn't exists or deleted.");
        }

        $userModel = $this->findByModel(Yii::$app->user->identity->guid);
        if ($userModel->profile_media_id != $mediaModel->id) {
            throw new \components\exceptions\AppException("Permission denied.");
        }

        try {
            $userModel->profile_media_id = NULL;
            if ($userModel->save(true, ['profile_media_id'])) {
                $mediaModel->delete();
            }
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access media doesn't exists or deleted.");
        }

        return Helper::outputJsonResponse(['success' => 1]);
    }


    public function actionPermission($guid)
    {
        // if (!Yii::$app->user->hasPermission(Permission::MODULE_USER_PERMISSION) || !Yii::$app->network->hasSubNetwork()) {
        //     throw new AppException(Yii::t('app', 'forbidden.access'));
        // }

        $model = $this->findByModel($guid);
        return $this->render('permission', ['model' => $model]);

    }

    public function findByModel($guid)
    {
        $params =  ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT];
        $model = User::findByGuid($guid, $params);
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access user doesn't exists or deleted.");
        }
        return $model;
    }
}
