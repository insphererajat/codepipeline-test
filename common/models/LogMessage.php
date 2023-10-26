<?php

namespace common\models;

use Yii;
use common\models\base\LogMessage as BaseLogMessage;

/**
 * This is the model class for table "log_message".
 *
 * @author Amit Handa
 */
class LogMessage extends BaseLogMessage
{
    const TYPE_EMAIL = 'EMAIL';
    const TYPE_SMS = 'SMS';
    const TYPE_NOTIFICATION = 'NOTIFICATION';
    
    public static function getTypeDropdown($key = null)
    {
        $list = [self::TYPE_EMAIL => 'Email', self::TYPE_SMS => 'SMS', self::TYPE_NOTIFICATION => 'Notification'];
        return isset($list[$key]) ? $list[$key] : $list;
    }
    
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
                ],
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
            $modelAQ->andWhere($tableName . '.type =:type', [':type' => $params['type']]);
        }
        
        if (isset($params['toApplicantId'])) {
            $modelAQ->andWhere('log_message.to_applicant_id =:toApplicantId', [':toApplicantId' => $params['toApplicantId']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByKeys($params = [])
    {
        return self::findByParams($params);
    }
    
    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }
    
    public function createLogMessage($data)
    {
        try {
            $model = new LogMessage;
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if (!$model->save()) {
                return FALSE;
            }
        }
        catch (\Exception $ex) {
            return FALSE;
        }
        return $model->id;
    }

}