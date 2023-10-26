<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_applicant".
 *
 * @property int $id
 * @property int $applicant_id
 * @property string $type
 * @property string|null $old_value
 * @property string|null $new_value
 * @property string|null $device_type
 * @property string|null $ip_address
 * @property string|null $useragent
 * @property int|null $created_on
 * @property int|null $created_by
 *
 * @property Applicant $applicant
 * @property Applicant $createdBy
 */
class LogApplicant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_applicant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'type'], 'required'],
            [['applicant_id', 'created_on', 'created_by'], 'integer'],
            [['type'], 'string'],
            [['old_value', 'new_value', 'useragent'], 'string', 'max' => 255],
            [['device_type'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 15],
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
            'type' => Yii::t('app', 'Type'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
            'device_type' => Yii::t('app', 'Device Type'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'useragent' => Yii::t('app', 'Useragent'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
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
}
