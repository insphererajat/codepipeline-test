<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use common\models\base\Applicant as BaseApplicant;

/**
 * Description of Applicant
 *
 * @author Amit Handa
 */
class Applicant extends BaseApplicant implements IdentityInterface
{
    
    const FRONTEND_LOGIN_KEY = 'frontend_login_authentication';
    const FRONTEND_FIXATION_COOKIE = 'frontend_fixation_cookie';
    const FRONTEND_SESSION_VALUE = 9980022;
    const FORM_STEP_SUBMITTED = 8;
    
    public $gender;
    public $date_of_birth;
    public $mother_name;
    public $father_name;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            \components\behaviors\GuidBehavior::className(),
            \components\behaviors\ApplicantForgotPasswordLogBehavior::className(),
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['name', 'email', 'mobile', 'auth_key', 'password_hash', 'password_reset_token']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'name' => 'cleanEncodeUTF8',
                        'email' => 'cleanEncodeUTF8',
                        'mobile' => 'cleanEncodeUTF8',
                        'auth_key' => 'cleanEncodeUTF8',
                        'password_hash' => 'cleanEncodeUTF8',
                        'password_reset_token' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'name' => 'cleanEncodeUTF8',
                        'email' => 'cleanEncodeUTF8',
                        'mobile' => 'cleanEncodeUTF8',
                        'auth_key' => 'cleanEncodeUTF8',
                        'password_hash' => 'cleanEncodeUTF8',
                        'password_reset_token' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_active' => caching\ModelCache::IS_ACTIVE_YES]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['api_auth_token' => $token]);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
        $this->password_reset_token_expiry_at = null;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Finds student by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $model = static::findOne([
                    'password_reset_token' => $token,
                    'is_active' => caching\ModelCache::IS_ACTIVE_YES,
        ]);
        if (empty($model) || $model->password_reset_token_expiry_at < time()) {
            return false;
        }
        return $model;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        $modelAQ->select($tableName . '.*');
        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => (int) $params['id']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['mobile'])) {
            $modelAQ->andWhere($tableName . '.mobile =:mobile', [':mobile' => $params['mobile']]);
        }
        
        if (isset($params['name'])) {
            $modelAQ->andWhere($tableName . '.name =:name', [':name' => $params['name']]);
        }

        if (isset($params['email'])) {
            $modelAQ->andWhere($tableName . '.email =:email', [':email' => $params['email']]);
        }
        
        if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant.id = applicant_post.applicant_id');
            
            if (isset($params['applicantPostId'])) {
                $modelAQ->andWhere('applicant_post.id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
            }
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }
    
    public static function findByKeys($params = [])
    {
        return self::findByParams($params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function findByMobileNumber($mobileNumber, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['mobile' => $mobileNumber], $params));
    }

    public static function findByEmail($email, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['email' => $email], $params));
    }

    protected function findModel($guid)
    {
        $model = self::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Applicant not found");
        }
        return $model;
    }
    
    public static function beforeUserLogin()
    {
        $session = Yii::$app->session;
        $session->destroy();
        $session->open();
        $session->regenerateID();

        return true;
    }

    public static function afterUserLogin(IdentityInterface $identity)
    {
        if ($identity->id > 0) {

            //Session hijacking prevention logic
            self::initSessionHijackingPreventions();
        }

        return true;
    }
    
    public static function afterUserLogout(IdentityInterface $identity)
    {
        if ($identity->id > 0) {
            
            $cookies = Yii::$app->response->cookies;
            $cookieName = "";
            if (Yii::$app->id == 'app-frontend') {
                $cookieName = self::FRONTEND_FIXATION_COOKIE;
            }
            
            if (!empty($cookieName)) {
                $cookies->remove($cookieName, true);
            }
        }
        
        return true;
    }

    public static function initSessionHijackingPreventions() {
        $appId = Yii::$app->id;
        if ($appId === 'app-frontend') {
            self::setSessionHijackingPreventions(self::FRONTEND_LOGIN_KEY, self::FRONTEND_SESSION_VALUE, self::FRONTEND_FIXATION_COOKIE);
        }
    }

    public static function setSessionHijackingPreventions($sessionKey, $sessionValue, $cookieKey)
    {
        $cookies = Yii::$app->response->cookies;

        // Create a cookie and store in session to prevent man in the middle attack
        $randomSecurityString = Yii::$app->getSecurity()->generateRandomString(32);

        // Set Network Id in session prevent session hijacking
        Yii::$app->session->set($sessionKey, $sessionValue);
        Yii::$app->session->set($cookieKey, $randomSecurityString);

        $cookieParams = [
            'name' => $cookieKey,
            'value' => $randomSecurityString,
            'expire' => time() + 7 * 24 * 60 * 60,            
            'httpOnly' => (YII_ENV == "prod") ? true : false,
            'secure' => (YII_ENV == "prod") ? true : false,
        ];

        if (Yii::$app->id == 'app-frontend') {
            $sessionObj = Yii::$app->get('session');
            $sessionCookieParams = $sessionObj->getCookieParams();
            $cookieParams['domain'] = $sessionCookieParams['domain'];
        }
        if (Yii::$app->id == 'app-backend') {
            $sessionObj = Yii::$app->get('session');
            $sessionCookieParams = $sessionObj->getCookieParams();
            $cookieParams['domain'] = $sessionCookieParams['domain'];
        }

        $cookies->add(new \yii\web\Cookie($cookieParams));

        return true;
    }

    public static function checkSessionHijackingPreventions($sessionKey, $cookieKey, $sessionValueToCheck = 1) 
    {

        $cookies = Yii::$app->request->cookies;
        $session = Yii::$app->session;
        
        // Check for seesion and cookie key 
        if(!$session->has($sessionKey) || !$session->has($cookieKey) || !$cookies->has($cookieKey)) {
            return false;
        }
        
        if ($session->get($sessionKey) != $sessionValueToCheck) {
            return false;
        }
        
        // check if cookie value and session value are same
        if($cookies->get($cookieKey)->value != $session->get($cookieKey)) {
            return false;
        }
        
        //Session hijacking prevention logic
        self::initSessionHijackingPreventions();
        
        return true;
    }
    
    public function isUserLocked()
    {
        if( (int)$this->failed_timestamp > time()) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    public static function updateFormStep($id, $step)
    {
        $applicantModel = Applicant::findById($id, [
                    'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);

        if (empty($applicantModel)) {
            return false;
        }

        try {
            $applicantModel->form_step = $step;
            $applicantModel->modified_on = time();
            $applicantModel->save(TRUE, ['form_step', 'modified_on']);
        } catch (\Exception $ex) {
            Yii::error('Application Clone Error :' . $ex->getMessage());
        }
    }
    
    public static function getRedirectionBasedOnFormStep($applicantId)
    {
        $qr = \Yii::$app->request->queryParams;
        $redirect = "/registration/personal-details";
        $model = Applicant::findById($applicantId, ['resultFormat'  => caching\ModelCache::RETURN_TYPE_OBJECT]);
        if($model->form_step == 7) {
            $applicantPost = ApplicantPost::findByApplicantId($model->id, ['notPostId' => MstPost::MASTER_POST, 'paymentStatus' => ApplicantPost::STATUS_PAID, 'countOnly' => true]);
            if ($applicantPost > 0) {

                try {
                    $model->form_step = Applicant::FORM_STEP_SUBMITTED;
                    $model->modified_on = time();
                    $model->save(true, ['form_step', 'modified_on']);
                } catch (\Exception $ex) {
                    Yii::error('Application Clone Error :' . $ex->getMessage());
                }
            }
        }
        if ($model !== null) {
            switch ($model->form_step) {
                case 1:
                    $redirect = "/registration/address-details";
                    break;
                case 2:
                    $redirect = "/registration/other-details";
                    break;
                case 3:
                    $redirect = "/registration/qualification-details";
                    break;
                case 4:
                    $redirect = "/registration/employment-details";
                    break;
                case 5:
                    $redirect = "/registration/document-details";
                    break;
                case 6:
                    $redirect = isset($qr['guid']) ?  "/registration/criteria-details": "/applicant/post";
                    break;
                case 7:
                    $redirect = isset($qr['guid']) ?  "/registration/review": "/applicant/post";
                    break;
                case 8:
                    $redirect = "/applicant/post";
                    break;
            }
        }

        return "/applicant/post";
    }
    
    public static function updateOtr($logProfileId)
    {
        if (empty($logProfileId)) {
            return false;
        }
        $logProfileModel = LogProfile::findById($logProfileId, ['resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($logProfileModel === null) {
            return false;
        }
        $applicantModel = Applicant::findById($logProfileModel->applicant_id, ['resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($applicantModel === null) {
            return false;
        }
        $applicantPostModel = ApplicantPost::findByApplicantId($logProfileModel->applicant_id, ['resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT, 'postId' => MstPost::MASTER_POST]);
        if ($applicantPostModel === null) {
            return false;
        }
        $applicantDetailModel = ApplicantDetail::findByApplicantPostId($applicantPostModel->id, ['selectCols' => ['id', 'father_name', 'date_of_birth'], 'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($applicantDetailModel === null) {
            return false;
        }

        $oldValue = [
            'name' => $applicantModel->name,
            'father_name' => $applicantDetailModel->father_name,
            'date_of_birth' => $applicantDetailModel->date_of_birth,
        ];

        $logProfileModel->old_value = \yii\helpers\Json::encode($oldValue);
        $logProfileModel->modified_by = Yii::$app->user->id;
        $logProfileModel->save(true, ['old_value', 'modified_by']);

        $newValue = \yii\helpers\Json::decode($logProfileModel->new_value);
        $applicantModel->name = isset($newValue['name']) ? $newValue['name'] : $applicantModel->name;
        $applicantModel->save(true, ['name']);

        $applicantDetailModel->father_name = isset($newValue['father_name']) ? $newValue['father_name'] : $applicantDetailModel->father_name;
        $applicantDetailModel->date_of_birth = isset($newValue['date_of_birth']) ? $newValue['date_of_birth'] : $applicantDetailModel->date_of_birth;
        $applicantDetailModel->save(true, ['father_name', 'date_of_birth']);
    }

}
