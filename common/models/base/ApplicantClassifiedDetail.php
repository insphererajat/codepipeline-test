<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_classified_detail".
 *
 * @property int $id
 * @property string $guid
 * @property int $applicant_classified_id
 * @property int $reference_no
 * @property string $phone_no
 * @property string $name
 * @property string $gender
 * @property string $father_name
 * @property string $mother_name
 * @property string $date_of_birth
 * @property int $birth_state_id
 * @property int $birth_district_id
 * @property int $birth_tehsil_id
 * @property string $birth_city
 * @property int $permanent_residence_type 1: plain, 2: Hill
 * @property int $nationality 1: Indian, 2: Other
 * @property int $marital_status
 * @property string $name_after_marriage
 * @property string $spouse_name
 * @property string $marriage_date
 * @property int $no_of_children only living children
 * @property int $is_domiciled 0: No, 1 : Yes
 * @property string $domicile_no
 * @property int $domicile_issue_state
 * @property int $domicile_issue_district
 * @property string $domicile_issue_date
 * @property int $disability_id
 * @property int $disability_percentage
 * @property string $disability_certificate_no
 * @property string $disability_certificate_issue_date
 * @property int $social_category_id
 * @property string $social_category_certificate_no
 * @property int $social_category_certificate_issue_authority_id
 * @property string $social_category_certificate_issue_date
 * @property int $social_category_certificate_state_code
 * @property int $social_category_certificate_district_code
 * @property int $is_non_creamy_layer
 * @property int $employer_type
 * @property int $employment_nature 1: permanent, 2: temporary, 3: contractual
 * @property int $is_employment_registered
 * @property string $employment_registration_no
 * @property int $employment_registration_office_id
 * @property string $employment_registration_date
 * @property string $employment_registration_valid_upto_date
 * @property int $is_exserviceman
 * @property int $is_dismissed_from_defence
 * @property string $discharge_certificate_no
 * @property string $discharge_date
 * @property int $is_voluntary_retirement
 * @property int $is_relieved_on_medical
 * @property int $is_dswro_registered
 * @property string $dswro_registration_no
 * @property string $dswro_registration_date
 * @property string $dswro_office_name
 * @property int $have_ncc_nss
 * @property int $have_served_territorial_army
 * @property int $is_ncc_b_certificate
 * @property string $ncc_b_certificate_date
 * @property int $is_ncc_c_certificate
 * @property int $is_nss_b_certificate
 * @property string $is_nss_b_certificate_date
 * @property int $is_nss_c_certificate
 * @property int $is_criminal_case
 * @property int $is_criminal_proceed_complete
 * @property string $criminal_sentenance_data
 * @property int $is_debarred
 * @property string $debarred_from_date
 * @property string $debarred_to_date
 * @property int $same_as_present_address
 * @property int $created_on
 * @property int $modified_on
 *
 * @property ApplicantClassified $applicantClassified
 * @property MstTehsil $birthState
 * @property MstListType $disability
 * @property MstDistrict $domicileIssueState
 * @property MstListType $employerType
 * @property MstEmploymentOffice $employmentRegistrationOffice
 * @property MstListType $socialCategoryCertificateIssueAuthority
 * @property MstListType $socialCategory
 */
class ApplicantClassifiedDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_classified_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid'], 'required'],
            [['applicant_classified_id', 'reference_no', 'birth_state_id', 'birth_district_id', 'birth_tehsil_id', 'permanent_residence_type', 'nationality', 'marital_status', 'no_of_children', 'is_domiciled', 'domicile_issue_state', 'domicile_issue_district', 'disability_id', 'disability_percentage', 'social_category_id', 'social_category_certificate_issue_authority_id', 'social_category_certificate_state_code', 'social_category_certificate_district_code', 'is_non_creamy_layer', 'employer_type', 'employment_nature', 'is_employment_registered', 'employment_registration_office_id', 'is_exserviceman', 'is_dismissed_from_defence', 'is_voluntary_retirement', 'is_relieved_on_medical', 'is_dswro_registered', 'have_ncc_nss', 'have_served_territorial_army', 'is_ncc_b_certificate', 'is_ncc_c_certificate', 'is_nss_b_certificate', 'is_nss_c_certificate', 'is_criminal_case', 'is_criminal_proceed_complete', 'is_debarred', 'same_as_present_address', 'created_on', 'modified_on'], 'integer'],
            [['gender', 'criminal_sentenance_data'], 'string'],
            [['date_of_birth', 'marriage_date', 'domicile_issue_date', 'disability_certificate_issue_date', 'social_category_certificate_issue_date', 'employment_registration_date', 'employment_registration_valid_upto_date', 'discharge_date', 'dswro_registration_date', 'ncc_b_certificate_date', 'is_nss_b_certificate_date', 'debarred_from_date', 'debarred_to_date'], 'safe'],
            [['guid'], 'string', 'max' => 36],
            [['phone_no'], 'string', 'max' => 20],
            [['name', 'father_name', 'mother_name', 'name_after_marriage', 'spouse_name'], 'string', 'max' => 255],
            [['birth_city', 'dswro_office_name'], 'string', 'max' => 100],
            [['domicile_no', 'disability_certificate_no', 'social_category_certificate_no', 'employment_registration_no', 'discharge_certificate_no', 'dswro_registration_no'], 'string', 'max' => 30],
            [['guid'], 'unique'],
            [['applicant_classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantClassified::className(), 'targetAttribute' => ['applicant_classified_id' => 'id']],
            [['birth_state_id', 'birth_district_id', 'birth_tehsil_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstTehsil::className(), 'targetAttribute' => ['birth_state_id' => 'state_id', 'birth_district_id' => 'district_id', 'birth_tehsil_id' => 'id']],
            [['disability_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['disability_id' => 'id']],
            [['domicile_issue_state', 'domicile_issue_district'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['domicile_issue_state' => 'state_id', 'domicile_issue_district' => 'id']],
            [['employer_type'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['employer_type' => 'id']],
            [['employment_registration_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstEmploymentOffice::className(), 'targetAttribute' => ['employment_registration_office_id' => 'id']],
            [['social_category_certificate_issue_authority_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['social_category_certificate_issue_authority_id' => 'id']],
            [['social_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['social_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Guid',
            'applicant_classified_id' => 'Applicant Classified ID',
            'reference_no' => 'Reference No',
            'phone_no' => 'Phone No',
            'name' => 'Name',
            'gender' => 'Gender',
            'father_name' => 'Father Name',
            'mother_name' => 'Mother Name',
            'date_of_birth' => 'Date Of Birth',
            'birth_state_id' => 'Birth State ID',
            'birth_district_id' => 'Birth District ID',
            'birth_tehsil_id' => 'Birth Tehsil ID',
            'birth_city' => 'Birth City',
            'permanent_residence_type' => 'Permanent Residence Type',
            'nationality' => 'Nationality',
            'marital_status' => 'Marital Status',
            'name_after_marriage' => 'Name After Marriage',
            'spouse_name' => 'Spouse Name',
            'marriage_date' => 'Marriage Date',
            'no_of_children' => 'No Of Children',
            'is_domiciled' => 'Is Domiciled',
            'domicile_no' => 'Domicile No',
            'domicile_issue_state' => 'Domicile Issue State',
            'domicile_issue_district' => 'Domicile Issue District',
            'domicile_issue_date' => 'Domicile Issue Date',
            'disability_id' => 'Disability ID',
            'disability_percentage' => 'Disability Percentage',
            'disability_certificate_no' => 'Disability Certificate No',
            'disability_certificate_issue_date' => 'Disability Certificate Issue Date',
            'social_category_id' => 'Social Category ID',
            'social_category_certificate_no' => 'Social Category Certificate No',
            'social_category_certificate_issue_authority_id' => 'Social Category Certificate Issue Authority ID',
            'social_category_certificate_issue_date' => 'Social Category Certificate Issue Date',
            'social_category_certificate_state_code' => 'Social Category Certificate State Code',
            'social_category_certificate_district_code' => 'Social Category Certificate District Code',
            'is_non_creamy_layer' => 'Is Non Creamy Layer',
            'employer_type' => 'Employer Type',
            'employment_nature' => 'Employment Nature',
            'is_employment_registered' => 'Is Employment Registered',
            'employment_registration_no' => 'Employment Registration No',
            'employment_registration_office_id' => 'Employment Registration Office ID',
            'employment_registration_date' => 'Employment Registration Date',
            'employment_registration_valid_upto_date' => 'Employment Registration Valid Upto Date',
            'is_exserviceman' => 'Is Exserviceman',
            'is_dismissed_from_defence' => 'Is Dismissed From Defence',
            'discharge_certificate_no' => 'Discharge Certificate No',
            'discharge_date' => 'Discharge Date',
            'is_voluntary_retirement' => 'Is Voluntary Retirement',
            'is_relieved_on_medical' => 'Is Relieved On Medical',
            'is_dswro_registered' => 'Is Dswro Registered',
            'dswro_registration_no' => 'Dswro Registration No',
            'dswro_registration_date' => 'Dswro Registration Date',
            'dswro_office_name' => 'Dswro Office Name',
            'have_ncc_nss' => 'Have Ncc Nss',
            'have_served_territorial_army' => 'Have Served Territorial Army',
            'is_ncc_b_certificate' => 'Is Ncc B Certificate',
            'ncc_b_certificate_date' => 'Ncc B Certificate Date',
            'is_ncc_c_certificate' => 'Is Ncc C Certificate',
            'is_nss_b_certificate' => 'Is Nss B Certificate',
            'is_nss_b_certificate_date' => 'Is Nss B Certificate Date',
            'is_nss_c_certificate' => 'Is Nss C Certificate',
            'is_criminal_case' => 'Is Criminal Case',
            'is_criminal_proceed_complete' => 'Is Criminal Proceed Complete',
            'criminal_sentenance_data' => 'Criminal Sentenance Data',
            'is_debarred' => 'Is Debarred',
            'debarred_from_date' => 'Debarred From Date',
            'debarred_to_date' => 'Debarred To Date',
            'same_as_present_address' => 'Same As Present Address',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantClassified()
    {
        return $this->hasOne(ApplicantClassified::className(), ['id' => 'applicant_classified_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBirthState()
    {
        return $this->hasOne(MstTehsil::className(), ['state_id' => 'birth_state_id', 'district_id' => 'birth_district_id', 'id' => 'birth_tehsil_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisability()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'disability_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicileIssueState()
    {
        return $this->hasOne(MstDistrict::className(), ['state_id' => 'domicile_issue_state', 'id' => 'domicile_issue_district']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployerType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'employer_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmploymentRegistrationOffice()
    {
        return $this->hasOne(MstEmploymentOffice::className(), ['id' => 'employment_registration_office_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialCategoryCertificateIssueAuthority()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'social_category_certificate_issue_authority_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialCategory()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'social_category_id']);
    }
}
