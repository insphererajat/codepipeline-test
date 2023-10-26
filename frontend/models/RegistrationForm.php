<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;

use Yii;
use common\models\caching\ModelCache;
use common\models\MstPost;
use yii\helpers\ArrayHelper;
use common\models\ApplicantDetail;
use components\Helper;
use common\models\MstListType;
use components\Security;

/**
 * Description of RegistrationForm
 *
 * @author Amit Handa
 */
class RegistrationForm extends \yii\base\Model
{

    public $id;
    public $guid;
    public $post_id;
    public $classifiedId;
    public $applicantPostId;
    public $applicantPostFormStep;
    public $createRecord = 0;
    //basic detail
    public $name;
    public $email;
    public $mobile;
    public $form_step;
    public $application_status;
    //personal details
    public $is_aadhaar_card_holder;
    public $aadhaar_no;
    public $name_on_aadhaar;
    public $father_name;
    public $mother_name;
    public $father_salutation;
    public $mother_salutation;
    public $date_of_birth;
    public $gender;
    public $marital_status;
    public $nationality;
    public $identity_type_id;
    public $identity_certificate_no;
    public $identity_type_display;
    public $std_code;
    public $birth_state_code;
    public $birth_certificate_type;
    public $is_domiciled;
    public $domicile_no;
    public $domicile_issue_state;
    public $domicile_issue_district;
    public $domicile_issue_date;
    public $disability_percentage;
    public $disability_certificate_no;
    public $disability_certificate_issue_date;
    public $is_uttrakhand_women;
    //address details
    public $same_as_present_address;
    public $present_address_house_no;
    public $present_address_street;
    public $present_address_area;
    public $present_address_premises_name;
    public $present_address_landmark;
    public $present_address_state_code;
    public $present_address_district_code;
    public $present_address_tehsil_code;
    public $present_address_tehsil_name;
    public $present_address_village_name;
    public $present_address_pincode;
    public $permanent_address_house_no;
    public $permanent_address_street;
    public $permanent_address_area;
    public $permanent_address_premises_name;
    public $permanent_address_landmark;
    public $permanent_address_state_code;
    public $permanent_address_district_code;
    public $permanent_address_tehsil_code;
    public $permanent_address_tehsil_name;
    public $permanent_address_village_name;
    public $permanent_address_pincode;
    // category details
    public $social_category_id;
    public $social_category_certificate_issue_authority_id;
    public $social_category_certificate_issue_date;
    public $social_category_certificate_no;
    public $social_category_certificate_state_code;
    public $social_category_certificate_district_code;
    public $is_non_creamy_layer;
    public $social_category_certificate_valid_upto_date;
    // subcategory
    public $disability_id;
    public $is_exserviceman;
    public $exserviceman_qualification_certificate;
    // qualification
    public $applicantQualificationId;
    public $qualification_type_id;
    public $qualification_degree_id;
    public $course_name;
    public $board_university;
    public $other_board;
    public $university_state;
    public $result_status;
    public $course_duration;
    public $qualification_year;
    public $mark_type;
    public $total_marks;
    public $obtained_marks;
    public $cgpa;
    public $grade;
    public $percentage;
    public $division;
    public $net_qualifying_date;
    public $mphil_phd_registration_no;
    public $mphil_phd_registration_date;
    public $mphil_phd_project_title;
    // other details
    public $is_employed;
    public $is_dependent_freedom_fighter;
    public $freedom_fighter_name;
    public $freedom_fighter_relation;
    public $freedom_fighter_certificate_no;
    public $freedom_fighter_issue_date;
    public $is_dismissed_from_defence;
    public $is_voluntary_retirement;
    public $is_relieved_on_medical;
    public $discharge_certificate_no;
    public $discharge_date;
    public $is_dswro_registered;
    public $dswro_registration_no;
    public $dswro_registration_date;
    public $dswro_registration_upto_date;
    public $dswro_office_name;
    public $is_debarred;
    public $debarred_from_date;
    public $debarred_to_date;
    // employment details
    public $applicantEmploymentId;
    public $employment_type_id;
    public $experience_type_id;
    public $start_date;
    public $end_date;
    public $office_name;
    public $designation;
    public $employer;
    public $employment_nature_id;
    // upload documents
    public $photo;
    public $signature;
    public $birth_certificate;
    public $caste_certificate;
    public $upload_employment_certificate = [];
    public $is_mobile_verified = 1;
    public $is_email_verified = 1;
    public $applyRules = true;
    public $autoLogin = true;
    // lt_details
    public $applicant_post_criteria_id;
    public $criteria_qualification_id;
    public $criteria_experience_id;
    public $criteria_valid_driving_licence;
    public $criteria_licence_issuing_authority;
    public $criteria_type_of_licence;
    public $criteria_licence_date_of_issue;
    public $criteria_valid_up_to;
    public $field2;
    public $field3;
    public $field4;
    public $fee_amount;
    public $application_no;
    public $is_eservice = false;
    public $district_code;
    public $_applicantDetailModel = [];
    public $posts;

    const SCENARIO_OTP_SCREEN = 'otp';
    const SCENARIO_FIRST_STEP = 'firstStep';
    const SCENARIO_SECOND_STEP = 'secondStep';
    const SCENARIO_THIRD_STEP = 'thirdStep';
    const SCENARIO_FOURTH_STEP = 'fourthStep';    
    const SCENARIO_FIFTH_STEP = 'fifthStep';
    const SCENARIO_SIXTH_STEP = 'sixthStep';
    const SCENARIO_SEVEN_STEP = 'sevenStep';
    const SCENARIO_EIGHT_STEP = 'eightStep';
    const SCENARIO_4 = 4;// DLSA Recruitment for ADR centres
    const SELECT_TYPE_YES = 1;
    const SELECT_TYPE_NO = 0;
    
    public function validateQualificationYear($attribute, $params, $validator)
    {
        if ($this->qualification_type_id == \common\models\MstQualification::PARENT_10TH) {
            $qualificationModel = \common\models\ApplicantQualification::findByApplicantIdAndQualificationTypeId($this->applicantPostId, \common\models\MstQualification::PARENT_12, [
                        'qualificationYear' => $this->qualification_year,
                        'countOnly' => true
            ]);
            if ($qualificationModel > 0) {
                $this->addError($attribute, "You already use same year in Intermediate(+2)");
            }
        }
        if ($this->qualification_type_id == \common\models\MstQualification::PARENT_12) {
            
            $qualificationModel = \common\models\ApplicantQualification::findByApplicantIdAndQualificationTypeId($this->applicantPostId, \common\models\MstQualification::PARENT_10TH, [
                        'qualificationYear' => $this->qualification_year,
                        'countOnly' => true
            ]);
            if ($qualificationModel > 0) {
                $this->addError($attribute, "You already use same year in SSC/Matric/High School");
            }
        }
    }
    
    public function validationValidUptoDate($attribute, $params, $validator)
    {
        if (!empty($this->$attribute) && (strtotime($this->$attribute) < strtotime(date('d-m-Y')))) {
            $this->addError($attribute, 'Valid Upto should be greater or equal to today.');
        }
    }
    
    public function validateEmploymentDocument($attribute, $params, $validator)
    {
        if (!empty($this->upload_employment_certificate)) {
            foreach ($this->upload_employment_certificate as $key => $value) {
                if (empty($value)) {
                    $applicantEmploymentModel = \common\models\ApplicantEmployment::findByKeys([
                                'id' => $key,
                                'selectCols' => ['applicant_employment.id', 'applicant_employment.experience_type_id']
                    ]);
                    $this->addError($attribute, 'Upload ' . \common\models\MstListType::getName($applicantEmploymentModel['experience_type_id']) . " Certificate ");
                }
            }
        }
    }
    
    public function validateIdentity($attribute, $params, $validator)
    {
        $this->identity_certificate_no = Security::cryptoAesDecrypt($this->identity_certificate_no, Yii::$app->params['hashKey']);
        if ($this->identity_type_id == MstListType::AADHAR && !preg_match(Helper::aadharRegex(), $this->identity_certificate_no)) {
            $this->addError($attribute, 'Please enter valid aadhar number.');
        }
        else if ($this->identity_type_id == MstListType::PAN && !preg_match(Helper::panRegex(), $this->identity_certificate_no)) {
            $this->addError($attribute, 'Please enter valid pan.');
        }
        else if ($this->identity_type_id == MstListType::DRIVING_LICENSE && !preg_match(Helper::drivingLicenseRegex(), $this->identity_certificate_no)) {
            $this->addError($attribute, 'Please enter valid driving license.');
        }
        else if ($this->identity_type_id == MstListType::PASSPORT && !preg_match(Helper::passportRegex(), $this->identity_certificate_no)) {
            $this->addError($attribute, 'Please enter valid passport.');
        }
        else if ($this->identity_type_id == MstListType::VOTER_ID && !preg_match(Helper::voterIdRegex(), $this->identity_certificate_no)) {
            $this->addError($attribute, 'Please enter valid voter id.');
        }
        
        $applicantDetailModel = \common\models\ApplicantDetail::findByIdentityCertificateNo(Helper::encryptString($this->identity_certificate_no), [
                    'notApplicantPostId' => $this->applicantPostId,
                    'countOnly' => true
        ]);
        
        if ($applicantDetailModel > 0) {
            $this->addError($attribute, "Identity already exist.");
        }
    }

