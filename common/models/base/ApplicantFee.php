<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_fee".
 *
 * @property int $id
 * @property int $applicant_id
 * @property int $applicant_post_id
 * @property string $module
 * @property float $fee_amount
 * @property int $payment_mode
 * @property string|null $other_details
 * @property int $status
 * @property int|null $created_on
 * @property int|null $created_by
 *
 * @property ApplicantPost $applicantPost
 * @property Applicant $applicant
 * @property Applicant $createdBy
 * @property Transaction[] $transactions
 */
class ApplicantFee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'applicant_post_id', 'module', 'fee_amount'], 'required'],
            [['applicant_id', 'applicant_post_id', 'payment_mode', 'status', 'created_on', 'created_by'], 'integer'],
            [['module'], 'string'],
            [['fee_amount'], 'number'],
            [['other_details'], 'string', 'max' => 255],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant_id' => Yii::t('app', 'Applicant ID'),
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'module' => Yii::t('app', 'Module'),
            'fee_amount' => Yii::t('app', 'Fee Amount'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'other_details' => Yii::t('app', 'Other Details'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[ApplicantPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPost()
    {
        return $this->hasOne(ApplicantPost::className(), ['id' => 'applicant_post_id']);
    }

    /**
     * Gets query for [[Applicant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['applicant_fee_id' => 'id']);
    }
}
