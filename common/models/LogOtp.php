<?php

namespace common\models;

use Yii;
use common\models\base\LogOtp as BaseLogOtp;
use common\models\caching\ModelCache;

/**
 * This is the model class for table "log_otp".
 *
 * @author Amit Handa
 */
class LogOtp extends BaseLogOtp
{

    const EMAIL_OTP = 1;
    const MOBILE_OTP = 2;
    const CANCEL_POST_OTP = 3;
    const CHANGE_EMAIL_OTP = 4;
    const CHANGE_MOBILE_OTP = 5;
    const ESERVICE_POST_OTP = 6;
    const VERIFIED = 1;
    const NOT_VERIFIED = 0;
    const VALIDATION_TIME = 5;

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
                ]
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['otp']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'otp' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'otp' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['type'])) {
            $modelAQ->andWhere($tableName . '.otp_type =:type', [':type' => $params['type']]);
        }
        
        if (isset($params['sentTo'])) {
            $modelAQ->andWhere($tableName . '.sent_to =:sentTo', [':sentTo' => $params['sentTo']]);
        }

        if (isset($params['otp'])) {
            $modelAQ->andWhere($tableName . '.otp =:otp', [':otp' => $params['otp']]);
        }

        if (isset($params['isVerified'])) {
            $modelAQ->andWhere($tableName . '.is_verified =:isVerified', [':isVerified' => $params['isVerified']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByOtpIdAndOtp($params = [])
    {
        return self::findByParams($params);
    }

    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }

    public static function generateOtp($type = self::EMAIL_OTP, $sendTo = NULL)
    {
        try {
            $model = LogOtp::findByType($type, [
                        'sentTo' => $sendTo,
                        'isVerified' => LogOtp::NOT_VERIFIED,
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);

            if ($model !== null) {
                
                $otpTime = self::VALIDATION_TIME;
                $currentTime = time();
                $createdTime = $model->created_on;
                $diff = round(abs($currentTime - $createdTime) / 60, 2);
                if ($diff < $otpTime) {
                    throw new \components\exceptions\AppException("Please wait for " . ($otpTime - $diff) . ' minutes.');
                }
            }

            if ($model === null) {
                $model = new LogOtp;
                $model->otp_type = $type;
                $model->otp = (string) mt_rand(100000, 1000000);
                $model->sent_to = (string) $sendTo;
            }

            $model->created_on = time();
            $model->modified_on = time();
            if (!$model->save()) {
                throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($model->errors));
            }
        } catch (\Exception $ex) {

            throw $ex;
        }
        return $model;
    }

    public static function validateOtp($params = [])
    {
        
        if (empty($params)) {
            throw new \components\exceptions\AppException('Invalid Args Supplied');
        }

        $otpDetail = self::findByOtpIdAndOtp($params);
        
        if (!empty($otpDetail)) {
            $currentTime = time();
            $createdTime = $otpDetail->created_on;
            $diff = round(abs($currentTime - $createdTime) / 60, 2);
            if ($diff > self::VALIDATION_TIME) {
                return false;
            }
            if ($otpDetail->otp == $params['otp']) {
                $otpDetail->is_verified = 1;
                $otpDetail->save(false);
                return true;
            }
            return false;
        }

        return false;
    }

}
