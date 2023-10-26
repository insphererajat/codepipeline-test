<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_qualification_subject".
 *
 * @property int $id
 * @property int $applicant_qualification_id
 * @property int $subject_id
 * @property int|null $subject_year
 * @property int|null $marks
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantQualification $applicantQualification
 * @property MstSubject $subject
 */
class ApplicantQualificationSubject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_qualification_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_qualification_id', 'subject_id'], 'required'],
            [['applicant_qualification_id', 'subject_id', 'subject_year', 'marks', 'created_on', 'modified_on'], 'integer'],
            [['applicant_qualification_id', 'subject_id'], 'unique', 'targetAttribute' => ['applicant_qualification_id', 'subject_id']],
            [['applicant_qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantQualification::className(), 'targetAttribute' => ['applicant_qualification_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstSubject::className(), 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant_qualification_id' => Yii::t('app', 'Applicant Qualification ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'subject_year' => Yii::t('app', 'Subject Year'),
            'marks' => Yii::t('app', 'Marks'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantQualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualification()
    {
        return $this->hasOne(ApplicantQualification::className(), ['id' => 'applicant_qualification_id']);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(MstSubject::className(), ['id' => 'subject_id']);
    }
}
