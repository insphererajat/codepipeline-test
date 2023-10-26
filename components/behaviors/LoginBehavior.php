<?php

namespace components\behaviors;

use common\models\caching\ModelCache;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Applicant;
use common\models\LogUserActivity;

/**
 * Description of LoginBehavior
 *
 * @author Pawan Kumar
 */
class LoginBehavior extends \yii\base\Behavior
{

    public $usernameAttribute = 'username';
    public $passwordAttribute = 'password';
    public $minutes = LogUserActivity::ACCOUNT_LOCKING_MINUTES;
    public $user;
    private $hasApplicant = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        date_default_timezone_set('Asia/Kolkata');
        parent::init();
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    //Set User Before Validate
    public function beforeValidate()
    {
        if(isset($this->owner->hasApplicantLogin)  && $this->owner->hasApplicantLogin) {
            if(!empty($this->owner->username)){
                $this->user = Applicant::findByEmail($this->owner->username,['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            }else{
                $params = [
                    'isActive' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
                    'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
                ];
                $this->user = Applicant::findByKeys($params);
            }
            $this->hasApplicant = true;
        }
        else {
            $this->user = User::findByUsername($this->owner->username);
        }
    }

    // Save User Login History
    public function afterValidate()
    {

        if (!empty($this->user)) {
            if (LogUserActivity::MAX_ATTEMPTS == $this->user->failed_attempt && $this->user->failed_timestamp > time()) {
                return false;
            }

            if ($this->owner->hasErrors($this->passwordAttribute)) {
                $this->saveLoginHistory(LogUserActivity::FAILED);
            } else if (!$this->owner->hasErrors($this->usernameAttribute) && !$this->owner->hasErrors($this->passwordAttribute)) {
                $this->saveLoginHistory(LogUserActivity::SUCCESS);
                return false;
            }
        }
        return true;
    }

    public function saveLoginHistory($status)
    {
        $loginModel = new LogUserActivity();
        $loginModel->type = LogUserActivity::USER_LOGIN;
        if ($this->hasApplicant) {
            $loginModel->applicant_id = $this->user->id;
        } else {
            $loginModel->user_id = $this->user->id;
        }

        $loginModel->status = $status;
        $loginModel->save();
    }


}
