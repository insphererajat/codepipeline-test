<?php

namespace components;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Description of User
 *
 * @author Pawan Kumar
 */
class User extends \yii\web\User
{

    private $_categories = [];
    private $_userModules = [];
    private $_classes = [];
    private $_classSections = [];

    public function hasModulePermission($module)
    {
        if ($this->hasAdminRole()) {
            return TRUE;
        }

        if (empty($this->_userModules)) {
            $this->getModules();
        }

        return ArrayHelper::isIn($module, $this->_userModules) ? TRUE : FALSE;
    }

    public function hasAdminRole()
    {
        if (!isset($this->getIdentity()->role_id) || empty($this->getIdentity()->role_id)) {
            return FALSE;
        }
        $roles = [
            \common\models\Role::ROLE_SUPERADMIN,
            \common\models\Role::ROLE_ADMIN
        ];

        return ArrayHelper::isIn($this->getIdentity()->role_id, $roles) ? TRUE : FALSE;
    }

    public function hasSuperAdminRole()
    {
        return ($this->getIdentity()->role_id == \common\models\Role::ROLE_SUPERADMIN) ? TRUE : FALSE;
    }

    public function hasFacultyRole()
    {
        return ($this->getIdentity()->role_id == \common\models\Role::ROLE_FACULTY) ? TRUE : FALSE;
    }

    public function hasSubAdminRole()
    {
        return ($this->getIdentity()->role_id == \common\models\Role::ROLE_SUBADMIN) ? TRUE : FALSE;
    }
    
    // new role for data entry 
    public function hasDataEntryRole()
    {
        return ($this->getIdentity()->role_id == \common\models\Role::ROLE_DATAENTRY) ? TRUE : FALSE;
    }

    public function categories()
    {
        if (empty($this->_categories)) {
            $params = [
                'resultFormat' => 'array',
                'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
                'type' => \common\models\UserPermission::TYPE_CATEGORY
            ];
            $categoryModel = \common\models\UserPermission::findByUserId($this->getId(), $params);
            if ($categoryModel !== NULL) {
                $this->_categories = ArrayHelper::getColumn($categoryModel, 'category_id');
            }
        }
        return $this->_categories;
    }

    public function hasCategoryPermission($categoryId)
    {
        if ($this->hasAdminRole()) {
            return TRUE;
        }

        if (empty($this->_categories)) {
            $this->categories();
        }

        return ArrayHelper::isIn($categoryId, $this->_categories) ? TRUE : FALSE;
    }

    public function getAcademicPostingPermission()
    {
        if ($this->hasAdminRole()) {
            return TRUE;
        }

        if ($this->_classes == NULL || empty($this->_classes)) {
            $params = [
                'type' => \common\models\UserPermission::TYPE_POSTING,
                'nullOperator' => [
                    'column' => 'user_permission.class_section_id'
                ],
                'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            ];
            $postingPermission = \common\models\UserPermission::findByUserId($this->getId(), $params);
            $this->_classes = ArrayHelper::getColumn($postingPermission, 'class_id');
        }

        return $this->_classes;
    }

    public function getAttendancePermission()
    {
        if ($this->hasAdminRole()) {
            return TRUE;
        }
        if ($this->_classSections == NULL || empty($this->_classSections)) {
            $params = [
                'type' => \common\models\UserPermission::TYPE_ATTENDANCE,
                'nullOperator' => [
                    'column' => 'user_permission.class_id'
                ],
                'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            ];
            $attendancePermission = \common\models\UserPermission::findByUserId($this->getId(), $params);
            if ($attendancePermission !== NULL) {
                $this->_classSections = ArrayHelper::getColumn($attendancePermission, 'class_section_id');
            }
        }
        return $this->_classSections;
    }
    
    public function getUserGroupByClassSection()
    {
        $classSectionIds = $this->getAttendancePermission();
        $results = [];
        if ($classSectionIds !== NULL) {
            $i = 0;

            foreach ($classSectionIds as $classSectionId) {

                $params = [
                    'selectCols' => ['classes.id AS classId', 'classes.name AS className', 'classes.alias AS classAlias',
                        'section.id AS sectionId', 'section.name AS sectionName', 'section.alias AS sectionAlias',
                        'class_section.id AS classSectionId', 'class_section.alias classSectionAlias'],
                    'joinWithClass' => 'innerJoin',
                    'joinWithSection' => 'innerJoin',
                    'classStatus' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
                    'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
                    'isDeletedSection' => \common\models\caching\ModelCache::IS_DELETED_NO,
                    'sectionStatus' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
                    'isAttendanceAllowed' => \common\models\Classes::ATTENDANCE_ALLOWED_YES,
                    'schoolId' => $this->getIdentity()->school_id,
                    'resultFormat' => 'array'
                ];
                $classSectionModel = \common\models\ClassSection::findById($classSectionId, $params);

                if ($classSectionModel !== NULL) {
                    $results[$i]['classSectionId'] = $classSectionModel['classSectionId'];
                    $results[$i]['classSectionAlias'] = $classSectionModel['classSectionAlias'];
                    $results[$i]['class'] = [
                        'classId' => $classSectionModel['classId'],
                        'className' => $classSectionModel['className'],
                        'classAlias' => $classSectionModel['classAlias']
                    ];
                    $results[$i]['section'] = [
                        'sectionId' => $classSectionModel['sectionId'],
                        'sectionName' => $classSectionModel['sectionName'],
                        'sectionAlias' => $classSectionModel['sectionAlias']
                    ];
                    $i++;
                }
            }
        }
        return $results;
    }
    
    public function getUserGroupByClasses()
    {
        $classIds = $this->getAcademicPostingPermission();
        $results = [];
        if ($classIds !== NULL) {
            $i = 0;
            foreach ($classIds as $classId) {

                $params = [
                    'selectCols' => ['class.id', 'class.name'],
                    'status' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
                    'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
                    'schoolId' => $this->getIdentity()->school_id,
                    'resultFormat' => 'array'
                ];
                $classModel = \common\models\Classes::findById($classId, $params);
                if ($classModel !== NULL) {
                    $results[$i]['classId'] = $classModel['id'];
                    $results[$i]['classAlias'] = $classModel['name'];
                    $i++;
                }
            }
        }
        return $results;
    }

    private function getModules()
    {
        $params = [
            'resultFormat' => 'array',
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            'type' => \common\models\UserPermission::TYPE_CORE
        ];
        $userPermission = \common\models\UserPermission::findByUserId($this->getId(), $params);
        if ($userPermission !== NULL) {
            $this->_userModules = ArrayHelper::getColumn($userPermission, 'permission_name');
        }
    }
    
    public function hasAcademicModuleAccess()
    {
        $allowAccess = FALSE;
        $modules = \common\models\Permission::getAcademicModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $allowAccess = TRUE;
                break;
            }
        }
        return $allowAccess;
    }

