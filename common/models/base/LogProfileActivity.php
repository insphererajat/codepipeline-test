<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_profile_activity".
 *
 * @property int $id
 * @property int $applicant_id
 * @property int $log_profile_id
 * @property int $status
 * @property string|null $remarks
 * @property int|null $created_on
 * @property int|null $created_by
 *
 * @property Applicant $applicant
 * @property LogProfile $logProfile
 */
class LogProfileActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_profile_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'log_profile_id', 'status'], 'required'],
            [['applicant_id', 'log_profile_id', 'status', 'created_on', 'created_by'], 'integer'],
            [['remarks'], 'string', 'max' => 1000],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['log_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => LogProfile::className(), 'targetAttribute' => ['log_profile_id' => 'id']],
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
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
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
}
