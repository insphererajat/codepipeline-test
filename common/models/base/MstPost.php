<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_post".
 *
 * @property int $id
 * @property string|null $guid
 * @property string $code
 * @property int|null $classified_id
 * @property string $title
 * @property string|null $folder_name
 * @property string $reference_date
 * @property int|null $is_age_relaxation
 * @property int $is_transferable
 * @property int $no_of_positions
 * @property int $department_id
 * @property int $recruitment_mode 1: Direct, 2: Deputation
 * @property int $recruitment_type 1: permanent, 2: temporary, 3: contractual',
 * @property int|null $is_tet
 * @property int $display_order
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $created_on
 * @property int $modified_by
 * @property int $modified_on
 *
 * @property ApplicantPost[] $applicantPosts
 * @property ApplicantPostDetail[] $applicantPostDetails
 * @property ExamCentre[] $examCentres
 * @property MstDepartment $department
 * @property MstClassified $classified
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstPostAge[] $mstPostAges
 * @property MstPostFee[] $mstPostFees
 * @property MstPostQualification[] $mstPostQualifications
 */
class MstPost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title', 'reference_date', 'is_transferable', 'no_of_positions', 'department_id', 'recruitment_mode', 'recruitment_type', 'display_order', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'required'],
            [['classified_id', 'is_age_relaxation', 'is_transferable', 'no_of_positions', 'department_id', 'recruitment_mode', 'recruitment_type', 'is_tet', 'display_order', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['reference_date'], 'safe'],
            [['guid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 50],
            [['title', 'folder_name'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstClassified::className(), 'targetAttribute' => ['classified_id' => 'id']],
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
            'code' => Yii::t('app', 'Code'),
            'classified_id' => Yii::t('app', 'Classified ID'),
            'title' => Yii::t('app', 'Title'),
            'folder_name' => Yii::t('app', 'Folder Name'),
            'reference_date' => Yii::t('app', 'Reference Date'),
            'is_age_relaxation' => Yii::t('app', 'Is Age Relaxation'),
            'is_transferable' => Yii::t('app', 'Is Transferable'),
            'no_of_positions' => Yii::t('app', 'No Of Positions'),
            'department_id' => Yii::t('app', 'Department ID'),
            'recruitment_mode' => Yii::t('app', 'Recruitment Mode'),
            'recruitment_type' => Yii::t('app', 'Recruitment Type'),
            'is_tet' => Yii::t('app', 'Is Tet'),
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
     * Gets query for [[ApplicantPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPosts()
    {
        return $this->hasMany(ApplicantPost::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantPostDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPostDetails()
    {
        return $this->hasMany(ApplicantPostDetail::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[ExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentres()
    {
        return $this->hasMany(ExamCentre::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(MstDepartment::className(), ['id' => 'department_id']);
    }

    /**
     * Gets query for [[Classified]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassified()
    {
        return $this->hasOne(MstClassified::className(), ['id' => 'classified_id']);
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
     * Gets query for [[MstPostAges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostAges()
    {
        return $this->hasMany(MstPostAge::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostFees()
    {
        return $this->hasMany(MstPostFee::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['post_id' => 'id']);
    }
}
