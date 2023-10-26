<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_profile_media".
 *
 * @property int $id
 * @property int $applicant_id
 * @property int $log_profile_id
 * @property int $media_id
 * @property int|null $created_on
 * @property int|null $created_by
 *
 * @property Applicant $applicant
 * @property LogProfile $logProfile
 * @property Media $media
 */
class LogProfileMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_profile_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'log_profile_id', 'media_id'], 'required'],
            [['applicant_id', 'log_profile_id', 'media_id', 'created_on', 'created_by'], 'integer'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['log_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => LogProfile::className(), 'targetAttribute' => ['log_profile_id' => 'id']],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
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
            'log_profile_id' => Yii::t('app', 'Log Profile ID'),
            'media_id' => Yii::t('app', 'Media ID'),
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
     * Gets query for [[LogProfile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogProfile()
    {
        return $this->hasOne(LogProfile::className(), ['id' => 'log_profile_id']);
    }

    /**
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}
