<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Applicant;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $id;
    public $otp;
    public $password;
    public $verifypassword;
    public $reCaptcha;

    private $_applicant;
    
    public function behaviors()
    {
        return [
            \components\behaviors\ApplicantPasswordLogBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['otp', 'password', 'verifypassword'], 'required'],
            ['otp', 'validateOtp'],
            ['otp', 'string', 'min' => 6, 'max' => 6, 'message' => 'Otp should contain at least 6 digit.'],
            ['verifypassword', 'compare', 'compareAttribute' => 'password'],
            ['password', 'string', 'min' => 6, 'max' => 50],
            ['password', 'match', 'pattern' => '/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/', 'message' => Yii::t('app', 'password_validation')],
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
    public function validateOtp($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $applicant = $this->getApplicant($this->otp);
            if (!$applicant) {
                $this->addError($attribute, 'Incorrect OTP.');
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
        $applicant = $this->getApplicant($this->otp);
        $applicant->setPassword($this->password);
        $applicant->removePasswordResetToken();
        return $applicant->save(true);
    }

    /**
     * Finds student by [[username number]]
     *
     * @return student|null
     */
    protected function getApplicant($otp)
    {
        if ($this->_applicant === null) {
            $this->_applicant = Applicant::findByPasswordResetToken($otp);
        }
        return $this->_applicant;
    }
    
    public function attributeLabels()
    {
        return [
            'verifypassword' => 'Vrify Password',
        ];
    }

}
