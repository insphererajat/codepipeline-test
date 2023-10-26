<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_transaction".
 *
 * @property int $id
 * @property string|null $gateway_id
 * @property int $transaction_id
 * @property float|null $response_amount
 * @property string|null $status
 * @property string|null $response
 * @property int|null $created_on
 *
 * @property Transaction $transaction
 */
class LogTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id'], 'required'],
            [['transaction_id', 'created_on'], 'integer'],
            [['response_amount'], 'number'],
            [['response'], 'string'],
            [['gateway_id'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::className(), 'targetAttribute' => ['transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'gateway_id' => Yii::t('app', 'Gateway ID'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'response_amount' => Yii::t('app', 'Response Amount'),
            'status' => Yii::t('app', 'Status'),
            'response' => Yii::t('app', 'Response'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }

    /**
     * Gets query for [[Transaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }
}
