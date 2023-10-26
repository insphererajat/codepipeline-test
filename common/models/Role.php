<?php

namespace common\models;

use common\models\base\Role as BaseRole;

class Role extends BaseRole
{

    const ROLE_SUPER_ADMIN = 0;
    const ROLE_ADMIN = 1;
    const ROLE_HELPDESK = 2;
    const ROLE_CLIENT_ADMIN = 3;
    const ROLE_CSC = 4;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['name']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'name' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'name' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $baseRules = parent::rules();

        $myRules = [
            ['name', 'required'],
            [['name'], 'unique'],
            ['name', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => 'Role can contain only alphanumeric characters',]
        ];

        return \yii\helpers\ArrayHelper::merge($baseRules, $myRules);
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select(self::tableName() . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere(self::tableName() . '.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['notSuperAdminRole'])) {
            $modelAQ->andWhere(self::tableName() . '.id !=:notSuperAdminRole', [':notSuperAdminRole' => $params['notSuperAdminRole']]);
        }

        if (isset($params['notAdminRole'])) {
            $modelAQ->andWhere(self::tableName() . '.id !=:notAdminRole', [':notAdminRole' => $params['notAdminRole']]);
        }

        if (isset($params['inRole'])) {
            $modelAQ->andWhere(['IN', 'role.id', $params['inRole']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function getRoles($params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['resultCount' => caching\ModelCache::RETURN_ALL], $params));
    }

    public static function getRole($name, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['name' => $name], $params));
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function getRoleArray()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_HELPDESK => 'Help Desk',
            self::ROLE_CLIENT_ADMIN => 'Client Admin',
            self::ROLE_CSC => 'CSC User'
        ];
    }
    
    public static function getSuperAdminRole()
    {
        return self::findByParams(['id' => self::ROLE_ADMIN]);
    }

    public static function getAdminRole()
    {
        return self::findByParams(['inRole' => [self::ROLE_ADMIN, self::ROLE_SUPERADMIN], 'resultCount' => caching\ModelCache::RETURN_ALL]);
    }

    public static function rolesArr()
    {
        $roles = self::findByParams(['resultCount' => caching\ModelCache::RETURN_ALL, 'notAdmin' => true]);
        return !empty($roles) ? \yii\helpers\ArrayHelper::map($roles, 'id', 'name') : [];
    }

    public static function getRoleNameByUserId($userId = NULL)
    {
        if ($userId == NULL) {
            return '';
        }

        $userModel = \common\models\User::findById($userId);
        if (empty($userModel)) {
            return '';
        }

        $role = self::findById($userModel['role_id']);

        if (empty($role)) {
            return '';
        }

        return $role['name'];
    }

}
