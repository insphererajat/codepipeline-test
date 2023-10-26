<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_classified".
 *
 * @property int $id
 * @property string|null $guid
 * @property string $code
 * @property int $recruitment_year
 * @property string $title
 * @property string|null $description
 * @property string $start_date
 * @property string $end_date
 * @property string $extended_date
 * @property string $payment_end_date
 * @property string|null $document_upload_end_date
 * @property string|null $reference_date
 * @property string|null $folder_name
 * @property string|null $eservice_start_date
 * @property string|null $eservice_end_date
 * @property int|null $eservices_limit
 * @property float|null $eservices_fee
 * @property int|null $is_post_specific
 * @property string|null $application_no_prefix
 * @property int $cancellation_status
 * @property string|null $admit_card_start_date
 * @property string|null $admit_card_end_date
 * @property int|null $is_attendance
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $created_on
 * @property int $modified_by
 * @property int $modified_on
 *
 * @property ApplicantPost[] $applicantPosts
 * @property ExamCentre[] $examCentres
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstYear $recruitmentYear
 * @property MstPost[] $mstPosts
 * @property MstPostAge[] $mstPostAges
 * @property MstPostFee[] $mstPostFees
 * @property MstPostQualification[] $mstPostQualifications
 */
class MstClassified extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_classified';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'recruitment_year', 'title', 'start_date', 'end_date', 'extended_date', 'payment_end_date', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'required'],
            [['recruitment_year', 'eservices_limit', 'is_post_specific', 'cancellation_status', 'is_attendance', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['start_date', 'end_date', 'extended_date', 'payment_end_date', 'document_upload_end_date', 'reference_date', 'eservice_start_date', 'eservice_end_date', 'admit_card_start_date', 'admit_card_end_date'], 'safe'],
            [['eservices_fee'], 'number'],
            [['guid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 50],
            [['title', 'folder_name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['application_no_prefix'], 'string', 'max' => 100],
            [['guid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['recruitment_year'], 'exist', 'skipOnError' => true, 'targetClass' => MstYear::className(), 'targetAttribute' => ['recruitment_year' => 'code']],
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
            'recruitment_year' => Yii::t('app', 'Recruitment Year'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'extended_date' => Yii::t('app', 'Extended Date'),
            'payment_end_date' => Yii::t('app', 'Payment End Date'),
            'document_upload_end_date' => Yii::t('app', 'Document Upload End Date'),
            'reference_date' => Yii::t('app', 'Reference Date'),
            'folder_name' => Yii::t('app', 'Folder Name'),
            'eservice_start_date' => Yii::t('app', 'Eservice Start Date'),
            'eservice_end_date' => Yii::t('app', 'Eservice End Date'),
            'eservices_limit' => Yii::t('app', 'Eservices Limit'),
            'eservices_fee' => Yii::t('app', 'Eservices Fee'),
            'is_post_specific' => Yii::t('app', 'Is Post Specific'),
            'application_no_prefix' => Yii::t('app', 'Application No Prefix'),
            'cancellation_status' => Yii::t('app', 'Cancellation Status'),
            'admit_card_start_date' => Yii::t('app', 'Admit Card Start Date'),
            'admit_card_end_date' => Yii::t('app', 'Admit Card End Date'),
            'is_attendance' => Yii::t('app', 'Is Attendance'),
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
        return $this->hasMany(ApplicantPost::className(), ['classified_id' => 'id']);
    }

    /**
     * Gets query for [[ExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentres()
    {
        return $this->hasMany(ExamCentre::className(), ['classified_id' => 'id']);
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
     * Gets query for [[RecruitmentYear]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecruitmentYear()
    {
        return $this->hasOne(MstYear::className(), ['code' => 'recruitment_year']);
    }

    /**
     * Gets query for [[MstPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPosts()
    {
        return $this->hasMany(MstPost::className(), ['classified_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostAges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostAges()
    {
        return $this->hasMany(MstPostAge::className(), ['classified_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostFees()
    {
        return $this->hasMany(MstPostFee::className(), ['classified_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['classified_id' => 'id']);
    }
}
