<?php

namespace common\models;

use Yii;
use common\models\base\UserPermission as BaseUserPermission;
use common\models\caching\ModelCache;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "UserPermission".
 *
 *@author Amit Handa
 */
class UserPermission extends BaseUserPermission
{
    const SUPER_ADMIN = 1;
    const DOMAIN_ADMIN = 2;
    const DOMAIN_USER = 3;
    
    public function behaviors()
    {
        return [
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['permission_name']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'permission_name' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'permission_name' => 'cleanEncodeUTF8',
                    ]
                ]
            ],
        ];
    }
    
    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        } else {
            $modelAQ->select('user_permission.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere('user_permission.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['userId'])) {
            $modelAQ->andWhere('user_permission.user_id =:userId', [':userId' => $params['userId']]);
        }

        if (isset($params['teamId'])) {
            $modelAQ->andWhere('user_permission.team_id =:teamId', [':teamId' => $params['teamId']]);
        }

        if (isset($params['permission'])) {
            $modelAQ->andWhere('user_permission.permission_name =:permission', [':permission' => $params['permission']]);
        }

        if (isset($params['nullTeamId'])) {
            $modelAQ->andWhere('user_permission.team_id IS NULL');
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByUserId($userId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['userId' => $userId], $params));
    }

    public static function findByTeamId($teamId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['teamId' => $teamId], $params));
    }

    public function saveRecord($data)
    {
        try {
            $model = new UserPermission();
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if ($model->save()) {
                return TRUE;
            }
        }
        catch (\Exception $ex) {
            throw $ex;
        }
        return FALSE;
    }

}
