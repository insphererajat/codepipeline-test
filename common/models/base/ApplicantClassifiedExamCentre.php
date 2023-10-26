<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_classified_exam_centre".
 *
 * @property int $applicant_classified_id
 * @property string $exam_centre_id
 * @property string $preference
 * @property int $exam_level
 * @property int $created_on
 * @property int $modified_on
 * @property int $created_by
 * @property int $modified_by
 *
 * @property ApplicantClassified $applicantClassified
 */
class ApplicantClassifiedExamCentre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_classified_exam_centre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_classified_id', 'exam_centre_id'], 'required'],
            [['applicant_classified_id', 'exam_level', 'created_on', 'modified_on', 'created_by', 'modified_by'], 'integer'],
            [['exam_centre_id', 'preference'], 'string', 'max' => 255],
            [['applicant_classified_id', 'exam_centre_id'], 'unique', 'targetAttribute' => ['applicant_classified_id', 'exam_centre_id']],
            [['applicant_classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantClassified::className(), 'targetAttribute' => ['applicant_classified_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicant_classified_id' => 'Applicant Classified ID',
            'exam_centre_id' => 'Exam Centre ID',
            'preference' => 'Preference',
            'exam_level' => 'Exam Level',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantClassified()
    {
        return $this->hasOne(ApplicantClassified::className(), ['id' => 'applicant_classified_id']);
    }
}
