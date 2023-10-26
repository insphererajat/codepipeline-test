<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use common\models\caching\ModelCache;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $reCaptcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Applicant',
                'filter' => ['is_active' => ModelCache::IS_ACTIVE_YES],
                'message' => 'If this email is present in our system then OTP will be sent to you.'
            ],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $applicant Applicant */
        $applicant = Applicant::findOne([
            'is_active' => ModelCache::IS_ACTIVE_YES,
            'email' => $this->email,
        ]);

        if (!$applicant) {
            return false;
        }
        
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $applicant->password_reset_token = (string) mt_rand(100000, 1000000);
        $applicant->password_reset_token_expiry_at = time() + $expire;
        if (!$applicant->save()) {
            return false;
        }
        
        \Yii::$app->sms->sendOtp($applicant->mobile, ['otp' => $applicant->password_reset_token]);
        \Yii::$app->email->forgotPasswordEmail($applicant->email, $applicant->name, $applicant->password_reset_token);
        return true;
    }
}
