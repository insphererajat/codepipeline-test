<?php

namespace common\models;

use Yii;
use components\Helper;
use common\models\base\MstConfiguration as BaseMstConfiguration;

/**
 * Description of MstConfiguration
 *
 * @author Amit Handa
 */
class MstConfiguration extends BaseMstConfiguration
{
    
    const TYPE_SENDGRID = 'sendgrid';
    const TYPE_PAYMENT_PAYU = 'payu';
    const TYPE_PAYMENT_ICICI = 'icici';
    const TYPE_PAYMENT_CSC = "csc";
    const TYPE_EMAIL_AES = 'aes';
    const TYPE_SMS = 'sms';
    const TYPE_S3 = 'amazonS3';
    const TYPE_PAYU_UBI = 'ubi';
    const TYPE_PAYU_IDBI = 'idbi';
    const TYPE_CCAVENUE_HDFC = 'hdfc';
    const TYPE_EZYSMS = 'ezysms';
    const TYPE_RAZORPAY = 'razorPay';
    
    // payment_mode_type const
    const AMOUNT = 1;
    const PERCENTAGE = 2;

    public static function getTypes()
    {
        return [
            self::TYPE_SENDGRID => self::TYPE_SENDGRID,
            self::TYPE_PAYMENT_PAYU => self::TYPE_PAYMENT_PAYU,
            self::TYPE_PAYMENT_ICICI => self::TYPE_PAYMENT_ICICI,
            self::TYPE_PAYMENT_CSC => self::TYPE_PAYMENT_CSC,
            self::TYPE_EMAIL_AES => self::TYPE_EMAIL_AES,
            self::TYPE_SMS => self::TYPE_SMS,
            self::TYPE_S3 => self::TYPE_S3,
            self::TYPE_PAYU_UBI => self::TYPE_PAYU_UBI,
            self::TYPE_PAYU_IDBI => self::TYPE_PAYU_IDBI,
            self::TYPE_CCAVENUE_HDFC => self::TYPE_CCAVENUE_HDFC,
            self::TYPE_EZYSMS => self::TYPE_EZYSMS,
            self::TYPE_RAZORPAY => self::TYPE_RAZORPAY
        ];
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            \components\behaviors\GuidBehavior::className(),
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        $modelAQ->select($tableName . '.*');
        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
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

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }
    
    public static function decryptValues($model)
    {
        $model['config_val1'] = !empty($model['config_val1']) ? Helper::decryptString($model['config_val1']) : '';
        $model['config_val2'] = !empty($model['config_val2']) ? Helper::decryptString($model['config_val2']) : '';
        $model['config_val3'] = !empty($model['config_val3']) ? Helper::decryptString($model['config_val3']) : '';
        $model['config_val4'] = !empty($model['config_val4']) ? Helper::decryptString($model['config_val4']) : '';
        $model['config_val5'] = !empty($model['config_val5']) ? Helper::decryptString($model['config_val5']) : '';
        $model['config_val6'] = !empty($model['config_val6']) ? Helper::decryptString($model['config_val6']) : '';
        $model['config_val7'] = !empty($model['config_val7']) ? Helper::decryptString($model['config_val7']) : '';
        $model['config_val8'] = !empty($model['config_val8']) ? Helper::decryptString($model['config_val8']) : '';
        $model['config_val9'] = !empty($model['config_val9']) ? Helper::decryptString($model['config_val9']) : '';
        $model['config_val10'] = !empty($model['config_val10']) ? Helper::decryptString($model['config_val10']) : '';
        $model['config_val11'] = !empty($model['config_val11']) ? Helper::decryptString($model['config_val11']) : '';
        $model['config_val12'] = !empty($model['config_val12']) ? Helper::decryptString($model['config_val12']) : '';
        $model['config_val13'] = !empty($model['config_val13']) ? Helper::decryptString($model['config_val13']) : '';
        $model['config_val13'] = !empty($model['config_val14']) ? Helper::decryptString($model['config_val14']) : '';
        $model['config_val13'] = !empty($model['config_val15']) ? Helper::decryptString($model['config_val15']) : '';
        
        return $model;
    }

}
