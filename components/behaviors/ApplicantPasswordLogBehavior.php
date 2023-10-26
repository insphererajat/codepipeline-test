<?php

namespace components\behaviors;

use yii\db\ActiveRecord;
use common\models\LogUserActivity;

/**
 * Description of ApplicantPasswordLogBehavior
 *
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class ApplicantPasswordLogBehavior extends \yii\base\Behavior {

    /**
     * @inheritdoc
     */
    public $_applicant;

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
        $this->_applicant = \common\models\Applicant::findIdentity($this->owner->attributes['id']);
        if ($this->_applicant != NULL && !empty($this->owner->attributes['password'])) {
            if (\Yii::$app->getSecurity()->validatePassword($this->owner->attributes['password'], $this->_applicant->password_hash)) {
                $this->owner->addError('password', 'Sorry,  System cannot be allowed to use last password again.');
            } else if (in_array(md5($this->owner->attributes['password']), [$this->_applicant->password_hash3, $this->_applicant->password_hash2, $this->_applicant->password_hash1])) {
                $this->owner->addError('password', 'Sorry,  System cannot be allowed to use last 3 password again.');
            }
        }
    }

    public function afterValidate() {
        if ($this->_applicant && !$this->owner->hasErrors('password') && !empty($this->owner->attributes['password'])) {
            LogUserActivity::saveLoginHistory(LogUserActivity::SUCCESS, $this->_applicant, LogUserActivity::USER_RESET_PASSWORD, true);
            $this->_applicant->password_hash3 = $this->_applicant->password_hash2;
            $this->_applicant->password_hash2 = $this->_applicant->password_hash1;
            $this->_applicant->password_hash1 = md5($this->owner->attributes['password']);
            $this->_applicant->save();
        }
    }

}
