<?php

namespace common\models;

use Yii;
use common\models\base\LogApplicant as BaseLogApplicant;

/**
 * This is the model class for table "log_applicant".
 *
 * @author Amit Handa
 */
class LogApplicant extends BaseLogApplicant
{

    const TYPE_MOBILE = 'MOBILE';
    const TYPE_EMAIL = 'EMAIL';
    const TYPE_NAME = 'NAME';
    // Device Type
    const MOBILE_DEVICE = 'Mobile';
    const DESKTOP_DEVICE = 'Desktop';
    const TAB_DEVICE = 'Tablet';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on'],
                ],
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['device_type', 'ip_address', 'useragent']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'device_type' => 'cleanEncodeUTF8',
                        'ip_address' => 'cleanEncodeUTF8',
                        'useragent' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'device_type' => 'cleanEncodeUTF8',
                        'ip_address' => 'cleanEncodeUTF8',
                        'useragent' => 'cleanEncodeUTF8',
                    ]
                ]
            ],
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

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }
    
    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }
    
    public function createLogApplicant($data)
    {
        try {
            $model = new LogApplicant;
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
    
    /**
     * Get the device type via mobileDetect component.
     * @return type
     */
    public static function getDeviceType()
    {
        if (Yii::$app->mobileDetect->isMobile()) {
            return self::MOBILE_DEVICE;
        }
        else if (Yii::$app->mobileDetect->isTablet()) {
            return self::TAB_DEVICE;
        }
        else {
            return self::DESKTOP_DEVICE;
        }
    }
}
