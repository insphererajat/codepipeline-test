<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_configuration".
 *
 * @property int $id
 * @property string $guid
 * @property string $type
 * @property string|null $config_val1
 * @property string|null $config_val2
 * @property string|null $config_val3
 * @property string|null $config_val4
 * @property string|null $config_val5
 * @property string|null $config_val6
 * @property string|null $config_val7
 * @property string|null $config_val8
 * @property string|null $config_val9
 * @property string|null $config_val10
 * @property string|null $config_val11
 * @property string|null $config_val12
 * @property string|null $config_val13
 * @property string|null $config_val14
 * @property string|null $config_val15
 * @property int|null $is_payment_mode
 * @property string|null $configuration_rule
 * @property int|null $is_active
 * @property int|null $modified_on
 * @property int|null $created_on
 */
class MstConfiguration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'type'], 'required'],
            [['is_payment_mode', 'is_active', 'modified_on', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['type'], 'string', 'max' => 50],
            [['config_val1', 'config_val2', 'config_val3', 'config_val4', 'config_val5', 'config_val6', 'config_val7', 'configuration_rule'], 'string', 'max' => 1000],
            [['config_val8', 'config_val9', 'config_val10'], 'string', 'max' => 10000],
            [['config_val11', 'config_val12', 'config_val13', 'config_val14', 'config_val15'], 'string', 'max' => 5000],
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
            'type' => Yii::t('app', 'Type'),
            'config_val1' => Yii::t('app', 'Config Val1'),
            'config_val2' => Yii::t('app', 'Config Val2'),
            'config_val3' => Yii::t('app', 'Config Val3'),
            'config_val4' => Yii::t('app', 'Config Val4'),
            'config_val5' => Yii::t('app', 'Config Val5'),
            'config_val6' => Yii::t('app', 'Config Val6'),
            'config_val7' => Yii::t('app', 'Config Val7'),
            'config_val8' => Yii::t('app', 'Config Val8'),
            'config_val9' => Yii::t('app', 'Config Val9'),
            'config_val10' => Yii::t('app', 'Config Val10'),
            'config_val11' => Yii::t('app', 'Config Val11'),
            'config_val12' => Yii::t('app', 'Config Val12'),
            'config_val13' => Yii::t('app', 'Config Val13'),
            'config_val14' => Yii::t('app', 'Config Val14'),
            'config_val15' => Yii::t('app', 'Config Val15'),
            'is_payment_mode' => Yii::t('app', 'Is Payment Mode'),
            'configuration_rule' => Yii::t('app', 'Configuration Rule'),
            'is_active' => Yii::t('app', 'Is Active'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
    }
}
