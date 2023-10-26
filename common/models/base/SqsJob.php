<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "sqs_job".
 *
 * @property int $id
 * @property string|null $job_id
 * @property string $tube
 * @property string $data
 * @property string|null $logs
 * @property int $is_completed
 * @property int|null $completed_at
 * @property int|null $created_at
 *
 * @property Transaction[] $transactions
 */
class SqsJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sqs_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tube', 'data'], 'required'],
            [['data', 'logs'], 'string'],
            [['is_completed', 'completed_at', 'created_at'], 'integer'],
            [['job_id'], 'string', 'max' => 255],
            [['tube'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'job_id' => Yii::t('app', 'Job ID'),
            'tube' => Yii::t('app', 'Tube'),
            'data' => Yii::t('app', 'Data'),
            'logs' => Yii::t('app', 'Logs'),
            'is_completed' => Yii::t('app', 'Is Completed'),
            'completed_at' => Yii::t('app', 'Completed At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['sqs_job_id' => 'id']);
    }
}
