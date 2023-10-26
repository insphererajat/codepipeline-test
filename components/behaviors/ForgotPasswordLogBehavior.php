<?php

namespace components\behaviors;

use yii\db\ActiveRecord;
use common\models\LogUserActivity;

/**
 * Description of ForgotPasswordLogBehavior
 *
 * @author Azam
 */
class ForgotPasswordLogBehavior extends \yii\base\Behavior {

    /**
     * @inheritdoc
     */
    public $oldForgotPasswordToken;
    public $_user;

    public function init() {
        parent::init();
    }

    public function events() {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    public function beforeUpdate() {
        $this->oldForgotPasswordToken = $this->owner->oldAttributes['password_reset_token'];
        $this->_user = \common\models\User::findIdentity($this->owner->attributes['id']);
    }

    public function afterUpdate() {

        if ($this->_user != NULL && $this->oldForgotPasswordToken != $this->owner->attributes['password_reset_token']) {
            LogUserActivity::saveLoginHistory(LogUserActivity::SUCCESS, $this->_user, LogUserActivity::FORGOT_PASSWORD);
        }

        return true;
    }

}
