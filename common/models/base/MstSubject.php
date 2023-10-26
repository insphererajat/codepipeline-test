<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_subject".
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property int $is_active
 * @property int|null $is_deleted
 * @property int|null $created_by
 * @property int|null $created_on
 * @property int|null $modified_by
 * @property int|null $modified_on
 *
 * @property ApplicantCriteriaDetail[] $applicantCriteriaDetails
 * @property ApplicantExamSubject[] $applicantExamSubjects
 * @property ApplicantPost[] $applicantPosts
 * @property ApplicantQualificationSubject[] $applicantQualificationSubjects
 * @property ApplicantQualification[] $applicantQualifications
 * @property MstPostQualification[] $mstPostQualifications
 * @property MstPostQualification[] $mstPostQualifications0
 * @property MstQualificationSubject[] $mstQualificationSubjects
 * @property MstQualification[] $qualifications
 * @property User $createdBy
 * @property User $modifiedBy
 */
class MstSubject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'name'], 'required'],
            [['is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['name'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantCriteriaDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriteriaDetails()
    {
        return $this->hasMany(ApplicantCriteriaDetail::className(), ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantExamSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantExamSubjects()
    {
        return $this->hasMany(ApplicantExamSubject::className(), ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPosts()
    {
        return $this->hasMany(ApplicantPost::className(), ['id' => 'applicant_post_id'])->viaTable('applicant_exam_subject', ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantQualificationSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualificationSubjects()
    {
        return $this->hasMany(ApplicantQualificationSubject::className(), ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualifications()
    {
        return $this->hasMany(ApplicantQualification::className(), ['id' => 'applicant_qualification_id'])->viaTable('applicant_qualification_subject', ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['additional_subject_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications0()
    {
        return $this->hasMany(MstPostQualification::className(), ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[MstQualificationSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstQualificationSubjects()
    {
        return $this->hasMany(MstQualificationSubject::className(), ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[Qualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualifications()
    {
        return $this->hasMany(MstQualification::className(), ['id' => 'qualification_id'])->viaTable('mst_qualification_subject', ['subject_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[ModifiedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }
}
