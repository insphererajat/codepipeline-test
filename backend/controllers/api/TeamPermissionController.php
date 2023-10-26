<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers\api;

use backend\controllers\AdminController;
use Yii;
use common\models\caching\ModelCache;
use common\models\Team;
use common\models\User;
use common\models\UserPermission;
use components\Helper;

/**
 * Description of TeamPermissionController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class TeamPermissionController extends AdminController
{
    public function actionAddUser()
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams['userIds']) || empty($postParams['guid'])) {
            throw new \components\exceptions\AppException("Invalid request.");
        }

        $team = $this->findByModel($postParams['guid']);
        

        try {
            $alreadyAttached = [];
            foreach ($postParams['userIds'] as $userId) {

                $userModel = \common\models\User::findById($userId);
                if ($userModel === NULL) {
                    throw new \components\exceptions\AppException("Sorry, You are trying to selected user doesn't exists or deleted.");
                }

                $params = [
                    'teamId' => $team->id, 
                ];
                $userConnection = \common\models\TeamUser::findByUserId($userModel['id'], $params);
                if (!empty($userConnection)) {
                    $alreadyAttached[] = ucwords($userModel['firstname']." ".$userModel['lastname'])." has been already added on this team.";
                    continue;
                }

                $model = new \common\models\TeamUser();
                $model->team_id = $team->id;
                $model->user_id = $userModel['id'];
                if(!$model->save()) {
                    throw new \components\exceptions\AppException(Helper::convertModelErrorsToString($model->errors));
                }
     
            }
          
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }

        return \components\Helper::outputJsonResponse(['success' => 1, 'errors' => $alreadyAttached]);
    }
    
    public function actionRemoveUser()
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams['userId']) || empty($postParams['teamGuid'])) {
            throw new \components\exceptions\AppException("Invalid request.");
        }

        $model = $this->findByModel($postParams['teamGuid']);

        $userModel = \common\models\User::findById($postParams['userId']);
        if ($userModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to selected user doesn't exists or deleted.");
        }

        try {
            $params = [
                'teamId' => $model->id, 
                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ];
            $userConnection = \common\models\TeamUser::findByUserId($userModel['id'], $params);
            if (!empty($userConnection)) {
                $userConnection->delete();
            }
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }

        return \components\Helper::outputJsonResponse(['success' => 1]);
    }

    public function actionAddPermission()
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams['permission']) || empty($postParams['guid'])) {
            throw new \components\exceptions\AppException("Invalid request.");
        }

        $savedParams = [];
        $permissionModel = \common\models\Permission::findByName($postParams['permission']);
        if ($permissionModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to selected permission doesn't exists or deleted.");
        }
        $savedParams['permission_name'] = $permissionModel['permission_name'];

        $fn = "findByTeamId";
        if (isset($postParams['type'] ) && $postParams['type'] === "user") {
            $fn = "findByUserId";
            $model = User::findByGuid($postParams['guid'], ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if ($model === NULL) {
                throw new \components\exceptions\AppException("Sorry, You are trying to selected user doesn't exists or deleted.");
            }
            $savedParams['user_id'] = $model->id;
        } else {
            $model = $this->findByModel($postParams['guid']);
            $savedParams['team_id'] = $model->id;
        }
    

        try {
            $params['permission'] = $permissionModel['permission_name'];
            $params['resultFormat'] = ModelCache::RETURN_TYPE_OBJECT;
            $userPermission = UserPermission::{$fn}($model->id, $params);
            if (empty($userPermission)) {
                ( new UserPermission())->saveRecord($savedParams);
            }
            else {
               $userPermission->delete(); 
            }
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }

        return \components\Helper::outputJsonResponse(['success' => 1]);
    }
  
 
    protected function findByModel($guid)
    {
        $model = Team::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access team doesn't exists or deleted.");
        }
        return $model;
    }

}
