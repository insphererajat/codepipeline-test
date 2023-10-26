<?php
namespace backend\controllers;

use backend\models\UserForm;
use Yii;
use common\models\User;
use common\models\caching\ModelCache;
use common\models\Media;
use components\Helper;
use components\Security;
use Exception;

/**
 * Description of ProfileController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class ProfileController extends AdminController
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->hasAdminRole() && !Yii::$app->user->hasClientAdminRole() && !Yii::$app->user->hasHelpdeskRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        $userModel = $this->findByModel(Yii::$app->user->identity->guid);

        $model = new UserForm();
        $model->setScenario(UserForm::SCENARIO_USER_UPDATE);
        $model->updateProfile = true;
        $model->attributes = $userModel->attributes;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if(!empty($post['UserForm']['password'])) {
                $post['UserForm']['password'] = Security::cryptoAesDecrypt($post['UserForm']['password'], Yii::$app->params['hashKey']);
            }
            if(!empty($post['UserForm']['verifypassword'])) {
                $post['UserForm']['verifypassword'] = Security::cryptoAesDecrypt($post['UserForm']['verifypassword'], Yii::$app->params['hashKey']);
            }
            if ($model->load($post) && $model->validate() && $model->save()) {
                $this->setSuccessMessage('Profile  has been updated successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(['profile/index']));
            }
            else {
               $this->setErrorMessage(\yii\helpers\Html::errorSummary($model, ['header' => '']));
            }
        }
        return $this->render('index', ['model' => $model, 'profile' => TRUE]);
    }

    public function actionDeleteMedia()
    {
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
            if($userModel->save(true, ['profile_media_id'])) {
                $mediaModel->delete();
            }
        } catch (Exception $ex) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access media doesn't exists or deleted.");
        }

        return Helper::outputJsonResponse(['success' => 1]);
    }

    public function findByModel($guid)
    {
        $model = User::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access user doesn't exists or deleted.");
        }
        return $model;
    }
}
