<?php

namespace common\models;

use Yii;
use common\models\base\LogUserActivity as BaseLogUserActivity;
use common\models\caching\ModelCache;
use components\Helper;

/**
 * This is the model class for table "log_user_activity".
 *
 * @author Amit Handa
 */
class LogUserActivity extends BaseLogUserActivity
{

    const USER_LOGIN = 1;
    const USER_LOGOUT = 10;
    const USER_RESET_PASSWORD = 2;
    const USER_FORGOT_PASSWORD = 3;
    const SUCCESS = 1;
    const FAILED = 0;

    const MAX_ATTEMPTS = 10;
    const ACCOUNT_LOCKING_MINUTES = '5';

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on']
                ],
            ],
            \components\behaviors\GuidBehavior::className()
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->applicant_id)) {
            $model = Applicant::findById($this->applicant_id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        } else {
            $model = User::findIdentity($this->user_id);
        }

        if ($this->type == self::USER_LOGIN) {
            if ($this->status == self::FAILED && $model->failed_attempt <= self::MAX_ATTEMPTS) {
                $model->failed_attempt = $model->failed_attempt + 1;
                $model->failed_timestamp = time();
            } else if ($this->status == self::SUCCESS) {
                $model->failed_attempt = 0;
                $model->failed_timestamp = NULL;
            }
        } else if ($this->type == self::USER_RESET_PASSWORD) {
            $model->password_hash3 = $model->password_hash2;
            $model->password_hash2 = $model->password_hash1;
            $model->password_hash1 = $model->password_hash;
        }
        $model->save(true, ['failed_attempt', 'failed_timestamp', 'password_hash1', 'password_hash2', 'password_hash3']);
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public static function saveLoginHistory($status = 0, $user, $type, $isApplicant = false)
    {
        try {
            if (empty($type) || empty($user)) {
                return false;
            }
            $loginModel = new LogUserActivity();
            $loginModel->type = $type;
            if ($isApplicant) {
                $loginModel->applicant_id = $user->id;
            } else {
                $loginModel->user_id = $user->id;
            }
            $loginModel->status = $status;
            if ($loginModel->save()) {
                return TRUE;
            }
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
        return FALSE;
    }

    public function beforeSave($insert)
    {
        $this->device_type = Helper::getDeviceType();
        $this->ip_address = Helper::GetUserIp();
        $this->useragent = Helper::getUserAgent();

        return parent::beforeSave($insert);
    }

}
