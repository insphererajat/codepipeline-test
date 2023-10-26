<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class VerifyOTPForm extends Model
{

    public $mobileOtp;
    public $mobileOtpId;
    public $mobile;
    public $email;
    public $type;
    public $applicant_id;

    const SCENARIO_VALIDATE_OTP = 'validate';
    const SCENARIO_SENT_OTP = 'sent-otp';
    const SCENARIO_VALIDATE_CANCEL_POST_OTP = 3;
    const SCENARIO_VALIDATE_ESERVICE_POST_OTP = 6;
    const SCENARIO_CHANGE_EMAIL_OTP = 'change_email_otp';
    const SCENARIO_CHANGE_MOBILE_OTP = 'change_mobile_otp';
    const SCENARIO_VALIDATE_CHANGE_REQUST_OTP = 'validate_change_request';

    public function rules()
    {
        return [
            [['applicant_id'], 'integer'],
            [['mobileOtp'], 'required', 'message' => '{attribute} is required.'],
            [['mobileOtp'], 'string', 'max' => 6],
            [['mobileOtpId'], 'integer'],
            [['mobileOtp'], 'match', 'pattern' => '/^[1-9][0-9]{5}$/', 'message' => 'Invalid otp.'],
            [['mobileOtp'], 'verifyMobileOtp', 'on' => self::SCENARIO_VALIDATE_OTP],
            [['mobileOtp'], 'verifyCancelPostOtp', 'on' => [self::SCENARIO_VALIDATE_CANCEL_POST_OTP, self::SCENARIO_VALIDATE_ESERVICE_POST_OTP]],
            ['email', 'email'],
            [['email'], 'required', 'message' => '{attribute} is required.', 'on' => self::SCENARIO_CHANGE_EMAIL_OTP],
            [['mobile'], 'string', 'min' => 10, 'max' => 10],
            [['mobile'], 'match', 'pattern' => '/[6789][0-9]{9}/', 'on' => self::SCENARIO_CHANGE_MOBILE_OTP],
            [['mobile'], 'required', 'message' => '{attribute} is required.', 'on' => self::SCENARIO_CHANGE_MOBILE_OTP],
            [['mobileOtp'], 'verifyChangeRequestOtp', 'on' => self::SCENARIO_VALIDATE_CHANGE_REQUST_OTP],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mobileOtp' => 'Otp'
        ];
    }

    public function verifyMobileOtp($attribute, $params, $validator)
    {
        if (!empty($this->mobileOtpId) && !empty($this->mobileOtp)) {
            $params = [
                'id' => $this->mobileOtpId,
                'otp' => $this->mobileOtp,
                'type' => \common\models\LogOtp::MOBILE_OTP,
                'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT,
                'isVerified' => \common\models\LogOtp::VERIFIED
            ];

            $model = \common\models\LogOtp::findByOtpIdAndOtp($params);
            if ($model === null) {
                $params['isVerified'] = \common\models\LogOtp::NOT_VERIFIED;
                if (!\common\models\LogOtp::validateOtp($params)) {
                    $this->addError('mobileotp', 'Mobile otp is not valid');
                }
            }
            else 
            {
                $this->addError('mobileotp', 'Otp is not valid.');
                return false;
            }
        }
    }
    
    public function verifyCancelPostOtp($attribute, $params, $validator)
    {
        if (!empty($this->mobileOtpId) && !empty($this->mobileOtp)) {
            $params = [
                'id' => $this->mobileOtpId,
                'otp' => $this->mobileOtp,
                'type' => $this->getScenario(),
                'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT,
                'isVerified' => \common\models\LogOtp::VERIFIED
            ];

            $model = \common\models\LogOtp::findByOtpIdAndOtp($params);
            if ($model === null) {
                $params['isVerified'] = \common\models\LogOtp::NOT_VERIFIED;
                if (!\common\models\LogOtp::validateOtp($params)) {
                    $this->addError('mobileotp', 'Mobile otp is not valid');
                }
            }
            else 
            {
                $this->addError('mobileotp', 'Otp is not valid.');
                return false;
            }
        }
    }
    
    public function verifyChangeRequestOtp($attribute, $params, $validator)
    {
        if (!empty($this->mobileOtpId) && !empty($this->mobileOtp)) {
            $params = [
                'id' => $this->mobileOtpId,
                'otp' => $this->mobileOtp,
                'type' => $this->type,
                'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT,
                'isVerified' => \common\models\LogOtp::VERIFIED
            ];

            $model = \common\models\LogOtp::findByOtpIdAndOtp($params);
            if ($model === null) {
                $params['isVerified'] = \common\models\LogOtp::NOT_VERIFIED;
                if (!\common\models\LogOtp::validateOtp($params)) {
                    $this->addError('mobileotp', 'OTP is not valid');
                }
            }
            else 
            {
                $this->addError('mobileotp', 'Otp is not valid.');
                return false;
            }
        }
    }
    
    public static function scenario($key = null)
    {
        $list = [
            \common\models\LogOtp::CANCEL_POST_OTP => self::SCENARIO_VALIDATE_CANCEL_POST_OTP,
            \common\models\LogOtp::ESERVICE_POST_OTP => self::SCENARIO_VALIDATE_ESERVICE_POST_OTP
        ];
        
        return isset($list[$key]) ? $list[$key] : $list;
    }

}
