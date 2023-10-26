<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_classified".
 *
 * @property int $id
 * @property int $applicant_id
 * @property int $classified_id
 * @property int $is_email_verified
 * @property int $is_mobile_verified
 * @property int $form_step
 * @property int $application_status
 * @property int $payment_status
 * @property int $created_on
 * @property int $modified_on
 *
 * @property ApplicantAddress[] $applicantAddresses
 * @property Applicant $applicant
 * @property MstClassified $classified
 * @property ApplicantClassifiedDetail[] $applicantClassifiedDetails
 * @property ApplicantClassifiedExamCentre[] $applicantClassifiedExamCentres
 * @property ApplicantExamSubject[] $applicantExamSubjects
 * @property MstSubject[] $subjects
 */
class ApplicantClassified extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_classified';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'classified_id'], 'required'],
            [['applicant_id', 'classified_id', 'is_email_verified', 'is_mobile_verified', 'form_step', 'application_status', 'payment_status', 'created_on', 'modified_on'], 'integer'],
            [['applicant_id', 'classified_id'], 'unique', 'targetAttribute' => ['applicant_id', 'classified_id']],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstClassified::className(), 'targetAttribute' => ['classified_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'applicant_id' => 'Applicant ID',
            'classified_id' => 'Classified ID',
            'is_email_verified' => 'Is Email Verified',
            'is_mobile_verified' => 'Is Mobile Verified',
            'form_step' => 'Form Step',
            'application_status' => 'Application Status',
            'payment_status' => 'Payment Status',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantAddresses()
    {
        return $this->hasMany(ApplicantAddress::className(), ['applicant_classified_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassified()
    {
        return $this->hasOne(MstClassified::className(), ['id' => 'classified_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantClassifiedDetails()
    {
        return $this->hasMany(ApplicantClassifiedDetail::className(), ['applicant_classified_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantClassifiedExamCentres()
    {
        return $this->hasMany(ApplicantClassifiedExamCentre::className(), ['applicant_classified_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantExamSubjects()
    {
        return $this->hasMany(ApplicantExamSubject::className(), ['applicant_classified_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(MstSubject::className(), ['id' => 'subject_id'])->viaTable('applicant_exam_subject', ['applicant_classified_id' => 'id']);
    }
}
