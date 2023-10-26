<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_district".
 *
 * @property int $code
 * @property string $guid
 * @property int $state_code
 * @property string $name
 * @property int|null $is_active
 * @property int|null $is_deleted
 * @property int $created_by
 * @property int|null $modified_by
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantAddress[] $applicantAddresses
 * @property ApplicantDetail[] $applicantDetails
 * @property ApplicantDetail[] $applicantDetails0
 * @property ApplicantDetail[] $applicantDetails1
 * @property ApplicantDetail[] $applicantDetails2
 * @property ApplicantPostExamCentre[] $applicantPostExamCentres
 * @property ApplicantPostExamCentre[] $applicantPostExamCentres0
 * @property ApplicantPost[] $applicantPosts
 * @property ExamCentre[] $examCentres
 * @property MstDepartment[] $mstDepartments
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstState $stateCode
 * @property MstTehsil[] $mstTehsils
 */
class MstDistrict extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'guid', 'state_code', 'name', 'created_by'], 'required'],
            [['code', 'state_code', 'is_active', 'is_deleted', 'created_by', 'modified_by', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 100],
            [['guid'], 'unique'],
            [['state_code', 'name'], 'unique', 'targetAttribute' => ['state_code', 'name']],
            [['code'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstState::className(), 'targetAttribute' => ['state_code' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'guid' => Yii::t('app', 'Guid'),
            'state_code' => Yii::t('app', 'State Code'),
            'name' => Yii::t('app', 'Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantAddresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantAddresses()
    {
        return $this->hasMany(ApplicantAddress::className(), ['state_code' => 'state_code', 'district_code' => 'code']);
    }

    /**
     * Gets query for [[ApplicantDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails()
    {
        return $this->hasMany(ApplicantDetail::className(), ['birth_state_code' => 'state_code', 'birth_district_code' => 'code']);
    }

    /**
     * Gets query for [[ApplicantDetails0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails0()
    {
        return $this->hasMany(ApplicantDetail::className(), ['domicile_issue_state' => 'state_code', 'domicile_issue_district' => 'code']);
    }

    /**
     * Gets query for [[ApplicantDetails1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails1()
    {
        return $this->hasMany(ApplicantDetail::className(), ['social_category_certificate_state_code' => 'state_code', 'social_category_certificate_district_code' => 'code']);
    }

    /**
     * Gets query for [[ApplicantDetails2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails2()
    {
        return $this->hasMany(ApplicantDetail::className(), ['high_school_passing_state' => 'state_code', 'high_school_passing_district' => 'code']);
    }

    /**
     * Gets query for [[ApplicantPostExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPostExamCentres()
    {
        return $this->hasMany(ApplicantPostExamCentre::className(), ['allocation_state_code' => 'state_code', 'allocation_district_code' => 'code']);
    }

    /**
     * Gets query for [[ApplicantPostExamCentres0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPostExamCentres0()
    {
        return $this->hasMany(ApplicantPostExamCentre::className(), ['state_code' => 'state_code', 'district_code' => 'code']);
    }

    /**
     * Gets query for [[ApplicantPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPosts()
    {
        return $this->hasMany(ApplicantPost::className(), ['id' => 'applicant_post_id'])->viaTable('applicant_post_exam_centre', ['state_code' => 'state_code', 'district_code' => 'code']);
    }

    /**
     * Gets query for [[ExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentres()
    {
        return $this->hasMany(ExamCentre::className(), ['state_code' => 'state_code', 'district_code' => 'code']);
    }

    /**
     * Gets query for [[MstDepartments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstDepartments()
    {
        return $this->hasMany(MstDepartment::className(), ['state_code' => 'state_code', 'district_code' => 'code']);
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

    /**
     * Gets query for [[StateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode()
    {
        return $this->hasOne(MstState::className(), ['code' => 'state_code']);
    }

    /**
     * Gets query for [[MstTehsils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstTehsils()
    {
        return $this->hasMany(MstTehsil::className(), ['state_code' => 'state_code', 'district_code' => 'code']);
    }
}
