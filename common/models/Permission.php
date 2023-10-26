<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\Permission as BasePermission;

/**
 * Description of Permission
 *
 * @author Amit Handa
 */
class Permission extends BasePermission
{

    const MODULE_USER = 'MODULE_USER';
    const CREATE_UPDATE_USER = 'CREATE_UPDATE_USER';
    const DELETE_USER = 'DELETE_USER';

    const MODULE_USER_PERMISSION = 'MODULE_USER_PERMISSION';

    const MODULE_PAGE = 'MODULE_PAGE';
    const CREATE_UPDATE_PAGE = 'CREATE_UPDATE_PAGE';
    const DELETE_PAGE = 'DELETE_PAGE';

    const MODULE_TAXONOMY = 'MODULE_TAXONOMY';
    const CREATE_UPDATE_TAXONOMY = 'CREATE_UPDATE_TAXONOMY';
    const DELETE_TAXONOMY = 'DELETE_TAXONOMY';

    const MODULE_MEDIA = 'MODULE_MEDIA';
    
    const MODULE_MEDIA_DIRECTORY= 'MODULE_MEDIA_DIRECTORY';
    const CREATE_UPDATE_MEDIA_DIRECTORY= 'CREATE_UPDATE_MEDIA_DIRECTORY';
    const DELETE_MEDIA_DIRECTORY= 'DELETE_MEDIA_DIRECTORY';

    const MODULE_POST = 'MODULE_POST';
    const CREATE_UPDATE_POST= 'CREATE_UPDATE_POST';
    const DELETE_POST = 'DELETE_POST';

    const MODULE_MENU = 'MODULE_MENU';
    const CREATE_UPDATE_MENU= 'CREATE_UPDATE_MENU';
    const DELETE_MENU = 'DELETE_MENU';

    const MODULE_TEMPLATE = 'MODULE_TEMPLATE';

    const MODULE_WIDGET = 'MODULE_WIDGET';
    const CREATE_UPDATE_SHORTCODES = 'CREATE_UPDATE_SHORTCODES';
    const DELETE_SHORTCODES = 'DELETE_SHORTCODES';


    public static function reservedConstants()
    {
        return [
            'afterDelete','afterFind','afterInsert','afterRefresh','afterUpdate','afterValidate','beforeDelete','beforeInsert','beforeUpdate','beforeValidate','EVENT', 'OP', 'init','default'
        ];
    }
    
    public static function savePermissions()
    {
        try {
            $class = new \ReflectionClass('common\models\Permission');
            $constantList = $class->getConstants();
            foreach ($constantList as $constant) {
                $words = explode('_', $constant);
                if (isset($words[0]) && \yii\helpers\ArrayHelper::isIn($words[0], self::reservedConstants())) {
                    continue;
                }

                $permissionModel = self::findByName($constant);
                if ($permissionModel == NULL) {
                    $permissionModel = new Permission;
                    $permissionModel->isNewRecord = TRUE;
                    $permissionModel->permission_name = $constant;
                    $permissionModel->save();
                }
            }
        }
        catch (\Exception $ex) {
            print_r($ex->getTraceAsString()); die;
            throw $ex;
        }
        return TRUE;
    }

    public static function getAllPermission()
    {
        return [
           
        ];
    }
    
    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select('permission.*');
        }

        if (isset($params['name'])) {
            $modelAQ->andWhere('permission.permission_name =:name', [':name' => $params['name']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }
    
    public static function findByName($name, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['name' => $name], $params));
    }
    
    
    public static function getUserPermissionArr($key = null)
    {
        $permission = [
            self::MODULE_USER => 'Access User',
            self::CREATE_UPDATE_USER => 'Create / Update User',
            self::DELETE_USER => 'Delete User',
            self::MODULE_USER_PERMISSION => 'Create /Update / Revoke User Permission',
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }

    public static function getPostPermissionArr($key = null)
    {
        $permission = [
            self::MODULE_POST => 'Access Posts',
            self::CREATE_UPDATE_POST => 'Create / Update Posts',
            self::DELETE_POST => 'Delete Posts'
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }
    
    public static function getPagePermissionArr($key = null)
    {
        $permission = [
            self::MODULE_PAGE => 'Access Pages',
            self::CREATE_UPDATE_PAGE => 'Create / Update Pages',
            self::DELETE_PAGE => 'Delete Pages',
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }

    public static function getMenuPermissionArr($key = null)
    {
        $permission = [
            self::MODULE_MENU => 'Access Menus',
            self::CREATE_UPDATE_MENU => 'Create / Update Menus',
            self::DELETE_MENU => 'Delete Menus',
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }
    
    // public static function getTaxonomyPermissionArr($key = null)
    // {
    //     $permission = [
    //         self::MODULE_TAXONOMY => 'Access Taxonomy',
    //         self::CREATE_UPDATE_TAXONOMY => 'Create / Update Taxonomy',
    //         self::DELETE_TAXONOMY => 'Delete Taxonomy'
    //     ];

    //     return !empty($key) ? $permission[$key] : $permission;
    // }

    public static function getMediaPermissionArr($key = null)
    {
        $permission = [
            self::MODULE_MEDIA => 'Upload Media',
            self::MODULE_MEDIA_DIRECTORY => 'Access Media / Directory',
            self::CREATE_UPDATE_MEDIA_DIRECTORY => 'Create / Update  Media / Directory',
            self::DELETE_MEDIA_DIRECTORY => 'Delete  Media / Directory'
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }

    public static function getWidgetPermissionArr($key = null)
    {
        $permission = [
            self::MODULE_WIDGET => 'Access Widgets',
            self::CREATE_UPDATE_SHORTCODES => 'Create / Update  Widgets Shortcodes',
            self::DELETE_SHORTCODES => 'Delete  Widgets Shortcodes'
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }

    public static function getTemplatePermissionArr($key = null)
    {
        $permission = [
            self::MODULE_TEMPLATE => 'Access Templates'
        ];

        return !empty($key) ? $permission[$key] : $permission;
    }

    
    
} 
