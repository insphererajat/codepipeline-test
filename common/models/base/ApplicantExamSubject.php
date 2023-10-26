<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_exam_subject".
 *
 * @property int $applicant_post_id
 * @property int $subject_id
 * @property int $exam_level
 * @property int|null $created_on
 * @property int|null $modified_on
 * @property int|null $created_by
 * @property int|null $modified_by
 *
 * @property ApplicantPost $applicantPost
 * @property MstSubject $subject
 */
class ApplicantExamSubject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_exam_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'subject_id'], 'required'],
            [['applicant_post_id', 'subject_id', 'exam_level', 'created_on', 'modified_on', 'created_by', 'modified_by'], 'integer'],
            [['applicant_post_id', 'subject_id'], 'unique', 'targetAttribute' => ['applicant_post_id', 'subject_id']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstSubject::className(), 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'exam_level' => Yii::t('app', 'Exam Level'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }

    /**
     * Gets query for [[ApplicantPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPost()
    {
        return $this->hasOne(ApplicantPost::className(), ['id' => 'applicant_post_id']);
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
