<?php

namespace common\models;

use Yii;
use common\models\base\MstMessageTemplate as BaseMstMessageTemplate;

class MstMessageTemplate extends BaseMstMessageTemplate
{
    
    const TEMPLATE_EMAIL = 'EMAIL';
    const TEMPLATE_SMS = 'SMS';
    const TEMPLATE_DOC_REASON = 'DOC';

    const SERVICE_OTP = 'OTP';
    const SERVICE_LOGIN_DETAIL = 'LOGIN';
    const SERVICE_FORGOT_PASSWORD = 'FORGOT_PASSWORD';
    const SERVICE_CANCEL_POST_OTP = 'CANCEL_POST';
    const SERVICE_PAYMENT_SUCCESS = 'PAYMENT_SUCCESS';
    const SERVICE_CHANGE_REQUEST_OTP = 'CHANGE_REQUEST_OTP';
    const SERVICE_ESERVICE_OTP = 'ESERVICE_OTP';
    //Admit card
    const SERVICE_ADMIT_CARD = 'ADMIT_CARD';
    const SERVICE_ADMIT_CARD_1 = 'ADMIT_CARD_1';
    const SERVICE_ADMIT_CARD_2 = 'ADMIT_CARD_2';
    const SERVICE_INTERVIEW = 'INTERVIEW';
    const SERVICE_TYPING_TEST_JOA = 'TYPING_TEST_JOA';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['title', 'service']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'title' => 'cleanEncodeUTF8',
                        'service' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'title' => 'cleanEncodeUTF8',
                        'service' => 'cleanEncodeUTF8'
                    ]
                ]
            ],
            \components\behaviors\GuidBehavior::className()
        ];
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        (new caching\ModelCache(self::className(), ['forceCache' => true]))->removeModelCache();
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public static function getServiceName($key = '')
    {
        $services = [
            self::SERVICE_OTP => 'Otp',
            self::SERVICE_LOGIN_DETAIL => 'Login'
        ];

        return !empty($key) ? $services[$key] : $services;
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

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['type'])) {
            $modelAQ->andWhere($tableName . '.type =:type', [':type' => $params['type']]);
        }
        if (isset($params['service'])) {
            $modelAQ->andWhere($tableName . '.service =:service', [':service' => $params['service']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }
    
    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }
    
    
    public static function getDocumentGuidLines($service, $params = [])
    {
        $guidlines = self::findByType(self::TEMPLATE_DOC_REASON, [
            'service' => $service
        ]);
        if(empty($guidlines)){
            return [];
        }
        return $guidlines;
    }
    
}
