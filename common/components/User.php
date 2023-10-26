<?php

namespace common\components;

use Yii;
use common\models\Role;
use yii\helpers\ArrayHelper;
use common\models\caching\ModelCache;

/**
 * Description of User
 *
 * @author Amit Handa
 */
class User extends \yii\web\User
{
    private $_userModules;

    public function beforeLogin( $identity, $cookieBased, $duration )
    {
        \common\models\User::beforeUserLogin();        
        return parent::beforeLogin($identity, $cookieBased, $duration);
    }
    
    public function afterLogin( $identity, $cookieBased, $duration )
    {
        \common\models\User::afterUserLogin($identity);        
        return parent::afterLogin($identity, $cookieBased, $duration);
    }
    
    public function afterLogout( $identity )
    {
        \common\models\User::afterUserLogout($identity);
        return parent::afterLogout($identity);
    }

    public function hasAdminRole()
    {
        return (!$this->isGuest && $this->getIdentity()->role_id == Role::ROLE_ADMIN) ? TRUE : FALSE;
    }
    
    public function hasClientAdminRole()
    {
        return (!$this->isGuest &&$this->getIdentity()->role_id == Role::ROLE_CLIENT_ADMIN) ? TRUE : FALSE;
    }
    
    public function hasHelpdeskRole()
    {
        return (!$this->isGuest && ArrayHelper::isIn($this->getIdentity()->role_id, [Role::ROLE_HELPDESK, Role::ROLE_CSC])) ? TRUE : FALSE;
    }
    
    public function hasCscRole()
    {
        return (!$this->isGuest &&$this->getIdentity()->role_id == Role::ROLE_CSC) ? TRUE : FALSE;
    }
}
