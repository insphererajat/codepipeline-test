<?php

namespace components\behaviors;

use yii\db\ActiveRecord;
use common\models\LogUserActivity;

/**
 * Description of ApplicantForgotPasswordLogBehavior
 *
 * @author Amit Handa
 */
class ApplicantForgotPasswordLogBehavior extends \yii\base\Behavior {

    /**
     * @inheritdoc
     */
    public $oldForgotPasswordToken;
    public $_applicant;

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
        $this->_applicant = \common\models\Applicant::findIdentity($this->owner->attributes['id']);
    }

    public function afterUpdate() {

        if ($this->_applicant != NULL && $this->oldForgotPasswordToken != $this->owner->attributes['password_reset_token']) {
            LogUserActivity::saveLoginHistory(LogUserActivity::SUCCESS, $this->_applicant, LogUserActivity::USER_FORGOT_PASSWORD, true);
        }

        return true;
    }

}
