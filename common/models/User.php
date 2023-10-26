<?php

namespace common\models;

use Yii;
use common\models\base\User as BaseUser;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends BaseUser implements IdentityInterface
{
    
    const BACKEND_LOGIN_KEY = 'backend_login_authentication';
    const BACKEND_FIXATION_COOKIE = 'backend_fixation_cookie';
    const BACKEND_SESSION_VALUE = 9870011;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;
    const SEARCH_BY_USERNAME = 'username';
    const SEARCH_BY_EMAIL = 'email';
    const USERNAME_REGEX = '^a-zA-Z0-9_-';

    public $reservedUsernames = ['admintest'];
    public $password;
    public $verifypassword;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_on', 'modified_on'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            [
                'class' => \components\behaviors\GuidBehavior::className(),
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['username', 'auth_key', 'password_hash', 'password_reset_token', 'firstname', 'lastname', 'email', 'verification_token']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'username' => 'cleanEncodeUTF8',
                        'auth_key' => 'cleanEncodeUTF8',
                        'password_hash' => 'cleanEncodeUTF8',
                        'password_reset_token' => 'cleanEncodeUTF8',
                        'firstname' => 'cleanEncodeUTF8',
                        'lastname' => 'cleanEncodeUTF8',
                        'email' => 'cleanEncodeUTF8',
                        'verification_token' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'username' => 'cleanEncodeUTF8',
                        'auth_key' => 'cleanEncodeUTF8',
                        'password_hash' => 'cleanEncodeUTF8',
                        'password_reset_token' => 'cleanEncodeUTF8',
                        'firstname' => 'cleanEncodeUTF8',
                        'lastname' => 'cleanEncodeUTF8',
                        'email' => 'cleanEncodeUTF8',
                        'verification_token' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function beforeValidate()
    {

        $username = $this->username;
        $checkUsername = FALSE;

        //if no username, then lets create one
        if (empty($username)) {
            $username = str_replace(' ', '', strtolower($this->firstname . $this->lastname));
            $checkUsername = TRUE;
        }

        if (\yii\helpers\ArrayHelper::isIn($username, $this->reservedUsernames)) {
            $username .= \Yii::$app->security->generateRandomString(4);
            $checkUsername = TRUE;
        }

        if ($checkUsername) {
            $usernameTruncated = strlen($username) > 45 ? substr($username, 0, 45) : $username;
            while (true) {
                $username = $usernameTruncated . rand(1000, 9999);
                $username = self::cleanUsername($username);

                $userModel = User::find()->where('username = :username', [':username' => $username]);
                if ($this->id > 0) {
                    $userModel->andWhere('id != :userId', [':userId' => $this->id]);
                }
                $exists = $userModel->exists();
                if (!$exists) {
                    $this->username = $username;
                    break;
                }
            }
        }

        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        //for new record only
        if ($this->id <= 0) {
            //Username & email should be unique across the whole system
            if (!empty($this->username)) {
                $existsAlready = User::find()
                    ->where('username=:username', [':username' => $this->username])
                    ->exists();

                // commented for demo
                if ($existsAlready) {
                    $this->addError('username', 'This username has already been taken.');
                    return FALSE;
                }
            }
            if (!empty($this->email)) {
                $emailExistsAlready = User::find()
                    ->where('email=:email', [':email' => $this->email])
                    ->exists();
                if ($emailExistsAlready) {
                    $this->addError('email', 'Email already exists. Please provide another email.');
                    return FALSE;
                }
            }
        }

        return parent::afterValidate();
    }

    public static function cleanUsername($username)
    {
        return preg_replace("/[" . self::USERNAME_REGEX . "]/", '', strtolower($username));
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findByParams(['auth_key' => $token, 'resultFormat' => self::RETURN_TYPE_OBJECT]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
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
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    private static function findByParams($params = [])
    {
        $modelAQ = User::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select('user.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere('user.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere('user.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['email'])) {
            $modelAQ->andWhere('user.email =:email', [':email' => $params['email']]);
        }

        if (isset($params['username'])) {
            $modelAQ->andWhere('user.username =:username', [':username' => $params['username']]);
        }

        if (isset($params['notAdminRole'])) {
            $modelAQ->andWhere('user.role_id !=:notAdminRole', [':notAdminRole' => $params['notAdminRole']]);
        }

        if (isset($params['roleId'])) {
            if (is_array($params['roleId'])) {
                $modelAQ->andWhere(['in', 'user_role.role_id', $params['roleId']]);
            } else {
                $modelAQ->andWhere('user_role.role_id =:roleId', [':roleId' => $params['roleId']]);
            }
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    /**
     * Assign passed attributes & save user record
     * @param array $attributes
     * @return boolean
     */
    public function saveUser($attributes)
    {
        if (empty($attributes)) {
            return FALSE;
        }

        //new user
        if (!isset($attributes['id']) || (int) $attributes['id'] <= 0) {
            $this->loadDefaultValues(TRUE);
            $this->generateAuthKey();
            $this->attributes = $attributes;
            $this->setAttribute('status', User::STATUS_ACTIVE);
            $this->setPassword($attributes['password']);
        }
        //existing user
        else if ((int) $attributes['id'] > 0) {

            $this->setAttribute('firstname', $attributes['firstname']);
            $this->setAttribute('lastname', $attributes['lastname']);
            if (isset($attributes['username']) && !empty($attributes['username'])) {
                $this->setAttribute('username', $attributes['username']);
            }
            if (isset($attributes['email']) && !empty($attributes['email'])) {
                $this->setAttribute('email', $attributes['email']);
            }
            if (isset($attributes['status']) && in_array($attributes['status'], [self::STATUS_INACTIVE, self::STATUS_ACTIVE])) {
                $this->setAttribute('status', (int) $attributes['status']);
            }

            if (isset($attributes['role_id'])) {
                $this->setAttribute('role_id', (int) $attributes['role_id']);
            }

            if (isset($attributes['password']) && !empty($attributes['password'])) {
                $this->setPassword($attributes['password']);
            }
            if (isset($attributes['profile_media_id']) && !empty($attributes['profile_media_id'])) {
                $this->setAttribute('profile_media_id', $attributes['profile_media_id']);
            }
        }

        if ($this->validate() && $this->save()) {
            return $this;
        }

        return FALSE;
    }
    
    public static function getUserByEmailOrUsername($usernameOrEmail, $field = self::SEARCH_BY_USERNAME) 
    {
        $column = ($field == self::SEARCH_BY_USERNAME) ? 'user.username' : 'user.email';
        return self::find()
                        ->innerJoin("role", "role.id = user.role_id")
                        ->where("$column = :usernameOrEmail", [":usernameOrEmail" => $usernameOrEmail])
                        ->andWhere("user.status = :userStatus", [":userStatus" => self::STATUS_ACTIVE])
                        ->andWhere("user.is_deleted = :userDeleted", [":userDeleted" => caching\ModelCache::IS_DELETED_NO])
                          ->one();
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function findByRoleId($roleId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['roleId' => $roleId], $params));
    }
    
    public static function getUserDropdown($params =[])
    {
        $queryParams = [
            'isDeleted' => 0,
            'resultCount' => caching\ModelCache::RETURN_ALL,
        ];
        $users = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));

        $list = [];
        foreach ($users as $value) {
            $list[$value['id']] = $value['firstname'].' '.$value['lastname'].'('.$value['username'].')';
        }
        return $list;
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
    
    public static function initSessionHijackingPreventions() {
        $appId = Yii::$app->id;
        if ($appId === 'app-backend') {
            self::setSessionHijackingPreventions(self::BACKEND_LOGIN_KEY, self::BACKEND_SESSION_VALUE, self::BACKEND_FIXATION_COOKIE);
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
            'secure' => (YII_ENV == "prod") ? true : false
        ];

        if (Yii::$app->id == 'app-backend') {
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
            if (Yii::$app->id == 'app-backend') {
                $cookieName = self::BACKEND_FIXATION_COOKIE;
            }
            
            if (!empty($cookieName)) {
                $cookies->remove($cookieName, true);
            }
        }
        
        return true;
    }
    
    public function beforeSave($insert)
    {
        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = strip_tags($this->{$key});
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        $this->password_reset_token = !empty($this->password_reset_token) ? $this->password_reset_token : NULL;
        return parent::beforeSave($insert);
    }
    
    public function afterFind()
    {
        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = htmlentities($attribute);
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        parent::afterFind();
    }
}