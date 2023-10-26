<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_employment".
 *
 * @property int $id
 * @property int $applicant_post_id
 * @property string $employer
 * @property int|null $employment_nature_id
 * @property string $office_name
 * @property string $designation
 * @property int $employment_type_id
 * @property string $start_date
 * @property string|null $end_date
 * @property int|null $experience_type_id
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantPost $applicantPost
 * @property MstListType $employmentNature
 * @property MstListType $employmentType
 * @property MstListType $experienceType
 */
class ApplicantEmployment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_employment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'employer', 'office_name', 'designation', 'employment_type_id', 'start_date'], 'required'],
            [['applicant_post_id', 'employment_nature_id', 'employment_type_id', 'experience_type_id', 'created_on', 'modified_on'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['employer', 'designation'], 'string', 'max' => 100],
            [['office_name'], 'string', 'max' => 255],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['employment_nature_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['employment_nature_id' => 'id']],
            [['employment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['employment_type_id' => 'id']],
            [['experience_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['experience_type_id' => 'id']],
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
            'employer' => Yii::t('app', 'Employer'),
            'employment_nature_id' => Yii::t('app', 'Employment Nature ID'),
            'office_name' => Yii::t('app', 'Office Name'),
            'designation' => Yii::t('app', 'Designation'),
            'employment_type_id' => Yii::t('app', 'Employment Type ID'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'experience_type_id' => Yii::t('app', 'Experience Type ID'),
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
     * Gets query for [[EmploymentNature]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmploymentNature()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'employment_nature_id']);
    }

    /**
     * Gets query for [[EmploymentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmploymentType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'employment_type_id']);
    }

    /**
     * Gets query for [[ExperienceType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExperienceType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'experience_type_id']);
    }
}
