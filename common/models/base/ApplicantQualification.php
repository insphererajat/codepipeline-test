<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_qualification".
 *
 * @property int $id
 * @property int $applicant_post_id
 * @property int $qualification_type_id
 * @property string|null $qualification_year
 * @property int $qualification_degree_id
 * @property string|null $course_name
 * @property int|null $board_university
 * @property string|null $other_board
 * @property int|null $university_state
 * @property int|null $result_status
 * @property int|null $council_id
 * @property string|null $council_registration_date
 * @property string|null $council_renewal_date
 * @property string|null $council_registration_no
 * @property int|null $course_duration
 * @property string|null $date_of_marksheet
 * @property string|null $mark_type
 * @property int|null $obtained_marks
 * @property int|null $total_marks
 * @property float|null $cgpa
 * @property string|null $grade
 * @property float|null $percentage
 * @property int|null $division
 * @property string|null $remarks
 * @property string|null $net_qualifying_date
 * @property string|null $mphil_phd_registration_no
 * @property string|null $mphil_phd_registration_date
 * @property string|null $mphil_phd_project_title
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantPost $applicantPost
 * @property MstListType $council
 * @property MstQualification $qualificationDegree
 * @property MstQualification $qualificationType
 * @property MstUniversity $boardUniversity
 * @property ApplicantQualificationSubject[] $applicantQualificationSubjects
 * @property MstSubject[] $subjects
 */
class ApplicantQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_qualification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'qualification_type_id', 'qualification_degree_id'], 'required'],
            [['applicant_post_id', 'qualification_type_id', 'qualification_degree_id', 'board_university', 'university_state', 'result_status', 'council_id', 'course_duration', 'obtained_marks', 'total_marks', 'division', 'created_on', 'modified_on'], 'integer'],
            [['qualification_year', 'council_registration_date', 'council_renewal_date', 'date_of_marksheet', 'net_qualifying_date', 'mphil_phd_registration_date'], 'safe'],
            [['mark_type'], 'string'],
            [['cgpa', 'percentage'], 'number'],
            [['course_name', 'other_board', 'remarks', 'mphil_phd_registration_no', 'mphil_phd_project_title'], 'string', 'max' => 255],
            [['council_registration_no'], 'string', 'max' => 100],
            [['grade'], 'string', 'max' => 225],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['council_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['council_id' => 'id']],
            [['qualification_degree_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstQualification::className(), 'targetAttribute' => ['qualification_degree_id' => 'id']],
            [['qualification_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstQualification::className(), 'targetAttribute' => ['qualification_type_id' => 'id']],
            [['board_university'], 'exist', 'skipOnError' => true, 'targetClass' => MstUniversity::className(), 'targetAttribute' => ['board_university' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'qualification_type_id' => Yii::t('app', 'Qualification Type ID'),
            'qualification_year' => Yii::t('app', 'Qualification Year'),
            'qualification_degree_id' => Yii::t('app', 'Qualification Degree ID'),
            'course_name' => Yii::t('app', 'Course Name'),
            'board_university' => Yii::t('app', 'Board University'),
            'other_board' => Yii::t('app', 'Other Board'),
            'university_state' => Yii::t('app', 'University State'),
            'result_status' => Yii::t('app', 'Result Status'),
            'council_id' => Yii::t('app', 'Council ID'),
            'council_registration_date' => Yii::t('app', 'Council Registration Date'),
            'council_renewal_date' => Yii::t('app', 'Council Renewal Date'),
            'council_registration_no' => Yii::t('app', 'Council Registration No'),
            'course_duration' => Yii::t('app', 'Course Duration'),
            'date_of_marksheet' => Yii::t('app', 'Date Of Marksheet'),
            'mark_type' => Yii::t('app', 'Mark Type'),
            'obtained_marks' => Yii::t('app', 'Obtained Marks'),
            'total_marks' => Yii::t('app', 'Total Marks'),
            'cgpa' => Yii::t('app', 'Cgpa'),
            'grade' => Yii::t('app', 'Grade'),
            'percentage' => Yii::t('app', 'Percentage'),
            'division' => Yii::t('app', 'Division'),
            'remarks' => Yii::t('app', 'Remarks'),
            'net_qualifying_date' => Yii::t('app', 'Net Qualifying Date'),
            'mphil_phd_registration_no' => Yii::t('app', 'Mphil Phd Registration No'),
            'mphil_phd_registration_date' => Yii::t('app', 'Mphil Phd Registration Date'),
            'mphil_phd_project_title' => Yii::t('app', 'Mphil Phd Project Title'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
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
     * Gets query for [[Council]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCouncil()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'council_id']);
    }

    /**
     * Gets query for [[QualificationDegree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualificationDegree()
    {
        return $this->hasOne(MstQualification::className(), ['id' => 'qualification_degree_id']);
    }

    /**
     * Gets query for [[QualificationType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualificationType()
    {
        return $this->hasOne(MstQualification::className(), ['id' => 'qualification_type_id']);
    }

    /**
     * Gets query for [[BoardUniversity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBoardUniversity()
    {
        return $this->hasOne(MstUniversity::className(), ['id' => 'board_university']);
    }

    /**
     * Gets query for [[ApplicantQualificationSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualificationSubjects()
    {
        return $this->hasMany(ApplicantQualificationSubject::className(), ['applicant_qualification_id' => 'id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(MstSubject::className(), ['id' => 'subject_id'])->viaTable('applicant_qualification_subject', ['applicant_qualification_id' => 'id']);
    }
}
