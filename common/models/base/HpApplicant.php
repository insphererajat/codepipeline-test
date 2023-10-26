<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "hp_applicant".
 *
 * @property int $id
 * @property string $guid
 * @property int $network_id
 * @property string|null $auth_key
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property int|null $password_reset_token_expiry_on
 * @property string $email
 * @property string $name
 * @property int $mobile
 * @property string|null $password_hash1
 * @property string|null $password_hash2
 * @property string|null $password_hash3
 * @property int $failed_attempt
 * @property int|null $failed_timestamp
 * @property int|null $is_email_verified
 * @property int|null $is_mobile_verified
 * @property int|null $form_step
 * @property int|null $is_active
 * @property int|null $is_deleted
 * @property int|null $created_on
 * @property int|null $modified_on
 * @property string $user_id
 */
class HpApplicant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hp_applicant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'network_id', 'email', 'name', 'mobile', 'user_id'], 'required'],
            [['network_id', 'password_reset_token_expiry_on', 'mobile', 'failed_attempt', 'failed_timestamp', 'is_email_verified', 'is_mobile_verified', 'form_step', 'is_active', 'is_deleted', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email', 'name', 'password_hash1', 'password_hash2', 'password_hash3'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 100],
            [['user_id'], 'unique'],
            [['email'], 'unique'],
            [['mobile'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'guid' => Yii::t('app', 'Guid'),
            'network_id' => Yii::t('app', 'Network ID'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'password_reset_token_expiry_on' => Yii::t('app', 'Password Reset Token Expiry On'),
            'email' => Yii::t('app', 'Email'),
            'name' => Yii::t('app', 'Name'),
            'mobile' => Yii::t('app', 'Mobile'),
            'password_hash1' => Yii::t('app', 'Password Hash1'),
            'password_hash2' => Yii::t('app', 'Password Hash2'),
            'password_hash3' => Yii::t('app', 'Password Hash3'),
            'failed_attempt' => Yii::t('app', 'Failed Attempt'),
            'failed_timestamp' => Yii::t('app', 'Failed Timestamp'),
            'is_email_verified' => Yii::t('app', 'Is Email Verified'),
            'is_mobile_verified' => Yii::t('app', 'Is Mobile Verified'),
            'form_step' => Yii::t('app', 'Form Step'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
}
