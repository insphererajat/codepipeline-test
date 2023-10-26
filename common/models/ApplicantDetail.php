<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\caching\ModelCache;
use common\models\base\ApplicantDetail as BaseApplicantDetail;

/**
 * Description of ApplicantDetail
 *
 * @author Amit Handa
 */
class ApplicantDetail extends BaseApplicantDetail
{
    
    //permanent_residence_type
    const PLAIN = 1;
    const HILL = 2;
    
    //domicile_issue_state
    const DOMICILE_ISSUE_STATE = 9102;
    //Place of Residence/ Place of 10th class Schooling
    const RURAL = 1;
    const URBAN = 2;
    //Qualifying Examination
    const QUALIFYING_GOVERNMENT = 1;
    const QUALIFYING_PRIVATE = 2;
    //Mode of Preparation
    const PREPARATION_SELF_STUDY = 1;
    const PREPARATION_COACHING = 2;
    //Family Annual Income
    const INCOME_1 = 1;
    const INCOME_2 = 2;
    const INCOME_3 = 3;
    const INCOME_4 = 4;
    const INCOME_5 = 5;
    const INCOME_6 = 6;
    //Nationality
    const NATIONALITY_INDIA = 1;
    const NATIONALITY_OTHER = 2;
    //Marital Status
    const MARRIED = 1;
    const UNMARRIED = 2;
    const DIVORCED = 3;
    
    //Father Salutation
    const MR = 1;
    const SHRI = 2;
    //Mother Salutation
    const MRS = 1;
    const SMT = 2;
    
    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';
    const GENDER_TRANSGENDER = 'TRANSGENDER';
    
    // mother tougue
    const TONGUE_HINDI = 1;
    const TONGUE_ENGLISH = 2;
    const TONGUE_OTHER = 3;
    
    //birth_certificate_type
    const BIRTH_CERTIFICATE = 0;
    const SCHOOL_LEAVING_CERTIFICATE = 1;
    const HIGH_SCHOOL_CERTIFICATE = 2;
    
