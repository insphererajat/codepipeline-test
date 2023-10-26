<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string|null $gateway_id
 * @property string $transaction_id
 * @property int $applicant_id
 * @property int $applicant_fee_id
 * @property string $type
 * @property float $amount
 * @property float|null $response_amount
 * @property string|null $status
 * @property int|null $is_consumed
 * @property string|null $response
 * @property string|null $requested_data
 * @property int|null $sqs_job_id
 * @property int|null $is_processed
 * @property string|null $failed_msg
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantFee $applicantFee
 * @property Applicant $applicant
 * @property SqsJob $sqsJob
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'applicant_id', 'applicant_fee_id', 'type', 'amount'], 'required'],
            [['applicant_id', 'applicant_fee_id', 'is_consumed', 'sqs_job_id', 'is_processed', 'created_on', 'modified_on'], 'integer'],
            [['type', 'response', 'requested_data', 'failed_msg'], 'string'],
            [['amount', 'response_amount'], 'number'],
            [['gateway_id', 'transaction_id'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['transaction_id'], 'unique'],
            [['gateway_id'], 'unique'],
            [['applicant_fee_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantFee::className(), 'targetAttribute' => ['applicant_fee_id' => 'id']],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['sqs_job_id'], 'exist', 'skipOnError' => true, 'targetClass' => SqsJob::className(), 'targetAttribute' => ['sqs_job_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gateway_id' => 'Gateway ID',
            'transaction_id' => 'Transaction ID',
            'applicant_id' => 'Applicant ID',
            'applicant_fee_id' => 'Applicant Fee ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'response_amount' => 'Response Amount',
            'status' => 'Status',
            'is_consumed' => 'Is Consumed',
            'response' => 'Response',
            'requested_data' => 'Requested Data',
            'sqs_job_id' => 'Sqs Job ID',
            'is_processed' => 'Is Processed',
            'failed_msg' => 'Failed Msg',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
        ];
    }

    /**
     * Gets query for [[ApplicantFee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantFee()
    {
        return $this->hasOne(ApplicantFee::className(), ['id' => 'applicant_fee_id']);
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
     * Gets query for [[SqsJob]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSqsJob()
    {
        return $this->hasOne(SqsJob::className(), ['id' => 'sqs_job_id']);
    }
}
