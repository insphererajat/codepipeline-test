<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_list_type".
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property int|null $parent_id
 * @property int $display_order
 * @property int|null $is_active
 * @property int|null $is_deleted
 * @property int|null $created_by
 * @property int|null $created_on
 * @property int|null $modified_by
 * @property int|null $modified_on
 *
 * @property ApplicantDetail[] $applicantDetails
 * @property ApplicantDetail[] $applicantDetails0
 * @property ApplicantDetail[] $applicantDetails1
 * @property ApplicantDetail[] $applicantDetails2
 * @property ApplicantDetail[] $applicantDetails3
 * @property ApplicantDetail[] $applicantDetails4
 * @property ApplicantDetail[] $applicantDetails5
 * @property ApplicantDetail[] $applicantDetails6
 * @property ApplicantDetail[] $applicantDetails7
 * @property ApplicantDetail[] $applicantDetails8
 * @property ApplicantEmployment[] $applicantEmployments
 * @property ApplicantEmployment[] $applicantEmployments0
 * @property ApplicantEmployment[] $applicantEmployments1
 * @property ApplicantEmployment[] $applicantEmployments2
 * @property ApplicantQualification[] $applicantQualifications
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstListType $parent
 * @property MstListType[] $mstListTypes
 * @property MstPostCriteria[] $mstPostCriterias
 * @property MstPostFee[] $mstPostFees
 * @property MstPostFee[] $mstPostFees0
 */
class MstListType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_list_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'name', 'display_order'], 'required'],
            [['parent_id', 'display_order', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'display_order' => Yii::t('app', 'Display Order'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails()
    {
        return $this->hasMany(ApplicantDetail::className(), ['father_occupation_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails0()
    {
        return $this->hasMany(ApplicantDetail::className(), ['father_qualification_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails1()
    {
        return $this->hasMany(ApplicantDetail::className(), ['identity_type_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails2()
    {
        return $this->hasMany(ApplicantDetail::className(), ['mother_occupation_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails3]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails3()
    {
        return $this->hasMany(ApplicantDetail::className(), ['mother_qualification_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails4]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails4()
    {
        return $this->hasMany(ApplicantDetail::className(), ['social_category_certificate_issue_authority_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails5]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails5()
    {
        return $this->hasMany(ApplicantDetail::className(), ['disability_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails6]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails6()
    {
        return $this->hasMany(ApplicantDetail::className(), ['employer_type_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails7]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails7()
    {
        return $this->hasMany(ApplicantDetail::className(), ['employment_registration_office_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails8]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails8()
    {
        return $this->hasMany(ApplicantDetail::className(), ['social_category_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantEmployments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantEmployments()
    {
        return $this->hasMany(ApplicantEmployment::className(), ['employer_type' => 'id']);
    }

    /**
     * Gets query for [[ApplicantEmployments0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantEmployments0()
    {
        return $this->hasMany(ApplicantEmployment::className(), ['employment_nature_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantEmployments1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantEmployments1()
    {
        return $this->hasMany(ApplicantEmployment::className(), ['employment_type_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantEmployments2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantEmployments2()
    {
        return $this->hasMany(ApplicantEmployment::className(), ['experience_type_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualifications()
    {
        return $this->hasMany(ApplicantQualification::className(), ['council_id' => 'id']);
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
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[MstListTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstListTypes()
    {
        return $this->hasMany(MstListType::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostCriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostCriterias()
    {
        return $this->hasMany(MstPostCriteria::className(), ['reservation_category_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostFees()
    {
        return $this->hasMany(MstPostFee::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostFees0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostFees0()
    {
        return $this->hasMany(MstPostFee::className(), ['sub_category_id' => 'id']);
    }
}
