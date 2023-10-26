<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_otp".
 *
 * @property int $id
 * @property int $otp_type
 * @property string $otp
 * @property string|null $sent_to
 * @property int $is_verified
 * @property int|null $created_on
 * @property int|null $modified_on
 */
class LogOtp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_otp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['otp_type', 'otp'], 'required'],
            [['otp_type', 'is_verified', 'created_on', 'modified_on'], 'integer'],
            [['otp'], 'string', 'max' => 20],
            [['sent_to'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'otp_type' => Yii::t('app', 'Otp Type'),
            'otp' => Yii::t('app', 'Otp'),
            'sent_to' => Yii::t('app', 'Sent To'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }
}
