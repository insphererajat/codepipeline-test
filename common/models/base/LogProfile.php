<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_profile".
 *
 * @property int $id
 * @property string $guid
 * @property int $applicant_id
 * @property string|null $old_value
 * @property string $new_value
 * @property int $status
 * @property int|null $created_on
 * @property int|null $created_by
 * @property int|null $modified_on
 * @property int|null $modified_by
 *
 * @property Applicant $applicant
 * @property LogProfileActivity[] $logProfileActivities
 * @property LogProfileMedia[] $logProfileMedia
 */
class LogProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'applicant_id', 'new_value', 'status'], 'required'],
            [['applicant_id', 'status', 'created_on', 'created_by', 'modified_on', 'modified_by'], 'integer'],
            [['old_value', 'new_value'], 'string'],
            [['guid'], 'string', 'max' => 36],
            [['guid'], 'unique'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
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
            'applicant_id' => Yii::t('app', 'Applicant ID'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'modified_by' => Yii::t('app', 'Modified By'),
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
     * Gets query for [[LogProfileActivities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfileActivities()
    {
        return $this->hasMany(LogProfileActivity::className(), ['log_profile_id' => 'id']);
    }

    /**
     * Gets query for [[LogProfileMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfileMedia()
    {
        return $this->hasMany(LogProfileMedia::className(), ['log_profile_id' => 'id']);
    }
}
