<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant".
 *
 * @property int $id
 * @property string $guid
 * @property string|null $auth_key
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property int|null $password_reset_token_expiry_at
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
 *
 * @property ApplicantCriteria[] $applicantCriterias
 * @property ApplicantFee[] $applicantFees
 * @property ApplicantFee[] $applicantFees0
 * @property ApplicantPost[] $applicantPosts
 * @property LogApplicant[] $logApplicants
 * @property LogApplicant[] $logApplicants0
 * @property LogProfile[] $logProfiles
 * @property LogProfileActivity[] $logProfileActivities
 * @property LogProfileMedia[] $logProfileMedia
 * @property LogUserActivity[] $logUserActivities
 * @property Transaction[] $transactions
 */
class Applicant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'email', 'name', 'mobile'], 'required'],
            [['password_reset_token_expiry_at', 'mobile', 'failed_attempt', 'failed_timestamp', 'is_email_verified', 'is_mobile_verified', 'form_step', 'is_active', 'is_deleted', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email', 'name', 'password_hash1', 'password_hash2', 'password_hash3'], 'string', 'max' => 255],
            [['mobile'], 'unique'],
            [['email'], 'unique'],
            [['guid'], 'unique'],
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
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'password_reset_token_expiry_at' => Yii::t('app', 'Password Reset Token Expiry At'),
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
        ];
    }

    /**
     * Gets query for [[ApplicantCriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriterias()
    {
        return $this->hasMany(ApplicantCriteria::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantFees()
    {
        return $this->hasMany(ApplicantFee::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantFees0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantFees0()
    {
        return $this->hasMany(ApplicantFee::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ApplicantPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPosts()
    {
        return $this->hasMany(ApplicantPost::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[LogApplicants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogApplicants()
    {
        return $this->hasMany(LogApplicant::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[LogApplicants0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogApplicants0()
    {
        return $this->hasMany(LogApplicant::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[LogProfiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfiles()
    {
        return $this->hasMany(LogProfile::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[LogProfileActivities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfileActivities()
    {
        return $this->hasMany(LogProfileActivity::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[LogProfileMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfileMedia()
    {
        return $this->hasMany(LogProfileMedia::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[LogUserActivities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogUserActivities()
    {
        return $this->hasMany(LogUserActivity::className(), ['applicant_id' => 'id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['applicant_id' => 'id']);
    }
}