    public static function getBirthCertificate($key = null)
    {
        $list = [
            self::BIRTH_CERTIFICATE => 'Birth Certificate',
            self::SCHOOL_LEAVING_CERTIFICATE => 'School Leaving Certificate',
            self::HIGH_SCHOOL_CERTIFICATE => '10th Certificate'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }

    public static function getMotherTongue($key = null)
    {
        $list = [
            self::TONGUE_HINDI => 'HINDI',
            self::TONGUE_ENGLISH => 'ENGLISH',
            self::TONGUE_OTHER => 'OTHER'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    

    public static function getFatherSalutation($key = null)
    {
        $list = [
            self::MR => 'Mr.',
            self::SHRI => 'Shri'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getGenderDropdown($key = null)
    {
        $list = [
            self::GENDER_MALE => 'MALE',
            self::GENDER_FEMALE => 'FEMALE',
            self::GENDER_TRANSGENDER => 'TRANSGENDER'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getMotherSalutation($key = null)
    {
        $list = [
            self::MRS => 'Mrs.',
            self::SMT => 'Smt.'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }

    
    public static function getMaritalStatus($key = null)
    {
        $list = [
            self::MARRIED => 'MARRIED',
            self::UNMARRIED => 'UNMARRIED',
            self::DIVORCED => 'DIVORCED'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getNationality($key = null)
    {
        $list = [
            self::NATIONALITY_INDIA => 'INDIAN'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }

        /**
     * Place of Residence/ Place of 10th class Schooling
     * @param type $key
     * @return string
     */
    public static function getResidencePlace($key = null)
    {
        $list = [
            self::RURAL => 'Rural',
            self::URBAN => 'Urban'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getQualifyingExamination($key = null)
    {
        $list = [
            self::QUALIFYING_GOVERNMENT => 'Government',
            self::QUALIFYING_PRIVATE => 'Private'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getModeOfPreparation($key = null)
    {
        $list = [
            self::PREPARATION_SELF_STUDY => 'Self Study',
            self::PREPARATION_COACHING => 'Coaching'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getAnnualIncome($key = null)
    {
        $list = [
            self::INCOME_1 => '2.5 lakh to 8 lakh',
            self::INCOME_2 => '8.1 lakh to 15 lakh',
            self::INCOME_3 => '15.1 lakh to 25 lakh',
            self::INCOME_4 => '25.1 lakh to 35 lakh',
            self::INCOME_5 => '35 lakh to above',
            self::INCOME_6 => 'Less Than 2.5 lakh',
            
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    
    public static function getPermanentresidenceType($key = null)
    {
        $list = [
            self::PLAIN => 'Plain',
            self::HILL => 'Hill'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ]
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['name_on_aadhaar', 'identity_certificate_no', 'phone_no', 'name', 'father_name', 'mother_name', 'birth_tehsil_name', 'birth_city', 'name_after_marriage', 'spouse_name', 'identification_mark1', 'identification_mark2', 'other_mothertongue', 'domicile_no', 'high_school_passing_school', 'parents_department_name', 'disability_certificate_no', 'social_category_certificate_no', 'employment_registration_no', 'freedom_fighter_name', 'freedom_fighter_relation', 'freedom_fighter_certificate_no', 'discharge_certificate_no', 'dswro_registration_no', 'dswro_office_name']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'name_on_aadhaar' => 'cleanEncodeUTF8',
                        'identity_certificate_no' => 'cleanEncodeUTF8',
                        'phone_no' => 'cleanEncodeUTF8',
                        'name' => 'cleanEncodeUTF8',
                        'father_name' => 'cleanEncodeUTF8',
                        'mother_name' => 'cleanEncodeUTF8',
                        'birth_tehsil_name' => 'cleanEncodeUTF8',
                        'birth_city' => 'cleanEncodeUTF8',
                        'name_after_marriage' => 'cleanEncodeUTF8',
                        'spouse_name' => 'cleanEncodeUTF8',
                        'identification_mark1' => 'cleanEncodeUTF8',
                        'identification_mark2' => 'cleanEncodeUTF8',
                        'other_mothertongue' => 'cleanEncodeUTF8',
                        'domicile_no' => 'cleanEncodeUTF8',
                        'high_school_passing_school' => 'cleanEncodeUTF8',
                        'parents_department_name' => 'cleanEncodeUTF8',
                        'disability_certificate_no' => 'cleanEncodeUTF8',
                        'social_category_certificate_no' => 'cleanEncodeUTF8',
                        'employment_registration_no' => 'cleanEncodeUTF8',
                        'freedom_fighter_name' => 'cleanEncodeUTF8',
                        'freedom_fighter_relation' => 'cleanEncodeUTF8',
                        'freedom_fighter_certificate_no' => 'cleanEncodeUTF8',
                        'discharge_certificate_no' => 'cleanEncodeUTF8',
                        'dswro_registration_no' => 'cleanEncodeUTF8',
                        'dswro_office_name' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'name_on_aadhaar' => 'cleanEncodeUTF8',
                        'identity_certificate_no' => 'cleanEncodeUTF8',
                        'phone_no' => 'cleanEncodeUTF8',
                        'name' => 'cleanEncodeUTF8',
                        'father_name' => 'cleanEncodeUTF8',
                        'mother_name' => 'cleanEncodeUTF8',
                        'birth_tehsil_name' => 'cleanEncodeUTF8',
                        'birth_city' => 'cleanEncodeUTF8',
                        'name_after_marriage' => 'cleanEncodeUTF8',
                        'spouse_name' => 'cleanEncodeUTF8',
                        'identification_mark1' => 'cleanEncodeUTF8',
                        'identification_mark2' => 'cleanEncodeUTF8',
                        'other_mothertongue' => 'cleanEncodeUTF8',
                        'domicile_no' => 'cleanEncodeUTF8',
                        'high_school_passing_school' => 'cleanEncodeUTF8',
                        'parents_department_name' => 'cleanEncodeUTF8',
                        'disability_certificate_no' => 'cleanEncodeUTF8',
                        'social_category_certificate_no' => 'cleanEncodeUTF8',
                        'employment_registration_no' => 'cleanEncodeUTF8',
                        'freedom_fighter_name' => 'cleanEncodeUTF8',
                        'freedom_fighter_relation' => 'cleanEncodeUTF8',
                        'freedom_fighter_certificate_no' => 'cleanEncodeUTF8',
                        'discharge_certificate_no' => 'cleanEncodeUTF8',
                        'dswro_registration_no' => 'cleanEncodeUTF8',
                        'dswro_office_name' => 'cleanEncodeUTF8',
                    ]
                ]
            ],
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();


        $modelAQ->select($tableName . '.*');
        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }
        
        if (isset($params['notApplicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id !=:notApplicantPostId', [':notApplicantPostId' => $params['notApplicantPostId']]);
        }

        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
        }
        
        if (isset($params['dateOfBirth'])) {
            $date = date('Y-m-d', strtotime($params['dateOfBirth']));
            $modelAQ->andWhere($tableName . '.date_of_birth =:dateOfBirth', [':dateOfBirth' => $date]);
        }
        
        if (isset($params['fatherName'])) {
            $modelAQ->andWhere($tableName . '.father_name =:fatherName', [':fatherName' => $params['fatherName']]);
        }

        if (isset($params['motherName'])) {
            $modelAQ->andWhere($tableName . '.mother_name =:motherName', [':motherName' => $params['motherName']]);
        }
        
        if (isset($params['identityCertificateNo'])) {
            $modelAQ->andWhere($tableName . '.identity_certificate_no =:identityCertificateNo', [':identityCertificateNo' => $params['identityCertificateNo']]);
        }
        
        if (isset($params['birthStateCode'])) {
            $modelAQ->andWhere($tableName . '.birth_state_code =:birthStateCode', [':birthStateCode' => $params['birthStateCode']]);
        }
        
        if (isset($params['birthDistrictCode'])) {
            $modelAQ->andWhere($tableName . '.birth_district_code =:birthDistrictCode', [':birthDistrictCode' => $params['birthDistrictCode']]);
        }
        
        if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant_post.id = applicant_detail.applicant_post_id');

            if (isset($params['postId'])) {
                $modelAQ->andWhere('applicant_post.post_id =:postId', [':postId' => $params['postId']]);
            }
            
            if (isset($params['joinWithApplicant']) && in_array($params['joinWithApplicant'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
                $modelAQ->{$params['joinWithApplicant']}('applicant', 'applicant.id = applicant_post.applicant_id');

                if (isset($params['applicantName'])) {
                    $modelAQ->andWhere('applicant.name =:applicantName', [':applicantName' => $params['applicantName']]);
                }
            }
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByApplicantPostId($applicantPostId, $params = [])
    {

        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId], $params));
    }
    
    public static function findByIdentityCertificateNo($identityCertificateNo, $params = [])
    {

        return self::findByParams(\yii\helpers\ArrayHelper::merge(['identityCertificateNo' => $identityCertificateNo], $params));
    }

    public static function findByDateOfBirth($dateOfBirth, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['dateOfBirth' => $dateOfBirth], $params));
    }
    
    public function beforeSave($insert)
    {
        if($this->is_aadhaar_card_holder == ModelCache::IS_ACTIVE_NO)
        {
            $this->aadhaar_no = null;
            $this->name_on_aadhaar = null;
        }
        if($this->is_aadhaar_card_holder == ModelCache::IS_ACTIVE_YES)
        {
            $this->identity_type_id = null;
            $this->identity_certificate_no = null;
        }
        if($this->is_domiciled == ModelCache::IS_ACTIVE_NO)
        {
            $this->domicile_no = null;
            $this->domicile_issue_state = null;
            $this->domicile_issue_district = null;
            $this->domicile_issue_date = null;
            // parent category section
            $this->social_category_id = MstListType::UNRESERVED_GENERAL;
            //Are you Differently abled Person(PH)?
            //$this->disability_id = MstListType::NOT_APPLICABLE;
            //$this->is_exserviceman = ModelCache::IS_ACTIVE_NO;
            //$this->is_dependent_freedom_fighter = ModelCache::IS_ACTIVE_NO;
            // Other details
            $this->have_ncc_nss = null;
        }
        if($this->social_category_id == MstListType::UNRESERVED_GENERAL)
        {
            $this->is_non_creamy_layer = null;
            $this->social_category_certificate_issue_date = null;
            $this->social_category_certificate_no = null;
            $this->social_category_certificate_state_code = null;
            $this->social_category_certificate_district_code = null;
            $this->social_category_certificate_issue_authority_id = null;
            $this->social_category_certificate_issue_date = null;
        }
        if($this->social_category_id != MstListType::OBC)
        {
            $this->is_non_creamy_layer = null;
            $this->social_category_certificate_valid_upto_date = null;
        }
        if($this->disability_id == MstListType::NOT_APPLICABLE)
        {
            $this->disability_percentage = null;
            $this->disability_certificate_no = null;
            $this->disability_certificate_issue_date = null;
        }
        if($this->is_exserviceman == ModelCache::IS_ACTIVE_NO)
        {
            $this->is_dismissed_from_defence = null;
            $this->is_voluntary_retirement = null;
            $this->is_relieved_on_medical = null;
            $this->discharge_certificate_no = null;
            $this->discharge_date = null;
        }
        if($this->is_dismissed_from_defence == ModelCache::IS_ACTIVE_YES)
        {
            $this->is_voluntary_retirement = null;
            $this->is_relieved_on_medical = null;
            $this->discharge_certificate_no = null;
            $this->discharge_date = null;
        }
        if($this->is_dswro_registered == ModelCache::IS_ACTIVE_NO)
        {
            $this->dswro_registration_no = null;
            $this->dswro_office_name = null;
            $this->dswro_registration_date = null;
            $this->dswro_registration_upto_date = null;
        }
        if($this->is_voluntary_retirement == ModelCache::IS_ACTIVE_NO)
        {
            $this->is_relieved_on_medical = null;
        }
        if($this->is_dependent_freedom_fighter == ModelCache::IS_ACTIVE_NO)
        {
            $this->freedom_fighter_name = null;
            $this->freedom_fighter_relation = null;
            $this->freedom_fighter_certificate_no = null;
            $this->freedom_fighter_issue_date = null;
        }
        if($this->is_domiciled == ModelCache::IS_ACTIVE_YES)
        {
            $this->is_high_school_passed_from_uttarakhand = null;
            $this->high_school_passing_state = null;
            $this->high_school_passing_district = null;
            $this->high_school_passing_school = null;
            $this->is_parents_non_transferable_from_utknd = null;
            $this->parents_department_name = null;
            $this->parents_department_name = null;
        }
        if($this->is_high_school_passed_from_uttarakhand == ModelCache::IS_ACTIVE_NO)
        {
            $this->high_school_passing_state = null;
            $this->high_school_passing_district = null;
            $this->high_school_passing_school = null;
        }
        if($this->is_high_school_passed_from_uttarakhand == ModelCache::IS_ACTIVE_YES)
        {
            $this->is_parents_non_transferable_from_utknd = null;
            $this->parents_department_name = null;
            $this->parents_department_date_of_joining = null;
        }
        if($this->is_parents_non_transferable_from_utknd == ModelCache::IS_ACTIVE_NO)
        {
            $this->parents_department_name = null;
            $this->parents_department_date_of_joining = null;
        }
        
        // Others detail tab
        if($this->have_ncc_nss == ModelCache::IS_ACTIVE_NO)
        {
            $this->is_ncc_b_certificate = null;
            $this->ncc_b_certificate_date = null;
            $this->is_ncc_c_certificate = null;
            $this->ncc_c_certificate_date = null;
            $this->is_nss_b_certificate = null;
            $this->nss_b_certificate_date = null;
            $this->is_nss_c_certificate = null;
            $this->nss_c_certificate_date = null;
            
        }        
        if($this->is_ncc_b_certificate == ModelCache::IS_ACTIVE_NO)
        {
            $this->ncc_b_certificate_date = null;
        }
        if($this->is_ncc_c_certificate == ModelCache::IS_ACTIVE_NO)
        {
            $this->ncc_c_certificate_date = null;
        }
        if($this->is_nss_b_certificate == ModelCache::IS_ACTIVE_NO)
        {
            $this->nss_b_certificate_date = null;
        }
        if($this->is_nss_c_certificate == ModelCache::IS_ACTIVE_NO)
        {
            $this->nss_c_certificate_date = null;
        }
        if ($this->mothertongue != self::TONGUE_OTHER)
        {
            $this->other_mothertongue = null;
        }

        $this->date_of_birth = !empty($this->date_of_birth) ? date('Y-m-d', strtotime($this->date_of_birth)) : null;
        $this->marriage_date = !empty($this->marriage_date) ? date('Y-m-d', strtotime($this->marriage_date)) : null;
        $this->domicile_issue_date = !empty($this->domicile_issue_date) ? date('Y-m-d', strtotime($this->domicile_issue_date)) : null;
        $this->parents_department_date_of_joining = !empty($this->parents_department_date_of_joining) ? date('Y-m-d', strtotime($this->parents_department_date_of_joining)) : null;
        $this->disability_certificate_issue_date = !empty($this->disability_certificate_issue_date) ? date('Y-m-d', strtotime($this->disability_certificate_issue_date)) : null;
        $this->social_category_certificate_issue_date = !empty($this->social_category_certificate_issue_date) ? date('Y-m-d', strtotime($this->social_category_certificate_issue_date)) : null;
        $this->social_category_certificate_valid_upto_date = !empty($this->social_category_certificate_valid_upto_date) ? date('Y-m-d', strtotime($this->social_category_certificate_valid_upto_date)) : null;
        $this->employment_registration_date = !empty($this->employment_registration_date) ? date('Y-m-d', strtotime($this->employment_registration_date)) : null;
        $this->employment_registration_valid_upto_date = !empty($this->employment_registration_valid_upto_date) ? date('Y-m-d', strtotime($this->employment_registration_valid_upto_date)) : null;
        $this->freedom_fighter_issue_date = !empty($this->freedom_fighter_issue_date) ? date('Y-m-d', strtotime($this->freedom_fighter_issue_date)) : null;
        $this->discharge_date = !empty($this->discharge_date) ? date('Y-m-d', strtotime($this->discharge_date)) : null;
        $this->dswro_registration_date = !empty($this->dswro_registration_date) ? date('Y-m-d', strtotime($this->dswro_registration_date)) : null;
        $this->dswro_registration_upto_date = !empty($this->dswro_registration_upto_date) ? date('Y-m-d', strtotime($this->dswro_registration_upto_date)) : null;
        $this->ncc_b_certificate_date = !empty($this->ncc_b_certificate_date) ? date('Y-m-d', strtotime($this->ncc_b_certificate_date)) : null;
        $this->ncc_c_certificate_date = !empty($this->ncc_c_certificate_date) ? date('Y-m-d', strtotime($this->ncc_c_certificate_date)) : null;
        $this->nss_b_certificate_date = !empty($this->nss_b_certificate_date) ? date('Y-m-d', strtotime($this->nss_b_certificate_date)) : null;
        $this->nss_c_certificate_date = !empty($this->nss_c_certificate_date) ? date('Y-m-d', strtotime($this->nss_c_certificate_date)) : null;
        $this->debarred_from_date = !empty($this->debarred_from_date) ? date('Y-m-d', strtotime($this->debarred_from_date)) : null;
        $this->debarred_to_date = !empty($this->debarred_to_date) ? date('Y-m-d', strtotime($this->debarred_to_date)) : null;
        
        // other tehsil
        $this->birth_tehsil_code = ($this->birth_tehsil_code == location\MstTehsil::OTHER) ? null : $this->birth_tehsil_code;
        $this->birth_tehsil_name = ($this->birth_tehsil_code == null) ? $this->birth_tehsil_name : null;
        if ($insert) {

        }

        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = strip_tags($this->{$key});
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        
        $this->date_of_birth = !empty($this->date_of_birth) ? date('d-m-Y', strtotime($this->date_of_birth)) : null;
        $this->marriage_date = !empty($this->marriage_date) ? date('d-m-Y', strtotime($this->marriage_date)) : null;
        $this->domicile_issue_date = !empty($this->domicile_issue_date) ? date('d-m-Y', strtotime($this->domicile_issue_date)) : null;
        $this->parents_department_date_of_joining = !empty($this->parents_department_date_of_joining) ? date('d-m-Y', strtotime($this->parents_department_date_of_joining)) : null;
        $this->disability_certificate_issue_date = !empty($this->disability_certificate_issue_date) ? date('d-m-Y', strtotime($this->disability_certificate_issue_date)) : null;
        $this->social_category_certificate_issue_date = !empty($this->social_category_certificate_issue_date) ? date('d-m-Y', strtotime($this->social_category_certificate_issue_date)) : null;
        $this->social_category_certificate_valid_upto_date = !empty($this->social_category_certificate_valid_upto_date) ? date('d-m-Y', strtotime($this->social_category_certificate_valid_upto_date)) : null;
        $this->employment_registration_date = !empty($this->employment_registration_date) ? date('d-m-Y', strtotime($this->employment_registration_date)) : null;
        $this->employment_registration_valid_upto_date = !empty($this->employment_registration_valid_upto_date) ? date('d-m-Y', strtotime($this->employment_registration_valid_upto_date)) : null;
        $this->freedom_fighter_issue_date = !empty($this->freedom_fighter_issue_date) ? date('d-m-Y', strtotime($this->freedom_fighter_issue_date)) : null;
        $this->discharge_date = !empty($this->discharge_date) ? date('d-m-Y', strtotime($this->discharge_date)) : null;
        $this->dswro_registration_date = !empty($this->dswro_registration_date) ? date('d-m-Y', strtotime($this->dswro_registration_date)) : null;
        $this->dswro_registration_upto_date = !empty($this->dswro_registration_upto_date) ? date('d-m-Y', strtotime($this->dswro_registration_upto_date)) : null;
        $this->ncc_b_certificate_date = !empty($this->ncc_b_certificate_date) ? date('d-m-Y', strtotime($this->ncc_b_certificate_date)) : null;
        $this->ncc_c_certificate_date = !empty($this->ncc_c_certificate_date) ? date('d-m-Y', strtotime($this->ncc_c_certificate_date)) : null;
        $this->nss_b_certificate_date = !empty($this->nss_b_certificate_date) ? date('d-m-Y', strtotime($this->nss_b_certificate_date)) : null;
        $this->nss_c_certificate_date = !empty($this->nss_c_certificate_date) ? date('d-m-Y', strtotime($this->nss_c_certificate_date)) : null;
        $this->debarred_from_date = !empty($this->debarred_from_date) ? date('d-m-Y', strtotime($this->debarred_from_date)) : null;
        $this->debarred_to_date = !empty($this->debarred_to_date) ? date('d-m-Y', strtotime($this->debarred_to_date)) : null;
        $this->birth_tehsil_code = !empty($this->birth_tehsil_name) ? location\MstTehsil::OTHER : $this->birth_tehsil_code;

        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = htmlentities($attribute);
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        parent::afterFind();
    }
    
    public static function validateDob($applicantPostId, $classifiedId)
    {
        $applicantDetail = ApplicantDetail::findByApplicantPostId($applicantPostId, [
                    'selectCols' => [new \yii\db\Expression("date_of_birth, social_category_id, is_exserviceman, is_dswro_registered, is_dependent_freedom_fighter, disability_id")],
        ]);
        
        $mstClassified = MstClassified::findById($classifiedId);
        
        if (!empty($mstClassified) && \yii\helpers\ArrayHelper::isIn($mstClassified['id'], [MstClassified::STENOGRAPHER])) {
            $mstPostFee = MstPostFee::getPostFee($classifiedId, $applicantPostId);
            $ageValidatorCompoment = new \frontend\components\AgeValidatorComponent();
            $ageValidatorCompoment->classifiedId = $classifiedId;
            $ageValidatorCompoment->dob = $applicantDetail['date_of_birth'];
            $ageValidatorCompoment->minAge = $mstPostFee['min_age'];
            $ageValidatorCompoment->maxAge = $mstPostFee['max_age'];
            if (!$ageValidatorCompoment->validate()) {
                throw new \components\exceptions\AppException("Sorry, you are not eligible due to age criteria.");
            }
        } else if($mstClassified['id'] == MstClassified::SAMAJ_KALYAN_ADHIKARI) {
            if (!empty($applicantDetail['is_exserviceman']) || !empty($applicantDetail['is_dswro_registered'])) {
                if (strtotime($applicantDetail['date_of_birth']) < strtotime(MstClassified::DOB_EX_ARMY)) {
                    return false;
                }
            } else if ($applicantDetail['disability_id'] != MstListType::NOT_APPLICABLE) {
                if (strtotime($applicantDetail['date_of_birth']) < strtotime(MstClassified::DOB_PH)) {
                    return false;
                }
            } else if ($applicantDetail['is_dependent_freedom_fighter'] == ModelCache::IS_ACTIVE_YES) {
                if (strtotime($applicantDetail['date_of_birth']) < strtotime(MstClassified::DOB_SC_ST_OBC_DFF)) {
                    return false;
                }
            } else if (\yii\helpers\ArrayHelper::isIn($applicantDetail['social_category_id'], [MstListType::EWS, MstListType::UNRESERVED_GENERAL])) {
                if (strtotime($applicantDetail['date_of_birth']) < strtotime(MstClassified::DOB_START_DATE)) {
                    return false;
                }
            } else {
                if (strtotime($applicantDetail['date_of_birth']) < strtotime(MstClassified::DOB_SC_ST_OBC_DFF)) {
                    return false;
                }
            }
        }
        return true;
    }
}