    public function hasCmsModuleAccess()
    {
        $allowAccess = FALSE;
        $modules = \common\models\Permission::getCmsModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $allowAccess = TRUE;
                break;
            }
        }
        return $allowAccess;
    }
    
    public function hasMisModuleAccess()
    {
        $allowAccess = FALSE;
        $modules = \common\models\Permission::getMisModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $allowAccess = TRUE;
                break;
            }
        }
        return $allowAccess;
    }
    
    public function getAcademicModuleUrl()
    {
        $url = '';
        $modules = \common\models\Permission::getAcademicModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $url = '/admin/' . $module['url'];
                break;
            }
        }
        return $url;
    }

    public function getCmsModuleUrl()
    {
        $url = '';
        $modules = \common\models\Permission::getCmsModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $url = '/admin/' . $module['url'];
                break;
            }
        }
        return $url;
    }
    
    public function hasCoreModuleAccess()
    {
        $allowAccess = FALSE;
        $modules = \common\models\Permission::getCoreModules();
        foreach ($modules as $module) {
            if ($this->hasFacultyRole() && in_array($module['value'], [\common\models\Permission::STUDENT, \common\models\Permission::EMPLOYEE, \common\models\Permission::ATTENDANCE, \common\models\Permission::SEND_ATTENDANCE_MESSAGE])) {
                continue;
            }
            if ($this->hasModulePermission($module['value'])) {
                $allowAccess = TRUE;
                break;
            }
        }
        return $allowAccess;
    }

    public function getMisModuleUrl()
    {
        $url = '';
        $modules = \common\models\Permission::getMisModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $url = $module['url'];
                break;
            }
        }
        return $url;
    }

    public function hasFeeModuleAccess()
    {
        $allowAccess = FALSE;
        $modules = \common\models\Permission::getFeesModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $allowAccess = TRUE;
                break;
            }
        }
        return $allowAccess;
    }
    
    public function getFeeModuleUrl()
    {
        $url = '';
        $modules = \common\models\Permission::getFeesModules();
        foreach ($modules as $module) {
            if ($this->hasModulePermission($module['value'])) {
                $url = $module['url'];
                break;
            }
        }
        return $url;
    }
    
    public function getResultPermission()
    {
        if ($this->hasAdminRole()) {
            return TRUE;
        }
        if ($this->_classSections == NULL || empty($this->_classSections)) {

            $params = [
                'selectCols' => ['user_permission.class_section_id'],
                'type' => \common\models\UserPermission::TYPE_RESULT,
                'nullOperator' => [
                    'column' => 'user_permission.class_id'
                ],
                'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            ];
            $resultPermission = \common\models\UserPermission::findByUserId($this->getId(), $params);
            if ($resultPermission !== NULL) {
                $this->_classSections = ArrayHelper::getColumn($resultPermission, 'class_section_id');
            }
        }
        return $this->_classSections;
    }

}
