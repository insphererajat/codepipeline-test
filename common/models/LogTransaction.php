<?php

namespace common\models;

use common\models\base\LogTransaction as BaseLogTransaction;

/**
 * This is the model class for table "log_otp".
 *
 * @author Amit Handa
 */
class LogTransaction extends BaseLogTransaction
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on']
                ]
            ]
        ];
    }

    public function saveLog($params)
    {
        $logTransation = new \common\models\LogTransaction();
        $logTransation->gateway_id = (string)$params['gateway_id'];
        $logTransation->transaction_id = $params['transaction_id'];
        $logTransation->response_amount = $params['response_amount'];
        $logTransation->status = $params['status'];
        $logTransation->response = $params['response'];
        $logTransation->save();
    }
}