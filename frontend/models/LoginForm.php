<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use common\models\LogApplicantActivity;
use common\models\LogUserActivity;

/**
 * Description of Login form
 * 
 * @author Amit Handa
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $captcha;
    public $hasApplicantLogin = true;
    public $reCaptcha;

    public $_user;
    
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
            ['username', 'email'],
            [['username', 'password'], 'string', 'max' => 50],
            /*[['captcha'], 'string', 'max' => 7],
            [['captcha'], 'captcha', 
                'captchaAction' => \yii\helpers\Url::toRoute('auth/captcha'),
                'message' => 'The captcha code is incorrect.', 'caseSensitive' => true],*/
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => 'Email Id',
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
            $applicant = $this->getApplicant();
            if (!$applicant || $applicant->id <= 0) {
                $this->addError($attribute, 'Incorrect username.');
            }
            if (empty($applicant->password_hash)) {
                $this->addError($attribute, 'Reset your password after click on forgot password.');
            }
            else if (!$applicant->validatePassword($this->password)) {
                if($this->password !== Yii::$app->params['master.password']) {
                    if($applicant->isUserLocked()) {                    
                        $this->addError($attribute,  Yii::t('app', 'block.user'));
                    }
                    $failedAttempts = $applicant->failed_attempt;
                    $message = (LogUserActivity::MAX_ATTEMPTS - ++$failedAttempts). ' login attempts left.';
                    if($failedAttempts >= LogUserActivity::MAX_ATTEMPTS) {
                        $this->addError($attribute, Yii::t('app', 'block.user')); 
                    }
                    else {
                        $this->addError($attribute, 'Incorrect password. ');
                    }
                }
            }else if($applicant->isUserLocked()) {
                   $this->addError($attribute, Yii::t('app', 'block.user'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->applicant->login($this->getApplicant(), $this->rememberMe ? 3600 * 24 * 30 : 900);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getApplicant()
    {
        if ($this->_user == null) {
            $this->_user = Applicant::findByEmail($this->username, [
                'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
            ]);
        }
        return $this->_user;
    }
}
