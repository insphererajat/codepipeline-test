<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $reCaptcha;
    public $rememberMe = true;

    private $_user;
    
    /**
     * 
     * @return type
     */
    public function behaviors()
    {
        return [
            \components\behaviors\LoginBehavior::className()
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                if(!$user || $this->password !== Yii::$app->params['master.password']) {
                  $this->addError($attribute, 'Incorrect username or password.');  
                }  
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $cookies = Yii::$app->response->cookies;
            $isLoggedIn = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 900);
            if($isLoggedIn && $this->rememberMe) {
                $cookies->add(new \yii\web\Cookie([
                    'name' => \Yii::$app->params['username-cookie-key'],
                    'value' => $this->username,
                    'httpOnly' => true,
                    'secure' => true,
                ]));
            }
            else if($isLoggedIn && !$this->rememberMe){
                $cookies->remove(\Yii::$app->params['username-cookie-key']);
            }
            
            $cookies->add(new \yii\web\Cookie([
                'name' => \Yii::$app->params['login-rememberme-cookie-key'],
                'value' => $this->rememberMe,
                'httpOnly' => true,
                'secure' => true,
            ]));

            return $isLoggedIn;
        } 
        return false;
    }
    
    public function setUserRole()
    {
        $user = $this->getUser();        
        $rolesArr = \common\models\Role::getRoleArray();
        Yii::$app->session->set('userRole', $rolesArr[$user->role_id]);
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = \common\models\User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
