<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_detail".
 *
 * @property int $id
 * @property int|null $applicant_post_id
 * @property int|null $reference_no
 * @property int|null $is_aadhaar_card_holder
 * @property int|null $aadhaar_no
 * @property string|null $name_on_aadhaar
 * @property int|null $identity_type_id
 * @property string|null $identity_certificate_no
 * @property string|null $identity_type_display
 * @property int|null $std_code
 * @property string|null $phone_no
 * @property int|null $alternate_mobile
 * @property string|null $name
 * @property string|null $gender
 * @property int|null $father_salutation
 * @property string|null $father_name
 * @property int|null $mother_salutation
 * @property string|null $mother_name
 * @property string|null $date_of_birth
 * @property int|null $birth_state_code
 * @property int|null $birth_district_code
 * @property int|null $birth_tehsil_code
 * @property string|null $birth_tehsil_name
 * @property string|null $birth_city
 * @property int $birth_certificate_type
 * @property int|null $permanent_residence_type 1: plain, 2: Hill
 * @property int|null $nationality 1: Indian, 2: Other
 * @property int $is_orphan
 * @property string|null $orphan_name
 * @property string|null $orphan_certificate_no
 * @property string|null $orphan_certificate_issue_date
 * @property string|null $orphan_authority
 * @property int|null $marital_status
 * @property string|null $name_after_marriage
 * @property string|null $spouse_name
 * @property string|null $marriage_date
 * @property int|null $no_of_children only living children
 * @property string|null $identification_mark1
 * @property string|null $identification_mark2
 * @property int|null $mothertongue
 * @property string|null $other_mothertongue
 * @property int|null $is_domiciled 0: No, 1 : Yes
 * @property int|null $want_to_apply_reservation
 * @property string|null $domicile_no
 * @property int|null $domicile_issue_state
 * @property int|null $domicile_issue_district
 * @property string|null $domicile_issue_date
 * @property int|null $is_high_school_passed_from_uttarakhand
 * @property int|null $high_school_passing_state
 * @property int|null $high_school_passing_district
 * @property string|null $high_school_passing_school
 * @property int|null $is_parents_non_transferable_from_utknd
 * @property string|null $parents_department_name
 * @property string|null $parents_department_date_of_joining
 * @property int|null $disability_id
 * @property int|null $disability_percentage
 * @property string|null $disability_certificate_no
 * @property string|null $disability_certificate_issue_date
 * @property int|null $social_category_id
 * @property string|null $social_category_certificate_no
 * @property int|null $social_category_certificate_issue_authority_id
 * @property string|null $social_category_certificate_issue_date
 * @property string|null $social_category_certificate_valid_upto_date
 * @property int|null $social_category_certificate_state_code
 * @property int|null $social_category_certificate_district_code
 * @property int|null $is_non_creamy_layer
 * @property int|null $is_employed
 * @property int|null $employer_type_id
 * @property int|null $employment_nature 1: permanent, 2: temporary, 3: contractual
 * @property int|null $is_employment_registered
 * @property string|null $employment_registration_no
 * @property int|null $employment_registration_office_id
 * @property string|null $employment_registration_date
 * @property string|null $employment_registration_valid_upto_date
 * @property int|null $is_dependent_freedom_fighter
 * @property string|null $freedom_fighter_name
 * @property string|null $freedom_fighter_relation
 * @property string|null $freedom_fighter_certificate_no
 * @property string|null $freedom_fighter_issue_date
 * @property int|null $is_exserviceman
 * @property string|null $exserviceman_qualification_certificate
 * @property int|null $is_dismissed_from_defence
 * @property string|null $discharge_certificate_no
 * @property string|null $discharge_date
 * @property int|null $is_voluntary_retirement
 * @property int|null $is_relieved_on_medical
 * @property int|null $is_dswro_registered
 * @property string|null $dswro_registration_no
 * @property string|null $dswro_registration_date
 * @property string|null $dswro_office_name
 * @property string|null $dswro_registration_upto_date
 * @property int|null $have_ncc_nss
 * @property int|null $have_served_territorial_army
 * @property int|null $is_ncc_b_certificate
 * @property string|null $ncc_b_certificate_date
 * @property int|null $is_ncc_c_certificate
 * @property string|null $ncc_c_certificate_date
 * @property int|null $is_nss_b_certificate
 * @property string|null $nss_b_certificate_date
 * @property int|null $is_nss_c_certificate
 * @property string|null $nss_c_certificate_date
 * @property int|null $is_criminal_case
 * @property int|null $is_criminal_proceed_complete
 * @property string|null $criminal_sentenance_data
 * @property int|null $is_debarred
 * @property int|null $residence_place
 * @property int|null $high_class_schooling_place
 * @property int|null $qualifying_examination
 * @property int|null $father_qualification_id
 * @property int|null $father_occupation_id
 * @property int|null $mother_qualification_id
 * @property int|null $mother_occupation_id
 * @property int|null $preparation_mode
 * @property int|null $family_annual_income
 * @property string|null $debarred_from_date
 * @property string|null $debarred_to_date
 * @property int|null $is_correspondance_permanent_address
 * @property int|null $is_uttrakhand_women
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property MstDistrict $birthDistrictCode
 * @property MstState $birthStateCode
 * @property MstTehsil $birthTehsilCode
 * @property MstDistrict $domicileIssueState
 * @property MstDistrict $socialCategoryCertificateStateCode
 * @property MstDistrict $highSchoolPassingState
 * @property MstListType $fatherOccupation
 * @property MstListType $fatherQualification
 * @property MstListType $identityType
 * @property MstListType $motherOccupation
 * @property MstListType $motherQualification
 * @property MstListType $socialCategoryCertificateIssueAuthority
 * @property ApplicantPost $applicantPost
 * @property MstListType $disability
 * @property MstListType $employerType
 * @property MstListType $employmentRegistrationOffice
 * @property MstListType $socialCategory
 */
class ApplicantDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'reference_no', 'is_aadhaar_card_holder', 'aadhaar_no', 'identity_type_id', 'std_code', 'alternate_mobile', 'father_salutation', 'mother_salutation', 'birth_state_code', 'birth_district_code', 'birth_tehsil_code', 'birth_certificate_type', 'permanent_residence_type', 'nationality', 'is_orphan', 'marital_status', 'no_of_children', 'mothertongue', 'is_domiciled', 'want_to_apply_reservation', 'domicile_issue_state', 'domicile_issue_district', 'is_high_school_passed_from_uttarakhand', 'high_school_passing_state', 'high_school_passing_district', 'is_parents_non_transferable_from_utknd', 'disability_id', 'disability_percentage', 'social_category_id', 'social_category_certificate_issue_authority_id', 'social_category_certificate_state_code', 'social_category_certificate_district_code', 'is_non_creamy_layer', 'is_employed', 'employer_type_id', 'employment_nature', 'is_employment_registered', 'employment_registration_office_id', 'is_dependent_freedom_fighter', 'is_exserviceman', 'is_dismissed_from_defence', 'is_voluntary_retirement', 'is_relieved_on_medical', 'is_dswro_registered', 'have_ncc_nss', 'have_served_territorial_army', 'is_ncc_b_certificate', 'is_ncc_c_certificate', 'is_nss_b_certificate', 'is_nss_c_certificate', 'is_criminal_case', 'is_criminal_proceed_complete', 'is_debarred', 'residence_place', 'high_class_schooling_place', 'qualifying_examination', 'father_qualification_id', 'father_occupation_id', 'mother_qualification_id', 'mother_occupation_id', 'preparation_mode', 'family_annual_income', 'is_correspondance_permanent_address', 'is_uttrakhand_women', 'created_on', 'modified_on'], 'integer'],
            [['gender', 'criminal_sentenance_data'], 'string'],
            [['date_of_birth', 'orphan_certificate_issue_date', 'marriage_date', 'domicile_issue_date', 'parents_department_date_of_joining', 'disability_certificate_issue_date', 'social_category_certificate_issue_date', 'social_category_certificate_valid_upto_date', 'employment_registration_date', 'employment_registration_valid_upto_date', 'freedom_fighter_issue_date', 'discharge_date', 'dswro_registration_date', 'dswro_registration_upto_date', 'ncc_b_certificate_date', 'ncc_c_certificate_date', 'nss_b_certificate_date', 'nss_c_certificate_date', 'debarred_from_date', 'debarred_to_date'], 'safe'],
            [['name_on_aadhaar'], 'string', 'max' => 150],
            [['identity_certificate_no', 'name', 'father_name', 'mother_name', 'name_after_marriage', 'spouse_name', 'identification_mark1', 'identification_mark2'], 'string', 'max' => 255],
            [['identity_type_display', 'other_mothertongue', 'freedom_fighter_certificate_no'], 'string', 'max' => 50],
            [['phone_no'], 'string', 'max' => 14],
            [['birth_tehsil_name'], 'string', 'max' => 64],
            [['birth_city', 'orphan_name', 'orphan_certificate_no', 'orphan_authority', 'high_school_passing_school', 'parents_department_name', 'freedom_fighter_name', 'freedom_fighter_relation', 'exserviceman_qualification_certificate', 'dswro_office_name'], 'string', 'max' => 100],
            [['domicile_no', 'disability_certificate_no', 'social_category_certificate_no', 'employment_registration_no', 'discharge_certificate_no', 'dswro_registration_no'], 'string', 'max' => 30],
            [['birth_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['birth_district_code' => 'code']],
            [['birth_state_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstState::className(), 'targetAttribute' => ['birth_state_code' => 'code']],
            [['birth_tehsil_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstTehsil::className(), 'targetAttribute' => ['birth_tehsil_code' => 'code']],
            [['domicile_issue_state', 'domicile_issue_district'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['domicile_issue_state' => 'state_code', 'domicile_issue_district' => 'code']],
            [['social_category_certificate_state_code', 'social_category_certificate_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['social_category_certificate_state_code' => 'state_code', 'social_category_certificate_district_code' => 'code']],
            [['high_school_passing_state', 'high_school_passing_district'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['high_school_passing_state' => 'state_code', 'high_school_passing_district' => 'code']],
            [['father_occupation_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['father_occupation_id' => 'id']],
            [['father_qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['father_qualification_id' => 'id']],
            [['identity_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['identity_type_id' => 'id']],
            [['mother_occupation_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['mother_occupation_id' => 'id']],
            [['mother_qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['mother_qualification_id' => 'id']],
            [['social_category_certificate_issue_authority_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['social_category_certificate_issue_authority_id' => 'id']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['disability_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['disability_id' => 'id']],
            [['employer_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['employer_type_id' => 'id']],
            [['employment_registration_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['employment_registration_office_id' => 'id']],
            [['social_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['social_category_id' => 'id']],
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
            'reference_no' => Yii::t('app', 'Reference No'),
            'is_aadhaar_card_holder' => Yii::t('app', 'Is Aadhaar Card Holder'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'name_on_aadhaar' => Yii::t('app', 'Name On Aadhaar'),
            'identity_type_id' => Yii::t('app', 'Identity Type ID'),
            'identity_certificate_no' => Yii::t('app', 'Identity Certificate No'),
            'identity_type_display' => Yii::t('app', 'Identity Type Display'),
            'std_code' => Yii::t('app', 'Std Code'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'alternate_mobile' => Yii::t('app', 'Alternate Mobile'),
            'name' => Yii::t('app', 'Name'),
            'gender' => Yii::t('app', 'Gender'),
            'father_salutation' => Yii::t('app', 'Father Salutation'),
            'father_name' => Yii::t('app', 'Father Name'),
            'mother_salutation' => Yii::t('app', 'Mother Salutation'),
            'mother_name' => Yii::t('app', 'Mother Name'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'birth_state_code' => Yii::t('app', 'Birth State Code'),
            'birth_district_code' => Yii::t('app', 'Birth District Code'),
            'birth_tehsil_code' => Yii::t('app', 'Birth Tehsil Code'),
            'birth_tehsil_name' => Yii::t('app', 'Birth Tehsil Name'),
            'birth_city' => Yii::t('app', 'Birth City'),
            'birth_certificate_type' => Yii::t('app', 'Birth Certificate Type'),
            'permanent_residence_type' => Yii::t('app', 'Permanent Residence Type'),
            'nationality' => Yii::t('app', 'Nationality'),
            'is_orphan' => Yii::t('app', 'Is Orphan'),
            'orphan_name' => Yii::t('app', 'Orphan Name'),
            'orphan_certificate_no' => Yii::t('app', 'Orphan Certificate No'),
            'orphan_certificate_issue_date' => Yii::t('app', 'Orphan Certificate Issue Date'),
            'orphan_authority' => Yii::t('app', 'Orphan Authority'),
            'marital_status' => Yii::t('app', 'Marital Status'),
            'name_after_marriage' => Yii::t('app', 'Name After Marriage'),
            'spouse_name' => Yii::t('app', 'Spouse Name'),
            'marriage_date' => Yii::t('app', 'Marriage Date'),
            'no_of_children' => Yii::t('app', 'No Of Children'),
            'identification_mark1' => Yii::t('app', 'Identification Mark1'),
            'identification_mark2' => Yii::t('app', 'Identification Mark2'),
            'mothertongue' => Yii::t('app', 'Mothertongue'),
            'other_mothertongue' => Yii::t('app', 'Other Mothertongue'),
            'is_domiciled' => Yii::t('app', 'Is Domiciled'),
            'want_to_apply_reservation' => Yii::t('app', 'Want To Apply Reservation'),
            'domicile_no' => Yii::t('app', 'Domicile No'),
            'domicile_issue_state' => Yii::t('app', 'Domicile Issue State'),
            'domicile_issue_district' => Yii::t('app', 'Domicile Issue District'),
            'domicile_issue_date' => Yii::t('app', 'Domicile Issue Date'),
            'is_high_school_passed_from_uttarakhand' => Yii::t('app', 'Is High School Passed From Uttarakhand'),
            'high_school_passing_state' => Yii::t('app', 'High School Passing State'),
            'high_school_passing_district' => Yii::t('app', 'High School Passing District'),
            'high_school_passing_school' => Yii::t('app', 'High School Passing School'),
            'is_parents_non_transferable_from_utknd' => Yii::t('app', 'Is Parents Non Transferable From Utknd'),
            'parents_department_name' => Yii::t('app', 'Parents Department Name'),
            'parents_department_date_of_joining' => Yii::t('app', 'Parents Department Date Of Joining'),
            'disability_id' => Yii::t('app', 'Disability ID'),
            'disability_percentage' => Yii::t('app', 'Disability Percentage'),
            'disability_certificate_no' => Yii::t('app', 'Disability Certificate No'),
            'disability_certificate_issue_date' => Yii::t('app', 'Disability Certificate Issue Date'),
            'social_category_id' => Yii::t('app', 'Social Category ID'),
            'social_category_certificate_no' => Yii::t('app', 'Social Category Certificate No'),
            'social_category_certificate_issue_authority_id' => Yii::t('app', 'Social Category Certificate Issue Authority ID'),
            'social_category_certificate_issue_date' => Yii::t('app', 'Social Category Certificate Issue Date'),
            'social_category_certificate_valid_upto_date' => Yii::t('app', 'Social Category Certificate Valid Upto Date'),
            'social_category_certificate_state_code' => Yii::t('app', 'Social Category Certificate State Code'),
            'social_category_certificate_district_code' => Yii::t('app', 'Social Category Certificate District Code'),
            'is_non_creamy_layer' => Yii::t('app', 'Is Non Creamy Layer'),
            'is_employed' => Yii::t('app', 'Is Employed'),
            'employer_type_id' => Yii::t('app', 'Employer Type ID'),
            'employment_nature' => Yii::t('app', 'Employment Nature'),
            'is_employment_registered' => Yii::t('app', 'Is Employment Registered'),
            'employment_registration_no' => Yii::t('app', 'Employment Registration No'),
            'employment_registration_office_id' => Yii::t('app', 'Employment Registration Office ID'),
            'employment_registration_date' => Yii::t('app', 'Employment Registration Date'),
            'employment_registration_valid_upto_date' => Yii::t('app', 'Employment Registration Valid Upto Date'),
            'is_dependent_freedom_fighter' => Yii::t('app', 'Is Dependent Freedom Fighter'),
            'freedom_fighter_name' => Yii::t('app', 'Freedom Fighter Name'),
            'freedom_fighter_relation' => Yii::t('app', 'Freedom Fighter Relation'),
            'freedom_fighter_certificate_no' => Yii::t('app', 'Freedom Fighter Certificate No'),
            'freedom_fighter_issue_date' => Yii::t('app', 'Freedom Fighter Issue Date'),
            'is_exserviceman' => Yii::t('app', 'Is Exserviceman'),
            'exserviceman_qualification_certificate' => Yii::t('app', 'Exserviceman Qualification Certificate'),
            'is_dismissed_from_defence' => Yii::t('app', 'Is Dismissed From Defence'),
            'discharge_certificate_no' => Yii::t('app', 'Discharge Certificate No'),
            'discharge_date' => Yii::t('app', 'Discharge Date'),
            'is_voluntary_retirement' => Yii::t('app', 'Is Voluntary Retirement'),
            'is_relieved_on_medical' => Yii::t('app', 'Is Relieved On Medical'),
            'is_dswro_registered' => Yii::t('app', 'Is Dswro Registered'),
            'dswro_registration_no' => Yii::t('app', 'Dswro Registration No'),
            'dswro_registration_date' => Yii::t('app', 'Dswro Registration Date'),
            'dswro_office_name' => Yii::t('app', 'Dswro Office Name'),
            'dswro_registration_upto_date' => Yii::t('app', 'Dswro Registration Upto Date'),
            'have_ncc_nss' => Yii::t('app', 'Have Ncc Nss'),
            'have_served_territorial_army' => Yii::t('app', 'Have Served Territorial Army'),
            'is_ncc_b_certificate' => Yii::t('app', 'Is Ncc B Certificate'),
            'ncc_b_certificate_date' => Yii::t('app', 'Ncc B Certificate Date'),
            'is_ncc_c_certificate' => Yii::t('app', 'Is Ncc C Certificate'),
            'ncc_c_certificate_date' => Yii::t('app', 'Ncc C Certificate Date'),
            'is_nss_b_certificate' => Yii::t('app', 'Is Nss B Certificate'),
            'nss_b_certificate_date' => Yii::t('app', 'Nss B Certificate Date'),
            'is_nss_c_certificate' => Yii::t('app', 'Is Nss C Certificate'),
            'nss_c_certificate_date' => Yii::t('app', 'Nss C Certificate Date'),
            'is_criminal_case' => Yii::t('app', 'Is Criminal Case'),
            'is_criminal_proceed_complete' => Yii::t('app', 'Is Criminal Proceed Complete'),
            'criminal_sentenance_data' => Yii::t('app', 'Criminal Sentenance Data'),
            'is_debarred' => Yii::t('app', 'Is Debarred'),
            'residence_place' => Yii::t('app', 'Residence Place'),
            'high_class_schooling_place' => Yii::t('app', 'High Class Schooling Place'),
            'qualifying_examination' => Yii::t('app', 'Qualifying Examination'),
            'father_qualification_id' => Yii::t('app', 'Father Qualification ID'),
            'father_occupation_id' => Yii::t('app', 'Father Occupation ID'),
            'mother_qualification_id' => Yii::t('app', 'Mother Qualification ID'),
            'mother_occupation_id' => Yii::t('app', 'Mother Occupation ID'),
            'preparation_mode' => Yii::t('app', 'Preparation Mode'),
            'family_annual_income' => Yii::t('app', 'Family Annual Income'),
            'debarred_from_date' => Yii::t('app', 'Debarred From Date'),
            'debarred_to_date' => Yii::t('app', 'Debarred To Date'),
            'is_correspondance_permanent_address' => Yii::t('app', 'Is Correspondance Permanent Address'),
            'is_uttrakhand_women' => Yii::t('app', 'Is Uttrakhand Women'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[BirthDistrictCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBirthDistrictCode()
    {
        return $this->hasOne(MstDistrict::className(), ['code' => 'birth_district_code']);
    }

    /**
     * Gets query for [[BirthStateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBirthStateCode()
    {
        return $this->hasOne(MstState::className(), ['code' => 'birth_state_code']);
    }

    /**
     * Gets query for [[BirthTehsilCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBirthTehsilCode()
    {
        return $this->hasOne(MstTehsil::className(), ['code' => 'birth_tehsil_code']);
    }

    /**
     * Gets query for [[DomicileIssueState]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDomicileIssueState()
    {
        return $this->hasOne(MstDistrict::className(), ['state_code' => 'domicile_issue_state', 'code' => 'domicile_issue_district']);
    }

    /**
     * Gets query for [[SocialCategoryCertificateStateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocialCategoryCertificateStateCode()
    {
        return $this->hasOne(MstDistrict::className(), ['state_code' => 'social_category_certificate_state_code', 'code' => 'social_category_certificate_district_code']);
    }

    /**
     * Gets query for [[HighSchoolPassingState]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHighSchoolPassingState()
    {
        return $this->hasOne(MstDistrict::className(), ['state_code' => 'high_school_passing_state', 'code' => 'high_school_passing_district']);
    }

    /**
     * Gets query for [[FatherOccupation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatherOccupation()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'father_occupation_id']);
    }

    /**
     * Gets query for [[FatherQualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatherQualification()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'father_qualification_id']);
    }

    /**
     * Gets query for [[IdentityType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdentityType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'identity_type_id']);
    }

    /**
     * Gets query for [[MotherOccupation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotherOccupation()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'mother_occupation_id']);
    }

    /**
     * Gets query for [[MotherQualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotherQualification()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'mother_qualification_id']);
    }

    /**
     * Gets query for [[SocialCategoryCertificateIssueAuthority]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocialCategoryCertificateIssueAuthority()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'social_category_certificate_issue_authority_id']);
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
     * Gets query for [[Disability]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisability()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'disability_id']);
    }

    /**
     * Gets query for [[EmployerType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployerType()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'employer_type_id']);
    }

    /**
     * Gets query for [[EmploymentRegistrationOffice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmploymentRegistrationOffice()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'employment_registration_office_id']);
    }

    /**
     * Gets query for [[SocialCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocialCategory()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'social_category_id']);
    }
}