    public function rules()
    {
        return [
            
            [['name', 'father_name', 'mother_name', 'name_on_aadhaar', 'dswro_office_name', 'freedom_fighter_name', 'freedom_fighter_relation', 'present_address_tehsil_name', 'permanent_address_tehsil_name'], 'match', 'pattern' => Helper::alphabetRegex(), 'message' => Yii::t('app', 'alphabet')],
            [['domicile_no', 'social_category_certificate_no', 'disability_certificate_no', 'dswro_registration_no', 'discharge_certificate_no', 'freedom_fighter_certificate_no', 'present_address_house_no', 'present_address_premises_name', 'present_address_street', 'present_address_area', 'present_address_landmark', 'present_address_village_name', 'permanent_address_house_no', 'permanent_address_premises_name', 'permanent_address_street', 'permanent_address_area', 'permanent_address_landmark', 'permanent_address_village_name', 'mphil_phd_registration_no', 'mphil_phd_project_title', 'office_name'], 'match', 'pattern' => Helper::alphanumericWithSpecialRegex(), 'message' => Yii::t('app', 'alpha.numeric.special')],
                        
            //First Step
            ['identity_certificate_no', 'validateIdentity', 'on' => self::SCENARIO_FIRST_STEP],
            [['application_no'], 'string', 'max' => 15],
            ['qualification_year', 'validateQualificationYear', 'on' => self::SCENARIO_FOURTH_STEP],
            [['aadhaar_no'], 'match', 'pattern' => '/^[2-9]\d{11}$/'],
            [['name', 'email', 'mobile'], 'required', 'on' => self::SCENARIO_OTP_SCREEN],
            [['aadhaar_no'], 'string', 'min' => 12, 'max' => 12],
            [['name_on_aadhaar', 'field2', 'field3', 'field4'], 'string', 'max' => 150],
            [['domicile_no', 'disability_certificate_no', 'social_category_certificate_no', 'discharge_certificate_no', 'dswro_registration_no'], 'string', 'max' => 30],
            [['domicile_issue_date', 'parents_department_date_of_joining', 'disability_certificate_issue_date', 'social_category_certificate_issue_date', 'social_category_certificate_valid_upto_date', 'discharge_date', 'criteria_licence_date_of_issue', 'criteria_valid_up_to'], 'safe'],
            ['social_category_certificate_valid_upto_date', 'validationValidUptoDate', 'on' => self::SCENARIO_FIRST_STEP],
            [['freedom_fighter_certificate_no', 'identity_type_display'], 'string', 'max' => 50],
            [['freedom_fighter_name', 'freedom_fighter_relation', 'exserviceman_qualification_certificate'], 'string', 'max' => 100],
            [['is_aadhaar_card_holder', 'date_of_birth', 'gender', 'marital_status', 'nationality', 'is_domiciled', 'birth_certificate_type', 'disability_id', 'birth_state_code', 'father_salutation', 'mother_salutation', 'father_name', 'mother_name'], 'required', 'on' => self::SCENARIO_FIRST_STEP],
                        
            [['social_category_id'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_domiciled === self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_domiciled').val() === '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],
            [['identity_type_id', 'identity_certificate_no'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_aadhaar_card_holder === self::SELECT_TYPE_NO) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_aadhaar_card_holder').val() === '" . self::SELECT_TYPE_NO . "') ? true : false;
            }"],
            [['aadhaar_no', 'name_on_aadhaar'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_aadhaar_card_holder === self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_aadhaar_card_holder').val() === '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],
            [['is_exserviceman'], 'required', 'on' => self::SCENARIO_FIRST_STEP],
            /*[['is_exserviceman', 'is_dependent_freedom_fighter'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_domiciled == self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_domiciled').val() == '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],*/
            [['exserviceman_qualification_certificate'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_exserviceman === self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_exserviceman').val() === '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],
            [['domicile_no', 'domicile_issue_district'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_domiciled === self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_domiciled').val() === '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],
            [['social_category_certificate_no', 'social_category_certificate_district_code', 'social_category_certificate_issue_authority_id', 'social_category_certificate_issue_date'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->social_category_id != \common\models\MstListType::UNRESERVED_GENERAL) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-social_category_id').val() !== '" . \common\models\MstListType::UNRESERVED_GENERAL . "') ? true : false;
            }"],
            [['is_non_creamy_layer', 'social_category_certificate_issue_date'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->social_category_id == \common\models\MstListType::OBC) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-social_category_id').val() === '" . \common\models\MstListType::OBC . "') ? true : false;
            }"],
            [['disability_percentage', 'disability_certificate_issue_date'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->disability_id != \common\models\MstListType::NOT_APPLICABLE && $model->disability_id != '') ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-disability_id').val() !== '" . \common\models\MstListType::NOT_APPLICABLE . "') ? true : false;
            }"],
            // END STEP First  
                        
            [['post_id', 'posts'], 'safe'],
            // Second Step
            [['present_address_state_code', 'present_address_district_code', 'present_address_pincode', 'permanent_address_pincode',
            'permanent_address_state_code', 'permanent_address_district_code', 'present_address_village_name', 'permanent_address_village_name'], 'required', 'on' => self::SCENARIO_SECOND_STEP],
            ['same_as_present_address', 'safe'],
            [['present_address_village_name', 'present_address_house_no', 'present_address_street', 'present_address_area', 'permanent_address_village_name', 'permanent_address_house_no', 'permanent_address_street', 'permanent_address_area', 'present_address_landmark', 'permanent_address_landmark', 'other_board', 'course_name'], 'string', 'max' => 255],
            [['present_address_pincode', 'permanent_address_pincode'], 'string', 'max' => 6],
            [['present_address_tehsil_name', 'permanent_address_tehsil_name'], 'string', 'max' => 64],
            [['is_aadhaar_card_holder', 'social_category_id', 'social_category_certificate_issue_authority_id', 'social_category_certificate_state_code', 'social_category_certificate_district_code', 'disability_id', 'is_exserviceman', 'identity_type_id', 'birth_state_code', 'domicile_issue_state', 'domicile_issue_district',
            'is_uttrakhand_women', 'father_salutation', 'mother_salutation', 'present_address_tehsil_code', 'permanent_address_tehsil_code', 'is_dswro_registered', 'is_dismissed_from_defence', 'is_voluntary_retirement', 'is_relieved_on_medical', 'birth_certificate_type'], 'integer'],
            [['social_category_certificate_no', 'social_category_certificate_issue_date', 'university_state', 'present_address_premises_name', 'permanent_address_premises_name'], 'string', 'max' => 255],
            // forth step
            [['qualification_type_id', 'qualification_degree_id', 'board_university', 'university_state', 'course_duration', 'qualification_year', 'mark_type', 'result_status', 'division'], 'required', 'on' => self::SCENARIO_FOURTH_STEP],
            [['applicant_post_criteria_id', 'qualification_type_id', 'qualification_degree_id', 'board_university', 'result_status'], 'integer'],
            ['percentage', 'number'],
            [['course_name'], 'required', 'on' => self::SCENARIO_FOURTH_STEP, 'when' => function ($model) {
                    return ($model->qualification_degree_id == \common\models\MstQualification::CHILD_OTHER) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-qualification_degree_id').val() == '" . \common\models\MstQualification::CHILD_OTHER . "') ? true : false;
            }"],
            [['other_board'], 'required', 'on' => self::SCENARIO_FOURTH_STEP, 'when' => function ($model) {
                    return ($model->board_university == \common\models\MstUniversity::OTHER) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-board_university').val() == '" . \common\models\MstUniversity::OTHER . "') ? true : false;
            }"],
            [['percentage'], 'required', 'on' => self::SCENARIO_FOURTH_STEP, 'when' => function ($model) {
                    return ($model->qualification_type_id != \common\models\MstQualification::CERTIFICATIONS) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-qualification_type_id').val() != '" . \common\models\MstQualification::CERTIFICATIONS . "') ? true : false;
            }"],
            [['obtained_marks', 'total_marks'], 'required', 'when' => function ($model) {
                    return ($model->mark_type == \common\models\MstQualification::RESULT_TYPE_MARKS) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-mark_type').val() == '" . \common\models\MstQualification::RESULT_TYPE_MARKS . "') ? true : false;
            }"],
            ['cgpa', 'required', 'when' => function ($model) {
                    return ($model->mark_type == \common\models\MstQualification::RESULT_TYPE_CGPA) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-mark_type').val() == '" . \common\models\MstQualification::RESULT_TYPE_CGPA . "') ? true : false;
            }"],
            ['grade', 'required', 'when' => function ($model) {
                    return ($model->mark_type == \common\models\MstQualification::RESULT_TYPE_GRADE) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-mark_type').val() == '" . \common\models\MstQualification::RESULT_TYPE_GRADE . "') ? true : false;
            }"],
            //['obtained_marks', 'compare', 'compareAttribute' => 'total_marks', 'operator' => '<='],
            ['percentage', 'compare', 'compareValue' => 33, 'operator' => '>='],
            [['obtained_marks', 'total_marks'], 'string', 'max' => 10],
            [['percentage'], 'string', 'max' => 5],
            //fifth step
            [['is_debarred'], 'required', 'on' => self::SCENARIO_FIRST_STEP],
            [['is_employed', 'is_dependent_freedom_fighter', 'is_debarred'], 'integer'],
            [['university_state'], 'string', 'max' => 255],
            [['debarred_from_date', 'debarred_to_date'], 'required', 'on' => self::SCENARIO_FIRST_STEP, 'when' => function ($model) {
                    return ($model->is_debarred === self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_debarred').val() === '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],
            
            //sixth step
            [['is_employed'], 'required', 'on' => self::SCENARIO_FIFTH_STEP],
            [['employer', 'office_name', 'designation', 'employment_type_id', 'start_date', 'experience_type_id', 'employment_nature_id'], 'required', 'on' => self::SCENARIO_FIFTH_STEP, 'when' => function ($model) {
                    return ($model->is_employed === self::SELECT_TYPE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-is_employed').val() === '" . self::SELECT_TYPE_YES . "') ? true : false;
            }"],
            [['employment_type_id', 'experience_type_id', 'employment_nature_id'], 'integer'],
            [['office_name', 'mphil_phd_project_title', 'mphil_phd_registration_no'], 'string', 'max' => 255],
            [['designation', 'dswro_office_name', 'employer'], 'string', 'max' => 100],
            [['start_date', 'end_date'], 'string'],
            [['end_date'], 'required', 'on' => self::SCENARIO_FIFTH_STEP, 'when' => function ($model) {
                    return ($model->employment_type_id === \common\models\ApplicantEmployment::EMPLOYMENT_TYPE_PAST) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-employment_type_id').val() === '" . \common\models\ApplicantEmployment::EMPLOYMENT_TYPE_PAST . "') ? true : false;
            }"],
            // seventh step
            [['photo', 'signature', 'birth_certificate', 'caste_certificate'], 'integer', 'on' => self::SCENARIO_SIXTH_STEP],
            [['photo', 'signature', 'birth_certificate'], 'required', 'on' => self::SCENARIO_SIXTH_STEP],
            [['caste_certificate'], 'required', 'on' => self::SCENARIO_SIXTH_STEP, 'when' => function ($model) {
                    $applicantDetail = ApplicantDetail::findByApplicantPostId($model->applicantPostId, [
                                'selectCols' => new \yii\db\Expression("social_category_id"),
                    ]);
                    return ($applicantDetail['social_category_id'] != \common\models\MstListType::UNRESERVED_GENERAL) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#registrationform-social_category_id').val() != '" . \common\models\MstListType::UNRESERVED_GENERAL . "') ? true : false;
            }"],
            [['upload_employment_certificate'], 'safe', 'on' => self::SCENARIO_SIXTH_STEP],
            ['upload_employment_certificate', 'validateEmploymentDocument', 'on' => self::SCENARIO_SIXTH_STEP],
            // basic fields validations
            ['email', 'email'],
            [['name'], 'string', 'max' => 255],
            [['mobile'], 'match', 'pattern' => '/[6789][0-9]{9}/'],
                        
            // Criteria Step
            [['applicant_post_criteria_id'], 'required', 'on' => self::SCENARIO_4, 'message' => 'This field cannot be blank.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'is_aadhaar_card_holder' => 'Are you holding an Aadhar Card?',
            'name_on_aadhaar' => 'Name In English',
            'aadhaar_no' => 'Aadhar No',
            'name' => 'Name',
            'email' => 'Email address',
            'father_name' => 'Full Name Of Father/Husband',
            'house_no' => 'Flat / Room / Door / Block / House No.',
            'premises_name' => 'Name of Premises / Building',
            'street' => 'Road / Street / Lane / Post Office',
            'area' => 'Area / Locality',
            'landmark' => 'Landmark',
            'state' => 'State',
            'district' => 'District',
            'village_name' => 'Village Name',
            'pincode' => 'PIN',
            'qualification_type_id' => 'Qualification Type',
            'qualification_degree_id' => 'Name of Course',
            'course_name' => 'Name of Course',
            'university_state' => 'University State',
            'board_university' => 'University Board',
            'other_board' => 'Other University Board',
            'result_status' => 'Result Status',
            'course_duration' => 'Course duration',
            'qualification_year' => 'Year',
            'mark_type' => 'Marks type',
            'obtained_marks' => 'Marks Obtained',
            'total_marks' => 'Out Of',
            'cgpa' => 'CGPA',
            'grade' => 'Grade',
            'percentage' => 'Percentage',
            'division' => 'Division',
            'is_employed' => 'Employed',
            'is_exserviceman' => 'Are You Ex-Army Person ?',
            'exserviceman_qualification_certificate' => 'Certificate No',
            'is_dependent_freedom_fighter' => 'Dependent Freedom Fighter',
            'freedom_fighter_name' => 'Name of freedom fighter',
            'freedom_fighter_relation' => 'Relation to freedom fighter',
            'freedom_fighter_certificate_no' => 'Certificate no',
            'freedom_fighter_issue_date' => 'Date of issuing',
            'is_debarred' => 'Whether Debarded or Black listed for examination by UPSC/SSC/State PSC/Board etc?',
            'qualifying_examination' => 'Type of Institution of Class 10th/Qualifying Examination',
            'is_dismissed_from_defence' => 'Dismissed From Defence',
            'is_voluntary_retirement' => 'Voluntary Retirement',
            'is_relieved_on_medical' => 'Relieved On Medical',
            'is_dswro_registered' => 'DSRO Registered',
            'dswro_registration_no' => 'DSRO Registration No',
            'dswro_office_name' => 'DSRO Office Name',
            'dswro_registration_date' => 'DSRO Registration Date',
            'dswro_registration_upto_date' => 'In last year Service',
            'experience_type_id' => 'Experience Type',
            'designation' => 'Enter Designation(Post Held)',
            'office_name' => 'Institution / Department / Organisation',
            'criteria_qualification_id' => 'Qualification',
            'net_qualifying_date' => 'Date of Qualifying',
            'mphil_phd_registration_no' => 'Registration No.',
            'mphil_phd_registration_date' => 'Registration Date',
            'mphil_phd_project_title' => 'Project Title',
            'birth_certificate_type' => 'Proof of Birth Certificate',
            'criteria_experience_id' => 'Experience',
            'criteria_valid_driving_licence' => 'Valid Driving licence',
            'criteria_licence_issuing_authority' => 'Licence Issuing authority',
            'criteria_type_of_licence' => 'Type of Licence',
            'criteria_licence_date_of_issue' => 'Date of issue',
            'criteria_valid_up_to' => 'Valid up to'
        ];
    }

    public function saveBasicDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            if (!empty($this->guid)) {
                $model = \common\models\Applicant::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if (empty($model)) {
                    throw new \components\exceptions\AppException("Sorry, You are trying to access applicant model doesn't exists.");
                }
            }
            else {

                $model = new \common\models\Applicant;
                $model->loadDefaultValues(TRUE);
                $model->attributes = $this->attributes;
                $model->isNewRecord = true;
                $password = $this->generatePassword();
                $model->generateAuthKey();
                $model->setPassword($password);
            }

            if (!$model->save()) {

                $this->addErrors($model->errors);
                return false;
            }

            // save into applicant classified tbl
            $applicantPostModel = new \common\models\ApplicantPost();
            $applicantPostModel->isNewRecord = true;
            $applicantPostModel->applicant_id = $model->id;
            $applicantPostModel->post_id = $this->post_id;
            if (!$this->classifiedId) {
                $applicantPostModel->classified_id = $this->classifiedId;
            }
            $applicantPostModel->classified_id = \common\models\MstClassified::MASTER_CLASSIFIED;
            if (!$applicantPostModel->save()) {

                $this->addErrors($applicantPostModel->errors);
                return false;
            }

            Yii::$app->applicant->login($model, 3600 * 60 * 60);
            if (empty($this->guid)) {
                Yii::$app->email->sendWelcomeApplicantEmail($model->id, $password);
            }

            $this->id = $model->id;
            $this->guid = $model->guid;

            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }

    public function savePersonalDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $applicant = \common\models\Applicant::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($applicant)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists.");
            }

            $model = ApplicantDetail::findByApplicantPostId($this->applicantPostId, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
            ]);

            if ($model === NULL) {

                $model = new ApplicantDetail();
                $model->isNewRecord = TRUE;
                $model->loadDefaultValues(true);
                $model->applicant_post_id = $this->applicantPostId;
                $this->createRecord = 1;
            }
            
            if ($this->is_eservice) {
                $this->father_name = $model->father_name;
            }
            
            if (!ModelCache::testEmail(Yii::$app->applicant->identity->email)) {
                $this->date_of_birth = !empty($model->date_of_birth) ? $model->date_of_birth : $this->date_of_birth;
            }
            $this->father_name = !empty($model->father_name) ? $model->father_name : $this->father_name;
            $this->father_salutation = !empty($model->father_salutation) ? $model->father_salutation : $this->father_salutation;
            $model->setAttributes($this->attributes);
            $model->domicile_issue_state = empty($model->domicile_issue_district) ? NULL : $model->domicile_issue_state;
            $model->social_category_certificate_state_code = (empty($model->social_category_certificate_district_code) && $model->social_category_id == \common\models\MstListType::UNRESERVED_GENERAL) ? NULL : $model->social_category_certificate_state_code;
            $model->is_aadhaar_card_holder = ModelCache::IS_ACTIVE_NO;
            $model->aadhaar_no = NULL;
            $model->name_on_aadhaar = NULL;
            $model->identity_certificate_no = !empty($this->identity_certificate_no) ? Helper::encryptString($this->identity_certificate_no) : '';
            $lastDigit = substr($this->identity_certificate_no, -4);
            $model->identity_type_display = str_pad("", (strlen($this->identity_certificate_no) - 4), "x", STR_PAD_LEFT) . $lastDigit;
            if (!$model->save(true, ['id', 'guid', 'applicant_post_id', 'is_aadhaar_card_holder', 'aadhaar_no', 'name_on_aadhaar', 'identity_type_id', 'identity_certificate_no', 'nationality', 'father_salutation', 'father_name', 'mother_salutation', 'mother_name', 'date_of_birth', 'birth_state_code', 'gender', 'marital_status', 'is_domiciled', 'domicile_no', 'domicile_issue_state', 'domicile_issue_district', 'domicile_issue_date', 'social_category_id', 'is_non_creamy_layer', 'social_category_certificate_issue_date', 'social_category_certificate_no', 'social_category_certificate_state_code', 'social_category_certificate_district_code', 'social_category_certificate_issue_authority_id', 'disability_id', 'disability_percentage', 'disability_certificate_no', 'disability_certificate_issue_date', 'is_dismissed_from_defence', 'is_voluntary_retirement', 'is_relieved_on_medical', 'is_dswro_registered', 'dswro_registration_no', 'dswro_office_name', 'dswro_registration_date', 'dswro_registration_upto_date', 'discharge_certificate_no', 'discharge_date', 'is_dependent_freedom_fighter', 'freedom_fighter_name', 'freedom_fighter_relation', 'freedom_fighter_certificate_no', 'freedom_fighter_issue_date', 'is_uttrakhand_women', 'social_category_certificate_valid_upto_date', 'birth_certificate_type', 'is_exserviceman', 'exserviceman_qualification_certificate', 'is_debarred', 'debarred_from_date', 'debarred_to_date', 'identity_type_display'])) {

                $this->addErrors($model->errors);
                return false;
            }
            
            if($this->createRecord || empty($applicant->form_step)) {
                $this->updateFormStep(1);
            }
            
            $this->updateEserviceTab(0);
            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }

    public function saveAddressDetails()
    {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            // save presentaddress
            $presentAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($this->applicantPostId, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                        'addressType' => \common\models\ApplicantAddress::CURRENT_ADDRESS
            ]);



            if ($presentAddressModel === NULL) {

                $presentAddressModel = new \common\models\ApplicantAddress;
                $presentAddressModel->applicant_post_id = $this->applicantPostId;
                $presentAddressModel->address_type = \common\models\ApplicantAddress::CURRENT_ADDRESS;
                $this->createRecord = 1;
            }

            $presentAddressModel->house_no = $this->present_address_house_no;
            $presentAddressModel->premises_name = $this->present_address_premises_name;
            $presentAddressModel->street = $this->present_address_street;
            $presentAddressModel->area = $this->present_address_area;
            $presentAddressModel->landmark = $this->present_address_landmark;
            $presentAddressModel->state_code = $this->present_address_state_code;
            $presentAddressModel->district_code = $this->present_address_district_code;
            $presentAddressModel->tehsil_code = $this->present_address_tehsil_code;
            $presentAddressModel->tehsil_name = $this->present_address_tehsil_name;
            $presentAddressModel->village_name = $this->present_address_village_name;
            $presentAddressModel->pincode = $this->present_address_pincode;

            // save permanent address
            $permanentAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($this->applicantPostId, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                        'addressType' => \common\models\ApplicantAddress::PERMANENT_ADDRESS
            ]);


            if ($permanentAddressModel === NULL) {

                $permanentAddressModel = new \common\models\ApplicantAddress;
                $permanentAddressModel->applicant_post_id = $this->applicantPostId;
                $permanentAddressModel->address_type = \common\models\ApplicantAddress::PERMANENT_ADDRESS;
                $this->createRecord = 1;
            }

            if (isset($this->same_as_present_address) && $this->same_as_present_address) {

                $permanentAddressModel->house_no = $this->present_address_house_no;
                $permanentAddressModel->premises_name = $this->present_address_premises_name;
                $permanentAddressModel->street = $this->present_address_street;
                $permanentAddressModel->area = $this->present_address_area;
                $permanentAddressModel->landmark = $this->present_address_landmark;
                $permanentAddressModel->state_code = $this->present_address_state_code;
                $permanentAddressModel->district_code = $this->present_address_district_code;
                $permanentAddressModel->tehsil_code = $this->present_address_tehsil_code;
                $permanentAddressModel->tehsil_name = $this->present_address_tehsil_name;
                $permanentAddressModel->village_name = $this->present_address_village_name;
                $permanentAddressModel->pincode = $this->present_address_pincode;
                \common\models\ApplicantPost::updateAll(['same_as_present_address' => 1], 'id=:id', [':id' => $this->applicantPostId]);
                
            }
            else {
                $permanentAddressModel->house_no = $this->permanent_address_house_no;
                $permanentAddressModel->premises_name = $this->permanent_address_premises_name;
                $permanentAddressModel->street = $this->permanent_address_street;
                $permanentAddressModel->area = $this->permanent_address_area;
                $permanentAddressModel->landmark = $this->permanent_address_landmark;
                $permanentAddressModel->state_code = $this->permanent_address_state_code;
                $permanentAddressModel->district_code = $this->permanent_address_district_code;
                $permanentAddressModel->tehsil_code = $this->permanent_address_tehsil_code;
                $permanentAddressModel->tehsil_name = $this->permanent_address_tehsil_name;
                $permanentAddressModel->village_name = $this->permanent_address_village_name;
                $permanentAddressModel->pincode = $this->permanent_address_pincode;
                \common\models\ApplicantPost::updateAll(['same_as_present_address' => 0], 'id=:id', [':id' => $this->applicantPostId]);
            }
            
            $presentAddressModel->tehsil_code = ($presentAddressModel->tehsil_code == \common\models\location\MstTehsil::OTHER) ? null : $presentAddressModel->tehsil_code;
            if (!$presentAddressModel->save()) {
                $this->addErrors($presentAddressModel->errors);
                return false;
            }
            
            $permanentAddressModel->tehsil_code = ($permanentAddressModel->tehsil_code == \common\models\location\MstTehsil::OTHER) ? null : $permanentAddressModel->tehsil_code;
            if (!$permanentAddressModel->save()) {
                $this->addErrors($permanentAddressModel->errors);
                return false;
            }
            
            if($this->createRecord || $this->applicantPostFormStep < 2) {
                $this->updateFormStep(2);
            }
            
            $this->updateEserviceTab(1);
            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }

    public function saveQualificationDetails()
    {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $count = \common\models\ApplicantQualification::findByApplicantPostId($this->applicantPostId, ['selectCols' => ['id'], 'countOnly' => true]);
            if($count > 20) {
                throw new \components\exceptions\AppException("Sorry, Your add qualification limit exceeded.");
            }
            
            $model = null;
            if ($this->qualification_type_id == \common\models\MstQualification::PARENT_10TH) {
                $model = \common\models\ApplicantQualification::findByApplicantIdAndQualificationTypeId($this->applicantPostId, $this->qualification_type_id, [
                            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                ]);
            }
            
            if (!empty($this->applicantQualificationId)) {
                $model = \common\models\ApplicantQualification::findById($this->applicantQualificationId, [
                            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                ]);
            }

            if ($model === NULL) {

                $model = new \common\models\ApplicantQualification();
                $model->isNewRecord = TRUE;
                $model->applicant_post_id = $this->applicantPostId;
                $model->qualification_type_id = $this->qualification_type_id;
            }
            $model->setAttributes($this->attributes);
            if ($model->obtained_marks > $model->total_marks) {
                $this->addError("obtained_marks", 'Marks Obtained must be less than or equal to "Out Of".');
                return false;
            }
            
            if ($model->qualification_degree_id != \common\models\MstQualification::CHILD_OTHER) {
                $model->course_name = NULL;
            }
            if ($model->board_university != \common\models\MstUniversity::OTHER) {
                $model->other_board = NULL;
            }
            if (!$model->save()) {
                $this->addErrors($model->errors);
                return false;
            }
            
            if (\common\models\ApplicantQualification::minimumQualificationValidation($this->applicantPostId) && $this->applicantPostFormStep < 5) {
                $this->updateFormStep(3);
            }
            
            $this->updateEserviceTab(3);
            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }

    public function saveEmploymentDetails()
    {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $count = \common\models\ApplicantEmployment::findByApplicantPostId($this->applicantPostId, ['selectCols' => ['id'], 'countOnly' => true]);
            if($count > 20) {
                throw new \components\exceptions\AppException("Sorry, Your add qualification limit exceeded.");
            }

            $applicantDetailModel = ApplicantDetail::findByApplicantPostId($this->applicantPostId, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
            ]);

            if ($applicantDetailModel === NULL) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access applicant post detail doesn't exists.");
            }
            if($this->is_employed == self::SELECT_TYPE_YES) {

                $params = [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                ];

                $model = NULL;
                if (isset($this->applicantEmploymentId) && $this->applicantEmploymentId > 0) {
                    $params['id'] = $this->applicantEmploymentId;
                    $model = \common\models\ApplicantEmployment::findByApplicantPostId($this->applicantPostId, $params);
                }

                if ($model === NULL) {

                    $model = new \common\models\ApplicantEmployment();
                    $model->isNewRecord = TRUE;
                    $model->applicant_post_id = $this->applicantPostId;
                }
                $model->employer = $this->employer;
                $model->office_name = $this->office_name;
                $model->designation = $this->designation;
                $model->employment_nature_id = $this->employment_nature_id;
                $model->employment_type_id = $this->employment_type_id;
                $model->start_date = $this->start_date;
                $model->end_date = $this->end_date;
                $model->experience_type_id = $this->experience_type_id;
                if (!$model->save()) {
                    $this->addErrors($model->errors);
                    return false;
                }
            }
            
            $applicantDetailModel->is_employed = $this->is_employed;
            $applicantDetailModel->save('true', ['is_employed']);
            
            $count = \common\models\ApplicantEmployment::findByApplicantPostId($this->applicantPostId, [
                        'count' => true
            ]);

            if (($count > 0 || empty($applicantDetailModel->is_employed)) && $this->applicantPostFormStep < 5) {
                $this->updateFormStep(4);
            }
            
            $this->updateEserviceTab(4);
            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }

    public function saveDocuments()
    {


        $transaction = \Yii::$app->db->beginTransaction();
        try {

            \common\models\ApplicantDocument::deleteAll('applicant_post_id=:applicantPostId', [':applicantPostId' => $this->applicantPostId]);
            if (isset($this->photo) && $this->photo > 0) {
                $model = new \common\models\ApplicantDocument();
                $model->isNewRecord = TRUE;
                $model->applicant_post_id = $this->applicantPostId;
                $model->media_id = $this->photo;
                $model->type = \common\models\ApplicantDocument::TYPE_USER_PHOTO;
                if (!$model->save()) {
                    $this->addErrors($model->errors);
                    return false;
                }
            }
            if (isset($this->signature) && $this->signature > 0) {
                $model = new \common\models\ApplicantDocument();
                $model->isNewRecord = TRUE;
                $model->applicant_post_id = $this->applicantPostId;
                $model->media_id = $this->signature;
                $model->type = \common\models\ApplicantDocument::TYPE_USER_SIGNATURE;
                if (!$model->save()) {
                    $this->addErrors($model->errors);
                    return false;
                }
            }
            if (isset($this->birth_certificate) && $this->birth_certificate > 0) {
                $model = new \common\models\ApplicantDocument();
                $model->isNewRecord = TRUE;
                $model->applicant_post_id = $this->applicantPostId;
                $model->media_id = $this->birth_certificate;
                $model->type = \common\models\ApplicantDocument::TYPE_USER_BIRTH_CERTIFICATE;
                if (!$model->save()) {
                    $this->addErrors($model->errors);
                    return false;
                }
            }
            if (isset($this->caste_certificate) && $this->caste_certificate > 0) {
                $model = new \common\models\ApplicantDocument();
                $model->isNewRecord = TRUE;
                $model->applicant_post_id = $this->applicantPostId;
                $model->media_id = $this->caste_certificate;
                $model->type = \common\models\ApplicantDocument::TYPE_USER_CASTE_CERTIFICATE;
                if (!$model->save()) {
                    $this->addErrors($model->errors);
                    return false;
                }
            }
            if (isset($this->upload_employment_certificate) && !empty($this->upload_employment_certificate)) {
                foreach ($this->upload_employment_certificate as $key => $employmentCertificate) {
                    $media = \common\models\Media::findOne($employmentCertificate);
                    if($media == NULL) {
                        continue;
                    }
                    $model = new \common\models\ApplicantDocument();
                    $model->isNewRecord = TRUE;
                    $model->applicant_post_id = $this->applicantPostId;
                    $model->media_id = $employmentCertificate;
                    $model->reference_id = $key;
                    $model->name = $media->filename;
                    $model->type = \common\models\ApplicantDocument::TYPE_USER_EMPLOYMENT_CERTIFICATE;
                    if (!$model->save()) {
                        $this->addErrors($model->errors);
                        return false;
                    }
                }
            }
            if ($this->applicantPostFormStep < 5) {
                $this->updateFormStep(5);
            }
            $this->updateEserviceTab(5);
            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }
    
    public function loadApplicantFee()
    {
        if (empty($this->applicantPostId)) {
            return false;
        }
        $postFee = \common\models\MstPostFee::getPostFee(['classifiedId' => $this->classifiedId, 'applicantPostId' => $this->applicantPostId]);
        if ($postFee === NULL) {
            throw new \components\exceptions\AppException("Sorry, Post fee details doesn't exist or deleted. Please contact with administrator.");
        }
        
        $applicantPost = \common\models\ApplicantPost::findByApplicantId($this->id, [
                    'classifiedId' => $this->classifiedId,
                    'notPostId' => MstPost::MASTER_POST,
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                    'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_PENDING
        ]);
        
        if ($applicantPost === NULL) {
            return false;
        }

        $model = \common\models\ApplicantFee::findByApplicantId($this->id, [
            'module' => \common\models\ApplicantFee::MODULE_APPLICATION,
            'applicantPostId' => $applicantPost->id,
            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
            'payStatus' => \common\models\ApplicantFee::STATUS_UNPAID
        ]);
        
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, Post fee details doesn't exist or deleted. Please contact with administrator.");
        }
        
        if($model->status == \common\models\ApplicantFee::STATUS_UNPAID)
        {
            $model->fee_amount = $postFee['amount'];
            $model->save(true, ['fee_amount']);
        }
        
        $this->fee_amount = $model->fee_amount;
        $this->application_no = $applicantPost->application_no;
    }
    
    private function _getClassifiedPostFee($postId)
    {
        $applicantPostModel = ApplicantDetail::findByApplicantPostId($this->applicantPostId);
        if (empty($applicantPostModel)) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists.");
        }

        $categoryId = $applicantPostModel['social_category_id'];
        $subCategoryArr[] = \common\models\MstListType::SUB_CATEGORY_NA;

        if ($applicantPostModel['is_uttrakhand_women'] == ModelCache::IS_ACTIVE_YES) {
            //$subCategoryArr[] = \common\models\MstListType::SUB_CATEGORY_UW;
        }
        if ($applicantPostModel['is_dependent_freedom_fighter'] == ModelCache::IS_ACTIVE_YES) {
            $subCategoryArr[] = \common\models\MstListType::SUB_CATEGORY_DFF;
        }
        if ($applicantPostModel['is_exserviceman'] == ModelCache::IS_ACTIVE_YES || $applicantPostModel['is_dswro_registered'] == ModelCache::IS_ACTIVE_YES ) {
            $subCategoryArr[] = \common\models\MstListType::SUB_CATEGORY_EX;
        }
        if ($applicantPostModel['disability_id'] != \common\models\MstListType::NOT_APPLICABLE) {
            $subCategoryArr[] = \common\models\MstListType::SUB_CATEGORY_PH;
        }

        return \common\models\MstPostFee::findByClassifiedId($this->classifiedId, [
                    'selectCols' => new \yii\db\Expression("MIN(marks) AS marks, MIN(amount) AS amount, MAX(max_age) AS max_age, MAX(is_10seat) AS is_10seat, MAX(is_60seat) AS is_60seat"),
                    'postId' => $postId,
                    'categoryId' => $categoryId,
                    'inSubCategoryIds' => $subCategoryArr,
                    'isActive' => ModelCache::IS_ACTIVE_YES,
                    'isDeleted' => ModelCache::IS_DELETED_NO,
        ]);
    }

    public function loadBasicDetails()
    {
        $model = \common\models\Applicant::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

        if ($model === NULL) {
            return false;
        }

        $this->name = $model->name;
        $this->email = $model->email;
        $this->mobile = $model->mobile;
    }

    public function loadPersonalDetails()
    {
        if (empty($this->applicantPostId)) {
            return false;
        }
        // find applicant classified id
        $model = ApplicantDetail::findByApplicantPostId($this->applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                
        if ($model === NULL) {
            return false;
        }

        $this->gender = $model->gender;
        $this->father_salutation = $model->father_salutation;
        $this->mother_salutation = $model->mother_salutation;
        $this->father_name = $model->father_name;
        $this->mother_name = $model->mother_name;
        $this->date_of_birth = date('d-m-Y', strtotime($model->date_of_birth));
        $this->nationality = $model->nationality;
        $this->marital_status = $model->marital_status;
        $this->is_domiciled = $model->is_domiciled;
        $this->identity_type_id = $model->identity_type_id;
        $this->birth_state_code = $model->birth_state_code;
        $this->domicile_no = $model->domicile_no;
        $this->domicile_issue_state = $model->domicile_issue_state;
        $this->domicile_issue_district = $model->domicile_issue_district;
        $this->domicile_issue_date = $model->domicile_issue_date;
        $this->disability_percentage = $model->disability_percentage;
        $this->disability_certificate_no = $model->disability_certificate_no;
        $this->disability_certificate_issue_date = $model->disability_certificate_issue_date;
        $this->is_non_creamy_layer = $model->is_non_creamy_layer;
        $this->social_category_certificate_valid_upto_date = $model->social_category_certificate_valid_upto_date;
        $this->disability_id = $model->disability_id;
        $this->social_category_id = $model->social_category_id;
        $this->social_category_certificate_no = $model->social_category_certificate_no;
        $this->social_category_certificate_issue_authority_id = $model->social_category_certificate_issue_authority_id;
        $this->social_category_certificate_issue_date = $model->social_category_certificate_issue_date;
        $this->social_category_certificate_state_code = $model->social_category_certificate_state_code;
        $this->social_category_certificate_district_code = $model->social_category_certificate_district_code;
        $this->is_aadhaar_card_holder = $model->is_aadhaar_card_holder;
        $this->aadhaar_no = $model->aadhaar_no;
        $this->name_on_aadhaar = $model->name_on_aadhaar;
        $this->is_exserviceman = $model->is_exserviceman;
        $this->exserviceman_qualification_certificate = $model->exserviceman_qualification_certificate;
        $this->is_dismissed_from_defence = $model->is_dismissed_from_defence;
        $this->is_voluntary_retirement = $model->is_voluntary_retirement;
        $this->is_relieved_on_medical = $model->is_relieved_on_medical;
        $this->is_dswro_registered = $model->is_dswro_registered;
        $this->dswro_registration_no = $model->dswro_registration_no;
        $this->dswro_office_name = $model->dswro_office_name;
        $this->dswro_registration_date = $model->dswro_registration_date;
        $this->dswro_registration_upto_date = $model->dswro_registration_upto_date;
        $this->discharge_certificate_no = $model->discharge_certificate_no;
        $this->discharge_date = $model->discharge_date;
        /*$this->is_dependent_freedom_fighter = $model->is_dependent_freedom_fighter;
        $this->freedom_fighter_name = $model->freedom_fighter_name;
        $this->freedom_fighter_relation = $model->freedom_fighter_relation;
        $this->freedom_fighter_certificate_no = $model->freedom_fighter_certificate_no;
        $this->freedom_fighter_issue_date = $model->freedom_fighter_issue_date;*/
        $this->birth_certificate_type = $model->birth_certificate_type;
        $this->is_employed = $model->is_employed;
        $this->is_debarred = $model->is_debarred;
        $this->debarred_from_date = $model->debarred_from_date;
        $this->debarred_to_date = $model->debarred_to_date;
        $this->is_domiciled = $model->is_domiciled;
        $this->is_exserviceman = $model->is_exserviceman;
        $this->exserviceman_qualification_certificate = $model->exserviceman_qualification_certificate;
        $this->identity_certificate_no = !empty($model->identity_certificate_no) ? Helper::decryptString($model->identity_certificate_no) : '';
        $this->identity_type_display = $model->identity_type_display;
    }

    public function loadAddressDetails()
    {
       
        if (empty($this->applicantPostId)) {
            return false;
        }
        // find applicant classified id
        $applicantAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($this->applicantPostId, ['resultCount' => 'all']);
                
        if ($applicantAddressModel === NULL) {
            return false;
        }

        foreach ($applicantAddressModel as $applicantAddressType => $applicantAddress) {

            switch ($applicantAddressType) {
                case \common\models\ApplicantAddress::CURRENT_ADDRESS:
                    $this->present_address_house_no = $applicantAddress['house_no'];
                    $this->present_address_premises_name = $applicantAddress['premises_name'];
                    $this->present_address_street = $applicantAddress['street'];
                    $this->present_address_area = $applicantAddress['area'];
                    $this->present_address_landmark = $applicantAddress['landmark'];
                    $this->present_address_state_code = $applicantAddress['state_code'];
                    $this->present_address_district_code = $applicantAddress['district_code'];
                    $this->present_address_tehsil_code = !empty($applicantAddress['tehsil_name']) ? \common\models\location\MstTehsil::OTHER : $applicantAddress['tehsil_code'];
                    $this->present_address_tehsil_name = $applicantAddress['tehsil_name'];
                    $this->present_address_village_name = $applicantAddress['village_name'];
                    $this->present_address_pincode = $applicantAddress['pincode'];

                    break;

                case \common\models\ApplicantAddress::PERMANENT_ADDRESS:
                    $this->permanent_address_house_no = $applicantAddress['house_no'];
                    $this->permanent_address_premises_name = $applicantAddress['premises_name'];
                    $this->permanent_address_street = $applicantAddress['street'];
                    $this->permanent_address_area = $applicantAddress['area'];
                    $this->permanent_address_landmark = $applicantAddress['landmark'];
                    $this->permanent_address_state_code = $applicantAddress['state_code'];
                    $this->permanent_address_district_code = $applicantAddress['district_code'];
                    $this->permanent_address_tehsil_code = !empty($applicantAddress['tehsil_name']) ? \common\models\location\MstTehsil::OTHER : $applicantAddress['tehsil_code'];
                    $this->permanent_address_tehsil_name = $applicantAddress['tehsil_name'];
                    $this->permanent_address_village_name = $applicantAddress['village_name'];
                    $this->permanent_address_pincode = $applicantAddress['pincode'];
                    break;
                default:
                    break;
            }
        }
        
        $applicantPost = \common\models\ApplicantPost::findById($this->applicantPostId);
        if (!empty($applicantPost)) {
            $this->same_as_present_address = $applicantPost['same_as_present_address'];
        }
    }

    public function loadQualificationDetails()
    {
        if (empty($this->applicantQualificationId)) {
            return false;
        }
        // find applicant classified id
        
        $model = \common\models\ApplicantQualification::findById($this->applicantQualificationId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

        if ($model === NULL) {
            return false;
        }

        $this->qualification_type_id = $model->qualification_type_id;
        $this->qualification_degree_id = $model->qualification_degree_id;
        $this->course_name = $model->course_name;
        $this->board_university = $model->board_university;
        $this->other_board = $model->other_board;
        $this->university_state = $model->university_state;
        $this->result_status = $model->result_status;
        $this->course_duration = $model->course_duration;
        $this->qualification_year = $model->qualification_year;
        $this->mark_type = $model->mark_type;
        $this->obtained_marks = $model->obtained_marks;
        $this->total_marks = $model->total_marks;
        $this->cgpa = $model->cgpa;
        $this->grade = $model->grade;
        $this->percentage = $model->percentage;
        $this->net_qualifying_date = $model->net_qualifying_date;
        $this->mphil_phd_registration_no = $model->mphil_phd_registration_no;
        $this->division = $model->division;
        $this->mphil_phd_registration_date = $model->mphil_phd_registration_date;
        $this->mphil_phd_project_title = $model->mphil_phd_project_title;
    }

    public function loadQualificationList()
    {
        if (empty($this->applicantPostId)) {
            return false;
        }
        // find applicant classified id
        $applicantQualificationModel = \common\models\ApplicantQualification::findByApplicantPostId($this->applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => 'all']);
        return $applicantQualificationModel;
    }

    public function loadOtherDetails()
    {
        // find applicant classified id
                
        if (empty($this->applicantPostId)) {
            return false;
        }
        $model = ApplicantDetail::findByApplicantPostId($this->applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

        if ($model === NULL) {
            return false;
        }
        $this->is_employed = $model->is_employed;
        $this->is_debarred = $model->is_debarred;
        $this->debarred_from_date = $model->debarred_from_date;
        $this->debarred_to_date = $model->debarred_to_date;
        $this->is_domiciled = $model->is_domiciled;
        $this->is_exserviceman = $model->is_exserviceman;
        $this->exserviceman_qualification_certificate = $model->exserviceman_qualification_certificate;
    }

    public function loadEmploymentDetails()
    {
         if (empty($this->applicantEmploymentId)) {
            return false;
        }
        // find applicant classified id
        $model = \common\models\ApplicantEmployment::findById($this->applicantEmploymentId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

        if ($model === NULL) {
            return false;
        }

        $this->employer = $model->employer;
        $this->office_name = $model->office_name;
        $this->designation = $model->designation;
        $this->employment_type_id = $model->employment_type_id;
        $this->start_date = $model->start_date;
        $this->end_date = $model->end_date;
        $this->experience_type_id = $model->experience_type_id;
        $this->employment_nature_id = $model->employment_nature_id;
    }

    public function loadEmploymentList()
    {
        if(empty($this->applicantPostId)){
            return FALSE;
        }
        
        $applicantDetailModel = ApplicantDetail::findByApplicantPostId($this->applicantPostId, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
        ]);

        if ($applicantDetailModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant post detail doesn't exists.");
        }
        
        $this->is_employed = $applicantDetailModel->is_employed;
        // find applicant classified id
        $applicantEmploymentModel = [];
        if (!empty($this->is_employed)) {
            $applicantEmploymentModel = \common\models\ApplicantEmployment::findByApplicantPostId($this->applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => 'all']);
        } else {
            \common\models\ApplicantEmployment::deleteAll('applicant_post_id=:applicantPostId', [':applicantPostId' => $this->applicantPostId]);
        }

        return $applicantEmploymentModel;
    }

    public function loadApplicantDocuments()
    {
        if(empty($this->applicantPostId)){
            return FALSE;
        }
        // find applicant classified id
        $applicantDocumentModel = \common\models\ApplicantDocument::findByApplicantPostId($this->applicantPostId, ['resultCount' => \common\models\caching\ModelCache::RETURN_ALL]);
        if (!empty($applicantDocumentModel)) {
            foreach ($applicantDocumentModel as $applicantDocument) {
                if ($applicantDocument['type'] == \common\models\ApplicantDocument::TYPE_USER_PHOTO) {
                    $this->photo = $applicantDocument['media_id'];
                }
                else if ($applicantDocument['type'] == \common\models\ApplicantDocument::TYPE_USER_SIGNATURE) {
                    $this->signature = $applicantDocument['media_id'];
                }
                else if ($applicantDocument['type'] == \common\models\ApplicantDocument::TYPE_USER_BIRTH_CERTIFICATE) {
                    $this->birth_certificate = $applicantDocument['media_id'];
                }
                else if ($applicantDocument['type'] == \common\models\ApplicantDocument::TYPE_USER_CASTE_CERTIFICATE) {
                    $this->caste_certificate = $applicantDocument['media_id'];
                }
                else if ($applicantDocument['type'] == \common\models\ApplicantDocument::TYPE_USER_EMPLOYMENT_CERTIFICATE) {
                    $this->upload_employment_certificate[$applicantDocument['reference_id']] = $applicantDocument['media_id'];
                }
            }
        }
    }

    public function generatePassword()
    {
        $length = 10;
        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'), ['@', '!', '&']);
        shuffle($chars);

        $password = implode(array_slice($chars, 0, $length));
        return $password;
    }

    private function updateFormStep($step)
    {
        \common\models\Applicant::updateAll(['form_step' => $step], 'id=:applicantId', [':applicantId' => $this->id]);
    }
    
    private function updateEserviceTab($index)
    {
        if ($this->is_eservice) {
            $applicantPost = \common\models\ApplicantPost::findById($this->applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            $applicantPost->eservice_tabs = substr_replace($applicantPost->eservice_tabs, 1, $index, 1);
            $applicantPost->save(true, ['eservice_tabs']);
        }
    }
    
    /**
     * validate age criteria
     * @return boolean
     * @throws \components\exceptions\AppException
     */
    public function validatePosts()
    {
        $this->_applicantDetailModel = ApplicantDetail::findByApplicantPostId($this->applicantPostId);
        if (empty($this->_applicantDetailModel)) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists.");
        }

        $mstPostFee = \common\models\MstPostFee::getPostFee(['classifiedId' => $this->classifiedId, 'applicantPostId' => $this->applicantPostId], $this->_applicantDetailModel);
        $this->fee_amount = $mstPostFee['amount'];
        
        /* $mstPostAge = \common\models\MstPostAge::getPostAge(['classifiedId' => $this->classifiedId, 'applicantPostId' => $this->applicantPostId], $this->_applicantDetailModel);        
          $ageValidatorCompoment = new \frontend\components\AgeValidatorComponent();
          $ageValidatorCompoment->classifiedId = $this->classifiedId;
          $ageValidatorCompoment->dob = $this->_applicantDetailModel['date_of_birth'];
          $ageValidatorCompoment->minAge = $mstPostAge['min_age'];
          $ageValidatorCompoment->maxAge = $mstPostAge['max_age'];
          if (!$ageValidatorCompoment->validate()) {
          $this->addError("applicant_post_criteria_id", "Sorry, you are not eligible due to age criteria.");
          return false;
          } */

        return true;
    }
    
    private function calculateAge($dob)
    {
        return date_diff(date_create($dob), date_create(\common\models\MstClassified::AGE_CALCULATE_DATE))->y;
    }

    
    public function postValidation()
    {
        
        $return = true;
        $mstPostCriteria = \common\models\MstPostCriteria::findByPostId($this->applicant_post_criteria_id);
        if ($mstPostCriteria === null) {
            $this->addError("applicant_post_criteria_id", "Invalid Post Criteria.");
            return false;
        }

        $additionalQualificationList = \common\models\MstPostQualification::findByPostCriteriaId($mstPostCriteria['id'], [
                    'selectCols' => [new \yii\db\Expression("DISTINCT mst_qualification.id, mst_qualification.name"),],
                    'qualificationId' => $this->criteria_qualification_id,
                    'joinWithAdditionalQualification' => 'innerJoin',
                    'groupBy' => ['mst_post_qualification.option_seq'],
                    'isActive' => ModelCache::IS_ACTIVE_YES,
                    'isDeleted' => ModelCache::IS_DELETED_NO,
                    'returnAll' => ModelCache::RETURN_ALL
        ]);
        
        return $return;
    }
    
    /**
     * criteria validation according to classified
     * @return boolean
     */
    public function criteriaValidation()
    {
        $function = '__scenario' . $this->classifiedId;
        return $this->$function();
    }
    
    private function __scenario5()
    {
        if (!empty($this->posts)) {
            $flag = false;
            foreach ($this->posts as $postId => $record) {
                if (!isset($record['post_id'])) {
                    continue;
                }
                $flag = true;
                $ageParams = [
                    'classifiedId' => $this->classifiedId,
                    'applicantPostId' => $this->applicantPostId,
                    'postId' => $postId
                ];
                $postName = MstPost::getTitle($postId);
                $criteria = Helper::calculateAge($ageParams, $this->_applicantDetailModel);
                if (!$criteria) {
                    $this->addError("university_id", "You're not eligible for this post {$postName} due to age criteria.");
                    return false;
                }
                if (!empty($this->_applicantDetailModel['disability_id']) && $this->_applicantDetailModel['disability_id'] != \common\models\MstListType::NOT_APPLICABLE && !ArrayHelper::isIn($this->_applicantDetailModel['disability_id'], [36, 191])) {
                    $this->addError("applicant_post_criteria_id", "If You're PH/Divyang only Hearing Impairment or Locomotor Disability eligible for this post {$postName}.");
                    return false;
                }
                $mstPostQualification = \common\models\MstPostQualification::findByPostId($postId, ['selectCols' => ['id', 'option_seq', 'qualification_id'], 'returnAll' => ModelCache::RETURN_ALL]);
                $options = $optionsSeq = [];
                foreach ($mstPostQualification as $key => $postQualification) {
                    $optionsSeq[$postQualification['option_seq']][] = $postQualification;
                }
                $c = 1;
                $gq = [];
                foreach ($record as $key => $value) {
                    if ($key == 'post_id') {
                        continue;
                    }
                    
                    if (ArrayHelper::isIn($postId, [8]) && $c == 2 && isset($gq[1]['q']) && !ArrayHelper::isIn($gq[1]['q'], [2, 19, 617])) {
                        $c++;
                        continue;
                    } else if (empty($value)) {
                        $this->addError("applicant_post_criteria_id", "Conidition {$c} cannot be empty for post {$postName}.");
                        return false;
                    }
                    if (!empty($value) && $key == 'field1') {
                        $apq = \common\models\ApplicantQualification::findByApplicantPostId($this->applicantPostId, [
                                    'selectCols' => ['id'],
                                    'qualificationTypeId' => $value
                        ]);
                        
                        if (empty($apq)) {
                            $apq = \common\models\ApplicantQualification::findByApplicantPostId($this->applicantPostId, [
                                        'selectCols' => ['id'],
                                        'qualificationDegreeId' => $value
                            ]);

                            if (empty($apq)) {
                                $this->addError("applicant_post_criteria_id", "Please add qualificaiton in step 3 for this post {$postName}.");
                                return false;
                            }
                        }

                        $gq[$c]['q'] = $value; // store qualification in array
                    }
                    
                    if (!empty($value) && $key == 'field2') {
                        $applicantEmploymentModel = \common\models\ApplicantEmployment::findByApplicantPostId($this->applicantPostId, [
                                    'resultCount' => ModelCache::RETURN_ALL
                        ]);
                        if (empty($applicantEmploymentModel)) {
                            $this->addError("applicant_post_criteria_id", "Please add Two  years experience as System Assistant or Computer/desktop Engineer or on higher post in the field of computers and having basic knowledge in computers like operating the computers, windows and Linux Operating Systems and typing out and taking print outs etc in Work Experience Details tab.");
                            return false;
                        }

                        $year = $month = $day = 0;
                        foreach ($applicantEmploymentModel as $key => $applicantEmployment) {
                            $end = empty($applicantEmployment['end_date']) ? date('Y-m-d') : $applicantEmployment['end_date'];
                            $endDate = new \DateTime($end . "T00:00:00");
                            $startDate = new \DateTime($applicantEmployment['start_date']);
                            $diff = $endDate->diff($startDate);
                            $year += $diff->y;
                            $month += $diff->m;
                            $day += $diff->d;
                        }

                        $month = ($day >= 30) ? ($month + floor($day / 30)) : $month;
                        $year = ($month >= 12) ? ($year + round($month / 12, 0)) : $year;
                        if ($year < 2) {
                            $this->addError("applicant_post_criteria_id", "Do you have at least Eight (2) years of experience for this post.");
                            return false;
                        }
                    }
                    $c++;
                }
            }
            if ($flag == false) {
                $this->addError("applicant_post_criteria_id", "Please choose any one post.");
                return false;
            }
        } else {
            $this->addError("applicant_post_criteria_id", "Please choose any one post.");
            return false;
        }
        return true;
    }
    
    private function __scenario4()
    {
        if (!empty($this->posts)) {
            $flag = false;
            foreach ($this->posts as $postId => $record) {
                if (!isset($record['post_id'])) {
                    continue;
                }
                $flag = true;
                $ageParams = [
                    'classifiedId' => $this->classifiedId,
                    'applicantPostId' => $this->applicantPostId,
                    'postId' => $postId
                ];
                $postName = MstPost::getTitle($postId);
                $criteria = Helper::calculateAge($ageParams, $this->_applicantDetailModel);
                if (!$criteria) {
                    $this->addError("university_id", "You're not eligible for this post {$postName} due to age criteria.");
                    return false;
                }
                if (!empty($this->_applicantDetailModel['disability_id']) && $this->_applicantDetailModel['disability_id'] != \common\models\MstListType::NOT_APPLICABLE && !ArrayHelper::isIn($this->_applicantDetailModel['disability_id'], [36, 191])) {
                    $this->addError("applicant_post_criteria_id", "If You're PH/Divyang only Hearing Impairment or Locomotor Disability eligible for this post {$postName}.");
                    return false;
                }
                $mstPostQualification = \common\models\MstPostQualification::findByPostId($postId, ['selectCols' => ['id', 'option_seq', 'qualification_id'], 'returnAll' => ModelCache::RETURN_ALL]);
                $options = $optionsSeq = [];
                foreach ($mstPostQualification as $key => $postQualification) {
                    $optionsSeq[$postQualification['option_seq']][] = $postQualification;
                }
                $c = 1;
                $gq = [];
                foreach ($record as $key => $value) {
                    if ($key == 'post_id') {
                        continue;
                    }
                    $exq = explode("~", $value);
                    if (ArrayHelper::isIn($postId, [2]) && $c == 6) {
                        $c++;
                        continue;
                    } else if (ArrayHelper::isIn($postId, [3, 7]) && $c == 2) {
                        $c++;
                        continue;
                    } else if (empty($value)) {
                        $this->addError("applicant_post_criteria_id", "Conidition {$c} cannot be empty for post {$postName}.");
                        return false;
                    }
                    if (isset($exq[0]) && !empty($exq[0])) {
                        $apq = \common\models\ApplicantQualification::findByApplicantPostId($this->applicantPostId, [
                                    'selectCols' => ['id'],
                                    'qualificationTypeId' => $exq[0]
                        ]);

                        if (empty($apq)) {
                            $apq = \common\models\ApplicantQualification::findByApplicantPostId($this->applicantPostId, [
                                        'selectCols' => ['id'],
                                        'qualificationDegreeId' => $exq[0]
                            ]);

                            if (empty($apq)) {
                                $this->addError("applicant_post_criteria_id", "Please add qualificaiton in step 3 for this post {$postName}.");
                                return false;
                            }
                        }

                        $gq[$c]['q'] = $exq[0]; // store qualification in array
                    }
                    $c++;
                }
            }
            if ($flag == false) {
                $this->addError("applicant_post_criteria_id", "Please choose any one post.");
                return false;
            }
        } else {
            $this->addError("applicant_post_criteria_id", "Please choose any one post.");
            return false;
        }
        return true;
    }
    
    public function checkPostPayment($applicantId)
    {
        if ($this->is_eservice) {
            $applicantPostModel = \common\models\ApplicantPost::findByApplicantId($applicantId, [
                        'id' => $this->applicantPostId,
                        'classifiedId' => $this->classifiedId,
                        'notPostId' => \common\models\MstPost::MASTER_POST,
                        'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE,
            ]);
        } else {
            $applicantPostModel = \common\models\ApplicantPost::findByApplicantId($applicantId, [
                        'classifiedId' => $this->classifiedId,
                        'notPostId' => \common\models\MstPost::MASTER_POST,
                        'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_PENDING,
            ]);
        }

        if(empty($applicantPostModel)){
            throw new \components\exceptions\AppException('Oops !! Post not found');
        }
        
        $module = \common\models\ApplicantFee::MODULE_APPLICATION;
        if ($this->is_eservice) {
            $module = \common\models\ApplicantFee::MODULE_ESERVICE;
        }

        $applicantFeeModel = \common\models\ApplicantFee::findByApplicantId($applicantId, [
            'applicantPostId' => $applicantPostModel['id'],
            'module' => $module,
            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if(empty($applicantFeeModel)){
            throw new \components\exceptions\AppException('Oops !! Fee not found');
        }
        
        if(!empty($applicantFeeModel) && $applicantFeeModel->status == \common\models\ApplicantFee::STATUS_PAID){
            return [
                'feeStatus' => true,
                'feeId' => Yii::$app->security->hashData($applicantFeeModel->id, Yii::$app->params['hashKey']),
            ];
        }
        
        return [
            'feeStatus' => false,
            'feeId' => Yii::$app->security->hashData($applicantFeeModel->id, Yii::$app->params['hashKey']),
        ];
    }
    
    public function saveUttrakhandWomen()
    {
        if (empty($this->applicantPostId)) {
            return false;
        }
        // find applicant classified id
        $model = ApplicantDetail::findByApplicantPostId($this->applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            return false;
        }
        $model->is_uttrakhand_women = null;
        if (!empty($model->is_domiciled)) {
            $model->is_uttrakhand_women = ($model->gender == 'FEMALE') ? ModelCache::IS_ACTIVE_YES : ModelCache::IS_ACTIVE_NO;
        }

        $model->save(true, ['is_uttrakhand_women']);
    }
    
    public function saveApplicantCriteria()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            
            $applicantPostModel = \common\models\ApplicantPost::findByApplicantId(Yii::$app->applicant->id, [
                'classifiedId' => $this->classifiedId,
                'notPostId' => MstPost::MASTER_POST,
                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_PENDING,
            ]);
            
            $applicantMasterPostModel = \common\models\ApplicantPost::findByApplicantId(Yii::$app->applicant->id, [
                'postId' => MstPost::MASTER_POST
            ]);

            if ($applicantPostModel === NULL) {

                $applicantPostModel = new \common\models\ApplicantPost;
                $applicantPostModel->loadDefaultValues(TRUE);
                $applicantPostModel->isNewRecord = true;
            }
            
            $applicantPostModel->applicant_id = Yii::$app->applicant->id;
            $applicantPostModel->same_as_present_address = $applicantMasterPostModel['same_as_present_address'];            
            $applicantPostModel->classified_id = $this->classifiedId;
            $applicantPostModel->post_id = !empty($applicantPostModel->post_id) ? $applicantPostModel->post_id : MstPost::CLASSIFIED_POST_ID;

            if (!$applicantPostModel->save()) {
                $this->addErrors($applicantPostModel->errors);
                return false;
            }
            
            $applicantPostId = $applicantPostModel->id;
            \common\models\ApplicantCriteria::deleteAll('applicant_post_id=:applicantPostId', [':applicantPostId' => $applicantPostId]);
            \common\models\ApplicantPostDetail::deleteAll('applicant_post_id=:applicantPostId', [':applicantPostId' => $applicantPostId]);
            $function = "_". $this->classifiedId;
            $this->$function($applicantPostId);

            if (!empty($this->fee_amount))
            {
                $applicantFee = \common\models\ApplicantFee::findByApplicantId($this->id, [
                            'applicantPostId' => $applicantPostId,
                            'payStatus' => \common\models\ApplicantFee::STATUS_UNPAID,
                            'module' => \common\models\ApplicantFee::MODULE_APPLICATION,
                            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                ]);

                if ($applicantFee === null) {
                    $applicantFee = new \common\models\ApplicantFee();
                    $applicantFee->isNewRecord = true;
                    $applicantFee->module = \common\models\ApplicantFee::MODULE_APPLICATION;
                }

                $applicantFee->fee_amount = $this->fee_amount;
                $applicantFee->applicant_id = $this->id;
                $applicantFee->applicant_post_id = $applicantPostId;
                if (!$applicantFee->save()) {
                    $this->addErrors($applicantFee->errors);
                    return false;
                }
            }

            if ($this->applicantPostFormStep < 6) {
                $this->updateFormStep(6);
            }

            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }
    
    private function _5($applicantPostId)
    {
        if (!empty($this->posts)) {
            foreach ($this->posts as $postId => $record) {
                if (isset($record['post_id']) && !empty($record['post_id'])) {
                    $applicantPostDetail = new \common\models\ApplicantPostDetail;
                    $applicantPostDetailId = $applicantPostDetail->create(['applicant_post_id' => $applicantPostId, 'post_id' => $postId]);
                    if (!$applicantPostDetailId) {
                        $this->addError("applicant_post_criteria_id", "Unable to save Applicant Post Detail.");
                        return false;
                    }
                    
                    $criteriaData = [
                        'applicant_post_id' => $applicantPostId,
                        'applicant_post_detail_id' => $applicantPostDetailId,
                        'applicant_id' => Yii::$app->applicant->id,
                        'is_applied_category' => ModelCache::IS_ACTIVE_NO,
                        'field1' => isset($record['field1']) ? $record['field1'] : NULL,
                        'field2' => isset($record['field2']) ? $record['field2'] : NULL,
                    ];

                    $model = new \common\models\ApplicantCriteria;
                    $applicantCriteriaId = $model->create($criteriaData);
                    if (!$applicantCriteriaId) {
                        $this->addError("applicant_post_criteria_id", "Unable to save Applicant Criteria.");
                        return false;
                    }
                }
            }
        }
    }
    
    private function _4($applicantPostId)
    {
        if (!empty($this->posts)) {
            foreach ($this->posts as $postId => $record) {
                if (isset($record['post_id']) && !empty($record['post_id'])) {
                    $applicantPostDetail = new \common\models\ApplicantPostDetail;
                    $applicantPostDetailId = $applicantPostDetail->create(['applicant_post_id' => $applicantPostId, 'post_id' => $postId]);
                    if (!$applicantPostDetailId) {
                        $this->addError("applicant_post_criteria_id", "Unable to save Applicant Post Detail.");
                        return false;
                    }
                    
                    foreach ($record as $key => $value) {
                        if ($key == 'post_id') {
                            continue;
                        }
                        if (!empty($value)) {
                            $exq = explode("~", $value);

                            $criteriaData = [
                                'applicant_post_id' => $applicantPostId,
                                'applicant_post_detail_id' => $applicantPostDetailId,
                                'applicant_id' => Yii::$app->applicant->id,
                                'is_applied_category' => ModelCache::IS_ACTIVE_NO,
                                'field1' => isset($exq[0]) ? $exq[0] : NULL, // qualification_id
                                'field2' => (isset($exq[1]) && !empty($exq[1])) ? $exq[1] : NULL, // university_id
                                'field3' => (isset($exq[2]) && !empty($exq[2])) ? $exq[2] : NULL
                            ];

                            $model = new \common\models\ApplicantCriteria;
                            $applicantCriteriaId = $model->create($criteriaData);
                            if (!$applicantCriteriaId) {
                                $this->addError("applicant_post_criteria_id", "Unable to save Applicant Criteria.");
                                return false;
                            }
                        }
                    }
                    
                }
            }
        }
    }

    public function loadApplicantCriteriaDetails()
    {
        // find applicant classified id
        if (empty($this->applicantPostId)) {
            return false;
        }
        $conditions = [
            'classifiedId' => $this->classifiedId,
            'notPostId' => MstPost::MASTER_POST,
            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ];
        // if normal registration
        if (!$this->is_eservice) {
            $conditions['applicationStatus'] = \common\models\ApplicantPost::APPLICATION_STATUS_PENDING;
        }
        $applicantPost = \common\models\ApplicantPost::findByApplicantId($this->id, $conditions);

        if ($applicantPost === NULL) {
            return false;
        }

        $model = \common\models\ApplicantCriteria::findByApplicantPostId($applicantPost->id, [
                    'selectCols' => ['applicant_criteria.*', 'applicant_post_detail.post_id'],
                    'joinWithApplicantPostDetail' => 'innerJoin',
                    'applicantId' => $this->id,
                    'returnAll' => true
        ]);

        if ($model === NULL) {
            return false;
        }
        
        $function = 'loadData'.$this->classifiedId;
        $this->posts =  \common\models\ApplicantCriteria::$function($model);
    }
    
    public function loadEserviceFee()
    {
        // find applicant classified id                
        if (empty($this->applicantPostId)) {
            return false;
        }

        $applicantPost = \common\models\ApplicantPost::findById($this->applicantPostId, [
                    'notPostId' => MstPost::MASTER_POST,
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                    'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE
        ]);

        if ($applicantPost === NULL) {
            return false;
        }

        $model = \common\models\ApplicantFee::findByApplicantId($this->id, [
                    'module' => \common\models\ApplicantFee::MODULE_ESERVICE,
                    'applicantPostId' => $this->applicantPostId,
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                    'payStatus' => \common\models\ApplicantFee::STATUS_UNPAID
        ]);
        
        $parentApplicantPost = \common\models\ApplicantPost::findByParentApplicantPostId($applicantPost->parent_applicant_post_id, ['countOnly' => true]);

        
        $fee = $applicantPost->classified->eservices_fee;
        if ($applicantPost->eservice_tabs == \common\models\ApplicantPost::ESERVICE_TAB_QUALIFICATION_VALUE && $parentApplicantPost == \common\models\ApplicantPost::QUALIFICATION_ESERVICE_LIMIT) {
            $fee = \common\models\ApplicantFee::FREE_FEE;
        }

        if ($model === null) {
            $model = new \common\models\ApplicantFee();
            $model->isNewRecord = true;
            $model->module = \common\models\ApplicantFee::MODULE_ESERVICE;
            $model->fee_amount = $fee;
            $model->applicant_id = $this->id;
            $model->applicant_post_id = $applicantPost->id;
        }

        $model->fee_amount = $fee;
        if (!$model->save()) {
            $this->addErrors($model->errors);
            return false;
        }

        $this->fee_amount = $fee;
        $this->application_no = $applicantPost->application_no;
    }
    
    /**
     * validate documents
     * @return boolean
     */
    public function validateDocuments($applicantEmployment = null)
    {
        if (empty($this->photo) || empty($this->signature) || empty($this->birth_certificate)) {
            return false;
        }

        if (isset($applicantEmployment) && !empty($applicantEmployment)) {
            foreach ($applicantEmployment as $employment) {
                if (!isset($this->upload_employment_certificate[$employment->id]) || empty($this->upload_employment_certificate[$employment->id])) {
                    return false;
                }
            }
        }

        return true;
    }
}
