<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_post".
 *
 * @property int $id
 * @property string|null $guid
 * @property int $applicant_id
 * @property int $classified_id
 * @property int $post_id
 * @property string|null $application_no
 * @property int|null $application_status
 * @property int|null $same_as_present_address
 * @property int|null $payment_status
 * @property string|null $place
 * @property string|null $date
 * @property int|null $quota
 * @property int|null $parent_applicant_post_id
 * @property string $eservice_tabs
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantAddress[] $applicantAddresses
 * @property ApplicantCriteria[] $applicantCriterias
 * @property ApplicantCriteriaDetail[] $applicantCriteriaDetails
 * @property ApplicantDetail[] $applicantDetails
 * @property ApplicantDocument[] $applicantDocuments
 * @property ApplicantEmployment[] $applicantEmployments
 * @property ApplicantExamSubject[] $applicantExamSubjects
 * @property MstSubject[] $subjects
 * @property ApplicantFee[] $applicantFees
 * @property MstClassified $classified
 * @property Applicant $applicant
 * @property ApplicantPost $parentApplicantPost
 * @property ApplicantPost[] $applicantPosts
 * @property MstPost $post
 * @property ApplicantPostDetail[] $applicantPostDetails
 * @property ApplicantPostExamCentre[] $applicantPostExamCentres
 * @property MstDistrict[] $stateCodes
 * @property ApplicantQualification[] $applicantQualifications
 */
class ApplicantPost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'classified_id', 'post_id'], 'required'],
            [['applicant_id', 'classified_id', 'post_id', 'application_status', 'same_as_present_address', 'payment_status', 'quota', 'parent_applicant_post_id', 'created_on', 'modified_on'], 'integer'],
            [['date'], 'safe'],
            [['guid'], 'string', 'max' => 36],
            [['application_no'], 'string', 'max' => 40],
            [['place'], 'string', 'max' => 255],
            [['eservice_tabs'], 'string', 'max' => 6],
            [['guid'], 'unique'],
            [['application_no'], 'unique'],
            [['classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstClassified::className(), 'targetAttribute' => ['classified_id' => 'id']],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['parent_applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['parent_applicant_post_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPost::className(), 'targetAttribute' => ['post_id' => 'id']],
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
            'applicant_id' => Yii::t('app', 'Applicant ID'),
            'classified_id' => Yii::t('app', 'Classified ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'application_no' => Yii::t('app', 'Application No'),
            'application_status' => Yii::t('app', 'Application Status'),
            'same_as_present_address' => Yii::t('app', 'Same As Present Address'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'place' => Yii::t('app', 'Place'),
            'date' => Yii::t('app', 'Date'),
            'quota' => Yii::t('app', 'Quota'),
            'parent_applicant_post_id' => Yii::t('app', 'Parent Applicant Post ID'),
            'eservice_tabs' => Yii::t('app', 'Eservice Tabs'),
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
        return $this->hasMany(ApplicantAddress::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantCriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriterias()
    {
        return $this->hasMany(ApplicantCriteria::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantCriteriaDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriteriaDetails()
    {
        return $this->hasMany(ApplicantCriteriaDetail::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails()
    {
        return $this->hasMany(ApplicantDetail::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDocuments()
    {
        return $this->hasMany(ApplicantDocument::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantEmployments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantEmployments()
    {
        return $this->hasMany(ApplicantEmployment::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantExamSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantExamSubjects()
    {
        return $this->hasMany(ApplicantExamSubject::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(MstSubject::className(), ['id' => 'subject_id'])->viaTable('applicant_exam_subject', ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantFees()
    {
        return $this->hasMany(ApplicantFee::className(), ['applicant_post_id' => 'id']);
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
     * Gets query for [[Applicant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
    }

    /**
     * Gets query for [[ParentApplicantPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentApplicantPost()
    {
        return $this->hasOne(ApplicantPost::className(), ['id' => 'parent_applicant_post_id']);
    }

    /**
     * Gets query for [[ApplicantPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPosts()
    {
        return $this->hasMany(ApplicantPost::className(), ['parent_applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(MstPost::className(), ['id' => 'post_id']);
    }

    /**
     * Gets query for [[ApplicantPostDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPostDetails()
    {
        return $this->hasMany(ApplicantPostDetail::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantPostExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPostExamCentres()
    {
        return $this->hasMany(ApplicantPostExamCentre::className(), ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[StateCodes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStateCodes()
    {
        return $this->hasMany(MstDistrict::className(), ['state_code' => 'state_code', 'code' => 'district_code'])->viaTable('applicant_post_exam_centre', ['applicant_post_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualifications()
    {
        return $this->hasMany(ApplicantQualification::className(), ['applicant_post_id' => 'id']);
    }
}
