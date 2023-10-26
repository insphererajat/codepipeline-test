<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use common\models\LogPassword;

/**
 * Password reset form
 */
class ChangePasswordForm extends Model
{
    public $id;
    public $current_password;
    public $password;
    public $verifypassword;
    public $reCaptcha;
    
    public function behaviors()
    {
        return [
            \components\behaviors\ApplicantPasswordLogBehavior::className()
        ];
    }

    /**
     * @var \common\models\Applicant
     */
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['current_password', 'password', 'verifypassword'], 'required'],
            ['verifypassword', 'compare', 'compareAttribute' => 'password'],
            ['password', 'match', 'pattern' => \components\Helper::passwordRegex(), 'message' => Yii::t('app', 'password_validation')],
            ['current_password', 'validatePassword'],
            [['password', 'verifypassword'], 'string', 'max' => 50],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'current_password' => 'Current Password',
            'password' => 'New Password',
            'verifypassword' => 'Vrify Password',
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
            $user = $this->getApplicant();
            if (!$user || !$user->validatePassword($this->current_password)) {
                $this->addError($attribute, 'Incorrect Current Password.');
            }
        }
    }
    
    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->getApplicant();
        $user->setPassword($this->password);
        return $user->save(true);
    }
    
    /**
     * Finds student by [[username number]]
     *
     * @return student|null
     */
    protected function getApplicant()
    {
        if ($this->_user === null) {
            $this->_user = Applicant::findIdentity(Yii::$app->applicant->id);
        }
        return $this->_user;
    }
}
