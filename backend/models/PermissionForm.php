<?php

namespace frontend\modules\admin\models;

use Yii;

/**
 * Description of PermissionForm
 *
 * @author Pawan Kumar
 */
class PermissionForm extends \yii\base\Model
{

    public $guid;
    public $permission;

    public function rules()
    {
        return [
            [['guid'], 'required'],
            [['permission'], 'safe'],
        ];
    }

    public function save()
    {
        try {

            $model = \common\models\User::findByGuid($this->guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
            if ($model === NULL) {
                throw new \components\exceptions\AppException("Invalid request.");
            }

            $authModel = \common\models\AuthAssignment::findByUserId($model->id);
            if ($authModel === NULL) {
                throw new \components\exceptions\AppException("Invalid request.");
            }

            $userParams = [
                'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT,
                'resultCount' => \common\models\caching\ModelCache::RETURN_ALL
            ];
            $userPermissions = \common\models\UserPermission::findByUserId($model->id, $userParams);

            // Remove All Permission of this user
            if ($userPermissions !== NULL && !empty($userPermissions)) {
                foreach ($userPermissions as $userPermission) {
                    $userPermission->delete();
                }
            }

            //Re assign all permission to user 
            if (!empty($this->permission)) {
                foreach ($this->permission as $userPermission) {
                    (new \common\models\UserPermission)->saveUserPermission($model->id, $userPermission);
                }
            }

            return TRUE;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
        return FALSE;
    }

}
