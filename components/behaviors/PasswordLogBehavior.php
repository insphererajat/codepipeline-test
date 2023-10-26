<?php

namespace components\behaviors;

use yii\db\ActiveRecord;
use common\models\LogUserActivity;

/**
 * Description of PasswordResetBehavior
 *
 * @author Azam
 */
class PasswordLogBehavior extends \yii\base\Behavior {

    /**
     * @inheritdoc
     */
    public $_user;

    public function init() {
        parent::init();
    }

    public function events() {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate'
        ];
    }

    public function beforeValidate() {
        $this->_user = \common\models\User::findIdentity($this->owner->attributes['id']);
        if ($this->_user != NULL && !empty($this->owner->attributes['password'])) {
            if (\Yii::$app->getSecurity()->validatePassword($this->owner->attributes['password'], $this->_user->password_hash)) {
                $this->owner->addError('password', 'Sorry,  System cannot be allowed to use last password again.');
            } else if (in_array(md5($this->owner->attributes['password']), [$this->_user->password_hash3, $this->_user->password_hash2, $this->_user->password_hash1])) {
                $this->owner->addError('password', 'Sorry,  System cannot be allowed to use last 3 password again.');
            }
        }
    }

    public function afterValidate() {
        if ($this->_user && !$this->owner->hasErrors('password') && !empty($this->owner->attributes['password'])) {
            LogUserActivity::saveLoginHistory(LogUserActivity::SUCCESS, $this->_user, LogUserActivity::USER_RESET_PASSWORD);
            $this->_user->password_hash3 = $this->_user->password_hash2;
            $this->_user->password_hash2 = $this->_user->password_hash1;
            $this->_user->password_hash1 = md5($this->owner->attributes['password']);
            $this->_user->save();
        }
    }

}
