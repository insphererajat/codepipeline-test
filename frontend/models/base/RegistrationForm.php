<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models\base;

use Yii;
use common\models\ExamCentre;
use common\models\caching\ModelCache;
use common\models\ExamCentreAddress;
use common\models\ExamCentreDocument;
use components\Security;

/**
 * Description of RegistrationForm
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class RegistrationForm extends \yii\base\Model
{

    public $id;
    public $guid;
    
    
    //basic detail
    public $firstname;
    public $email;
    public $mobile;
    
    
    public $address;
    public $udise_code;
    public $name;
    public $school_address;
    public $block_tehsil_ward;
    public $country_code;
    public $state_code;
    public $district_code;
    public $pincode;
    public $principal_name;
    
    
    public $academic_year_code;
    public $exam_year_code;
    public $regional_centre_code;
    public $application_status;
    public $is_application_submit;


    public $center_type;
    public $category;
    public $study_centre_code;
    public $school_mobile;
    public $std_code;
    public $phone;
    public $priority;
    public $is_mobile_verified;
    public $is_email_verified;
       
    public $superintendent_name;
    public $superintendent_designation;
    public $superintendent_address;
    public $superintendent_country_code;
    public $superintendent_state_code;
    public $superintendent_district_code;
    public $superintendent_pincode;
    public $superintendent_std_code;
    public $superintendent_telephone_r;
    public $superintendent_telephone_o;
    public $superintendent_mobile;
    public $superintendent_email;

    public $postoffice_name;
    public $postoffice_address;
    public $postoffice_country_code;
    public $postoffice_state_code;
    public $postoffice_district_code;
    public $postoffice_pincode;
    public $postoffice_email;
    public $postoffice_mobile;
    
    public $bankmanager_name;
    public $bankmanager_designation;
    public $bank_country_code;
    public $bank_state_code;
    public $bank_district_code;
    public $bank_pincode;
    public $bank_address;
    public $bank_std_code;
    public $bank_telephone_o;
    public $bank_email_address;
    public $bank_mobile;
    
    public $bankmanager_resi_address;
    public $bankmanager_resi_country_code;
    public $bankmanager_resi_state_code;
    public $bankmanager_resi_district_code;
    public $bankmanager_resi_pincode;  
    public $bankmanager_std_code;
    public $bankmanager_telephone_r;
    public $bankmanager_mobile;
    public $bankmanager_email_address;
    
    
    public $affiliation_no;
    public $affiliation_valid_upto;
    public $affiliation_board_code;
    public $affiliation_level;
    public $affiliation_status;
    public $bank_account_holder_name;
    public $bank_account_number;
    public $bank_branch_name;
    public $ifsc_code;
    public $nearest_police_station;
    public $police_station_telephone_no;
    public $distance_from_police_station;
    public $distance_from_bank;
    public $is_boundary_wall;
    public $is_cctv_available;
    public $hall;
    public $total_room;
    public $capacity;
    public $total_teacher;
    public $total_student;
    public $latitude;
    public $longitude;
    public $geoAddress;


    public $photo;
    public $signature;
    public $affiliation_certificate;
    public $school_image;

    public $applyRules = true;
    public $autoLogin = true;

    const SCENARIO_OTP_SCREEN = 'otp';
    const SCENARIO_FIRST_STEP = 'firstStep';
    const SCENARIO_SECOND_STEP = 'secondStep';
    const SCENARIO_THIRD_STEP = 'thirdStep';
    const SCENARIO_FOURTH_STEP = 'fourthStep';
    const SCENARIO_FIFTH_STEP = 'fifthStep';
    const SCENARIO_SIXTH_STEP = 'sixthStep';
    const SCENARIO_SEVENTH_STEP = 'seventhStep';

    public function rules()
    {
        return [
            //First Step
            [['firstname','email','mobile'] ,'required','on' => self::SCENARIO_OTP_SCREEN],
            //[['firstname','name', 'principal_name', 'mobile', 'school_address', 'country_code', 'state_code', 'district_code', 'block_tehsil_ward', 'pincode', 'email'], 'required', 'on' => self::SCENARIO_FIRST_STEP],
            [['udise_code', 'guid'], 'string'],
            //[['study_centre_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\StudyCentre::className(), 'targetAttribute' => ['study_centre_code' => 'code']],
            [['country_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstCountry::className(), 'targetAttribute' => ['country_code' => 'code']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstState::className(), 'targetAttribute' => ['state_code' => 'code']],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstDistrict::className(), 'targetAttribute' => ['district_code' => 'code']],
            //[['regional_centre_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstRegional::className(), 'targetAttribute' => ['regional_centre_code' => 'code']],
            //Second Step 
            [['superintendent_name', 'superintendent_designation', 'superintendent_address', 'superintendent_country_code', 'superintendent_state_code', 'superintendent_district_code', 'superintendent_pincode', 'superintendent_mobile', 'superintendent_email'], 'required', 'on' => self::SCENARIO_SECOND_STEP],
            [['superintendent_telephone_o', 'superintendent_telephone_r', 'superintendent_std_code'], 'integer'],
            [['superintendent_country_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstCountry::className(), 'targetAttribute' => ['superintendent_country_code' => 'code']],
            [['superintendent_state_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstState::className(), 'targetAttribute' => ['superintendent_state_code' => 'code']],
            [['superintendent_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstDistrict::className(), 'targetAttribute' => ['superintendent_district_code' => 'code']],
            // Third Step
            [['postoffice_name', 'postoffice_address', 'postoffice_country_code', 'postoffice_state_code', 'postoffice_district_code', 'postoffice_pincode', 'postoffice_email', 'postoffice_mobile'], 'required', 'on' => self::SCENARIO_THIRD_STEP],
            [['postoffice_country_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstCountry::className(), 'targetAttribute' => ['postoffice_country_code' => 'code']],
            [['postoffice_state_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstState::className(), 'targetAttribute' => ['postoffice_state_code' => 'code']],
            [['postoffice_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstDistrict::className(), 'targetAttribute' => ['postoffice_district_code' => 'code']],
            // Fourth Step
            [['bankmanager_name', 'bankmanager_designation', 'bank_address', 'bank_country_code', 'bank_state_code', 'bank_district_code', 'bank_pincode',  'bank_std_code','bank_telephone_o', 'bank_mobile', 'bank_email_address'], 'required', 'on' => self::SCENARIO_FOURTH_STEP],
            [['bankmanager_resi_address', 'bankmanager_resi_country_code', 'bankmanager_resi_state_code',  'bankmanager_resi_district_code',  'bankmanager_resi_pincode', 'bankmanager_mobile', 'bankmanager_email_address'], 'required', 'on' => self::SCENARIO_FOURTH_STEP],
            [['bank_country_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstCountry::className(), 'targetAttribute' => ['bank_country_code' => 'code']],
            [['bank_state_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstState::className(), 'targetAttribute' => ['bank_state_code' => 'code']],
            [['bank_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstDistrict::className(), 'targetAttribute' => ['bank_district_code' => 'code']],
            [['bankmanager_resi_country_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstCountry::className(), 'targetAttribute' => ['bankmanager_resi_country_code' => 'code']],
            [['bankmanager_resi_state_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstState::className(), 'targetAttribute' => ['bankmanager_resi_state_code' => 'code']],
            [['bankmanager_resi_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\MstDistrict::className(), 'targetAttribute' => ['bankmanager_resi_district_code' => 'code']],
            // Fifth Step
            [['affiliation_board_code', 'affiliation_no', 'affiliation_valid_upto', 'affiliation_status', 'affiliation_level', 'total_student', 'total_teacher', 'is_cctv_available', 'priority'], 'required', 'on' => self::SCENARIO_FIFTH_STEP],
            [['is_boundary_wall', 'distance_from_bank', 'nearest_police_station', 'police_station_telephone_no', 'distance_from_police_station'], 'required', 'on' => self::SCENARIO_FIFTH_STEP],
            [['bank_account_holder_name', 'bank_account_number', 'bank_branch_name', 'ifsc_code', 'hall', 'total_room', 'capacity', 'total_teacher', 'total_student'], 'required', 'on' => self::SCENARIO_FIFTH_STEP],
            // validation Rules
            [['pincode', 'superintendent_pincode', 'postoffice_pincode', 'bank_pincode', 'bankmanager_resi_pincode'], 'string', 'max' => 6],
            [['mobile', 'superintendent_mobile', 'bankmanager_mobile', 'postoffice_mobile', 'bank_mobile'], 'string', 'max' => 10],
            [['mobile', 'superintendent_mobile', 'bankmanager_mobile', 'postoffice_mobile', 'bank_mobile'], 'match', 'pattern' => '/[6789][0-9]{9}/'],
            [['bank_email_address','bankmanager_email_address', 'superintendent_email', 'email', 'postoffice_email'], 'email'],
            //[['academic_year_code'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\AcademicYear::className(), 'targetAttribute' => ['academic_year_code' => 'code']],
            [['bank_account_number'], 'string', 'min' => 11],
            [['bank_account_number'], 'string', 'max' => 16],
            [['study_centre_code'], 'string', 'min' => 6],
            [['id', 'application_status', 'is_application_submit', 'is_mobile_verified','is_email_verified', 'bankmanager_std_code','bankmanager_telephone_r', 'priority'], 'integer'],
            [['geoAddress'], 'safe'],
            [['photo', 'signature'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024 * 1024, 'on' => self::SCENARIO_SIXTH_STEP],
            [['affiliation_certificate','school_image'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'], 'maxSize' => 1024 * 1024* 5, 'on' => self::SCENARIO_SIXTH_STEP],
            [['latitude','longitude'], 'required', 'on' => self::SCENARIO_SEVENTH_STEP],
            [['latitude','longitude'], 'number']
        ];
    }

    public function beforeValidate()
    {
        
        if (!$this->is_mobile_verified && $this->applyRules) {
            $this->addError("mobile", "Please verify mobile number before submiting form.");
            return false;
        }
        
        if (!$this->is_email_verified && $this->applyRules) {
            $this->addError("email", "Please verify email address before submiting form.");
            return false;
        }

        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name of the school/college/institute',
            'school_address' => 'School/college/institute address',
            'block_tehsil_ward' => 'Block/Tehsil/Ward',
            'country_code' => 'Country',
            'state_code' => 'State',
            'district_code' => 'District',
            'pincode' => 'Pincode',
            'principal_name' => 'Principal',
            'mobile' => 'Mobile number',
            'email' => 'Email address',
            'postoffice_name' => 'Post Office Name',
            'postoffice_address' => 'Post Office address',
            'postoffice_country_code' => 'Country',
            'postoffice_state_code' => 'State',
            'postoffice_district_code' => 'District',
            'postoffice_pincode' => 'Pincode',
            'bankmanager_name' => 'Name',
            'bankmanager_designation' => 'Designation',
            'bank_country_code' => 'Country',
            'bank_state_code' => 'State',
            'bank_district_code' => 'District',
            'bank_pincode' => 'Pincode',
            'bank_address' => 'Address',
            'bankmanager_resi_address' => 'Address',
            'bankmanager_resi_country_code' => 'Country',
            'bankmanager_resi_state_code' => 'State',
            'bankmanager_resi_district_code' => 'District',
            'bankmanager_resi_pincode' => 'Pincode',
            'bank_std_code' => 'Std code',
            'bank_telephone_o' => 'Office telephone',
            'bankmanager_telephone_r' => 'Residance telephone',
            'bankmanager_mobile' => 'Mobile number',
            'bankmanager_email_address' => 'Email Address',
            'bank_account_holder_name' => 'Account holder name',
            'bank_branch_name' => 'Branch',
            'ifsc_code' => 'Ifsc',
            'superintendent_country_code' => 'Country',
            'superintendent_state_code' => 'State',
            'superintendent_district_code' => 'District',
            'superintendent_mobile' => 'Mobile',
            'superintendent_mobile_resi' => 'Mobile',
            'superintendent_email' => 'Email',
            'superintendent_pincode' => 'Pincode',
            'school_mobile' => 'Mobile',
            'phone' => 'Telephone',
            'priority' => 'Type Of Institute'
        ];
    }

    public function saveBasicDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            if (!empty($this->guid)) {
                $model = \common\models\Applicant::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if (empty($model)) {
                    throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
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
                echo '<pre>';print_r($model);die;
            }


            if (!$model->save()) {
                $this->addErrors($model->errors);
                return false;
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

    public function saveSuperintendentDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $examCentre = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($examCentre)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
            }

            $model = ExamCentreAddress::findByCentreId($examCentre->id, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                        'addressType' => ExamCentreAddress::TYPE_SUPRITENDENT
            ]);
            if ($model === NULL) {
                $model = new ExamCentreAddress;
                $model->loadDefaultValues(true);
                $this->updateFormStep(2);
            }

            $model->exam_centre_id = $examCentre->id;
            $model->address_type = ExamCentreAddress::TYPE_SUPRITENDENT;
            $model->name = $this->superintendent_name;
            $model->designation = $this->superintendent_designation;
            $model->address1 = $this->superintendent_address;
            $model->country_code = $this->superintendent_country_code;
            $model->state_code = $this->superintendent_state_code;
            $model->district_code = $this->superintendent_district_code;
            $model->pincode = $this->superintendent_pincode;
            $model->std_code = $this->superintendent_std_code;
            $model->telephone_o = $this->superintendent_telephone_o;
            $model->telephone_r = $this->superintendent_telephone_r;
            $model->mobile = $this->superintendent_mobile;
            $model->email = $this->superintendent_email;
            if (!$model->save()) {
                $this->addErrors($model->errors);
                return false;
            }

            $transaction->commit();
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }

    public function savePostOfficeDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $examCentre = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($examCentre)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
            }

            $model = ExamCentreAddress::findByCentreId($examCentre->id, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                        'addressType' => ExamCentreAddress::TYPE_POSTOFFICE
            ]);
            if ($model === NULL) {
                $model = new ExamCentreAddress;
                $model->loadDefaultValues(true);
                $this->updateFormStep(3);
            }

            $model->exam_centre_id = $examCentre->id;
            $model->address_type = ExamCentreAddress::TYPE_POSTOFFICE;
            $model->name = $this->postoffice_name;
            $model->address1 = $this->postoffice_address;
            $model->country_code = $this->postoffice_country_code;
            $model->state_code = $this->postoffice_state_code;
            $model->district_code = $this->postoffice_district_code;
            $model->pincode = $this->postoffice_pincode;
            $model->mobile = $this->postoffice_mobile;
            $model->email = $this->postoffice_email;
            if (!$model->save()) {
                $this->addErrors($model->errors);
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return false;
    }

    public function saveBankAccountDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $examCentre = ExamCentre::findById($this->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($examCentre)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
            }

            $model = ExamCentreAddress::findByCentreId($examCentre->id, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                        'addressType' => ExamCentreAddress::TYPE_BANKOFFICE
            ]);

            if ($model === NULL) {
                $model = new ExamCentreAddress;
                $model->loadDefaultValues(true);
                $this->updateFormStep(4);
            }

            $model->exam_centre_id = $examCentre->id;
            $model->address_type = ExamCentreAddress::TYPE_BANKOFFICE;
            $model->name = $this->bankmanager_name;
            $model->designation = $this->bankmanager_designation;
            $model->address1 = $this->bank_address;
            $model->country_code = $this->bank_country_code;
            $model->state_code = $this->bank_state_code;
            $model->district_code = $this->bank_district_code;
            $model->pincode = $this->bank_pincode;
            $model->std_code = $this->bank_std_code;
            $model->telephone_o = $this->bank_telephone_o;
            $model->mobile = $this->bank_mobile;
            $model->email = $this->bank_email_address;
            if (!$model->save()) {
                $this->addErrors($model->errors);
                return false;
            }

            $bankManager = ExamCentreAddress::findByCentreId($examCentre->id, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                        'addressType' => ExamCentreAddress::TYPE_BANKMANAGER
            ]);

            if ($bankManager === NULL) {
                $bankManager = new ExamCentreAddress;
                $bankManager->loadDefaultValues(true);
            }
            $bankManager->exam_centre_id = $examCentre->id;
            $bankManager->address_type = ExamCentreAddress::TYPE_BANKMANAGER;
            $bankManager->name = $this->bankmanager_name;
            $bankManager->designation = $this->bankmanager_designation;
            $bankManager->address1 = $this->bankmanager_resi_address;
            $bankManager->country_code = $this->bankmanager_resi_country_code;
            $bankManager->state_code = $this->bankmanager_resi_state_code;
            $bankManager->district_code = $this->bankmanager_resi_district_code;
            $bankManager->pincode = $this->bankmanager_resi_pincode;
            $bankManager->mobile = $this->bankmanager_mobile;
            $bankManager->std_code = $this->bankmanager_std_code;
            $bankManager->telephone_r = $this->bankmanager_telephone_r;
            $bankManager->mobile = $this->bankmanager_mobile;
            $bankManager->email = $this->bankmanager_email_address;
            if (!$bankManager->save()) {
                $this->addErrors($bankManager->errors);
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return false;
    }

    public function saveAffiliationDetails()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $examCentre = ExamCentre::findById($this->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($examCentre)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
            }
            
            $examCentre->affiliation_board_code = $this->affiliation_board_code;
            $examCentre->affiliation_no = $this->affiliation_no;
            $examCentre->affiliation_valid_upto = date('Y-m-d', strtotime($this->affiliation_valid_upto));
            $examCentre->affiliation_status = $this->affiliation_status;
            $examCentre->affiliation_level = $this->affiliation_level;
            $examCentre->total_student = $this->total_student;
            $examCentre->total_teacher = $this->total_teacher;
            $examCentre->total_room = $this->total_room;
            $examCentre->hall = $this->hall;
            $examCentre->capacity = $this->capacity;
            $examCentre->is_cctv_available = $this->is_cctv_available;
            $examCentre->is_boundary_wall = $this->is_boundary_wall;
            $examCentre->distance_from_bank = $this->distance_from_bank;
            $examCentre->nearest_police_station = $this->nearest_police_station;
            $examCentre->police_station_telephone_no = $this->police_station_telephone_no;
            $examCentre->distance_from_police_station = $this->distance_from_police_station;
            $examCentre->bank_account_holder_name = $this->bank_account_holder_name;
            $examCentre->bank_account_number = $this->bank_account_number;
            $examCentre->bank_branch_name = $this->bank_branch_name;
            $examCentre->ifsc_code = $this->ifsc_code;
            $examCentre->priority = $this->priority;
            $examCentre->step = 5;

            if (!$examCentre->save()) {
                $this->addErrors($examCentre->errors);
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        return false;
    }
    
    public function uploadDocuments()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            
            $examCentre = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($examCentre)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
            }

            $documents = [];
            $uploadedDocuments = ExamCentreDocument::findByCentreId($this->id, ['resultCount' => ModelCache::RETURN_ALL]);
            if(!empty($uploadedDocuments)) {
                foreach($uploadedDocuments as $document) {
                    $documents[$document['type']] = $document['id'];
                }
            }
            
            if (!empty($this->photo)) {
                $mediaObj = $this->uploadFile($this->photo);
                if (!empty($mediaObj['orig'])) {
                    $mediaParams = [
                        'media_id' => $mediaObj['orig'],
                        'type' => ExamCentreDocument::PHOTO
                    ];
                    (new \common\models\ExamCentreDocument)->saveDocument($this->id, $mediaParams);
                }
            }
            else {
                if (empty($documents[ExamCentreDocument::PHOTO])) {
                    $this->addError('photo', 'Please upload principal photo.');
                    return false;
                }
            }
            
            if (!empty($this->signature)) {
                $mediaObj = $this->uploadFile($this->signature);
                if (!empty($mediaObj['orig'])) {
                    $mediaParams = [
                        'media_id' => $mediaObj['orig'],
                        'type' => ExamCentreDocument::SIGNATURE
                    ];
                    (new ExamCentreDocument)->saveDocument($this->id, $mediaParams);
                }
            }
            else {
                if (empty($documents[ExamCentreDocument::SIGNATURE])) {
                    $this->addError('signature', 'Please upload principal signature.');
                    return false;
                }
            }

            if (!empty($this->affiliation_certificate)) {
                $mediaObj = $this->uploadFile($this->affiliation_certificate);
                if (!empty($mediaObj['orig'])) {
                    $mediaParams = [
                        'media_id' => $mediaObj['orig'],
                        'type' => \common\models\ExamCentreDocument::AFFILIATION_CERTIFICATE
                    ];
                    (new \common\models\ExamCentreDocument)->saveDocument($this->id, $mediaParams);
                }
            }
            else {
                if (empty($documents[ExamCentreDocument::AFFILIATION_CERTIFICATE])) {
                    $this->addError('affiliation_certificate', 'Please upload affiliation certificate.');
                    return false;
                }
            }
            
            if (!empty($this->school_image)) {
                $mediaObj = $this->uploadFile($this->school_image);
                if (!empty($mediaObj['orig'])) {
                    $mediaParams = [
                        'media_id' => $mediaObj['orig'],
                        'type' => \common\models\ExamCentreDocument::SCHOOL_IMAGE
                    ];
                    (new \common\models\ExamCentreDocument)->saveDocument($this->id, $mediaParams);
                }
            }
            else {
                if (empty($documents[ExamCentreDocument::SCHOOL_IMAGE])) {
                    $this->addError('school_image', 'Please upload school campus photo.');
                    return false;
                }
            }
            
            $examCentre->step = 6;
            $examCentre->save(true, ['step']);
            
            $transaction->commit();
            return true;
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        return false;
    }
    
    public function saveGeoCoordinate()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            
            $model = ExamCentre::findById($this->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if (empty($model)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access exam centre doesn't exists.");
            }

            $model->latitude = $this->latitude;
            $model->longitude = $this->longitude;
            $model->step = 7;
            if (!$model->save(true, ['latitude', 'longitude', 'step'])) {
                $this->addErrors($model->errors);
                return false;
            }
            $transaction->commit();
            return true;
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return false; 
    }

    public function loadBasicDetails()
    {
        $model = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            return false;
        }
        $this->name = $model->name;
        $this->principal_name = $model->principal_name;
        $this->mobile = $model->mobile;
        $this->school_address = $model->address1;
        $this->country_code = $model->country_code;
        $this->state_code = $model->state_code;
        $this->district_code = $model->district_code;
        $this->block_tehsil_ward = $model->block_tehsil_ward;
        $this->pincode = $model->pincode;
        $this->mobile = $model->mobile;
        $this->email = $model->email;
        $this->regional_centre_code = $model->regional_centre_code;
        $this->is_mobile_verified = $model->is_mobile_verified;
        $this->is_email_verified = $model->is_email_verified;
        $this->application_status = $model->application_status;
        $this->is_application_submit = $model->is_application_submit;
        $this->study_centre_code = $model->study_centre_code;
        $this->udise_code = $model->udise_code;
    }

    public function loadAffiliationDetails()
    {
        $model = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            return false;
        }

        $this->affiliation_board_code = $model->affiliation_board_code;
        $this->affiliation_no = $model->affiliation_no;
        $this->affiliation_valid_upto = date('d-m-Y', strtotime($model->affiliation_valid_upto));
        $this->affiliation_status = $model->affiliation_status;
        $this->affiliation_level = $model->affiliation_level;
        $this->total_student = $model->total_student;
        $this->total_teacher = $model->total_teacher;
        $this->total_room = $model->total_room;
        $this->hall = $model->hall;
        $this->capacity = $model->capacity;
        $this->is_cctv_available = $model->is_cctv_available;
        $this->is_boundary_wall = $model->is_boundary_wall;
        $this->distance_from_bank = $model->distance_from_bank;
        $this->nearest_police_station = $model->nearest_police_station;
        $this->police_station_telephone_no = $model->police_station_telephone_no;
        $this->distance_from_police_station = $model->distance_from_police_station;
        $this->bank_account_holder_name = $model->bank_account_holder_name;
        $this->bank_account_number =  $model->bank_account_number;
        $this->bank_branch_name =$model->bank_branch_name;
        $this->ifsc_code =$model->ifsc_code;
        $this->bank_account_holder_name = $model->bank_account_holder_name;
        $this->bank_account_number = $model->bank_account_number;
        $this->bank_branch_name = $model->bank_branch_name;
        $this->ifsc_code = $model->ifsc_code;
        $this->priority = $model->priority;
                
    }

    public function loadSuperintendentDetails()
    {
        $model = ExamCentreAddress::findByCentreId($this->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'addressType' => ExamCentreAddress::TYPE_SUPRITENDENT]);
        if ($model === NULL) {
            return false;
        }

        $this->superintendent_name = $model->name;
        $this->superintendent_designation = $model->designation;
        $this->superintendent_address = $model->address1;
        $this->superintendent_country_code = $model->country_code;
        $this->superintendent_state_code = $model->state_code;
        $this->superintendent_district_code = $model->district_code;
        $this->superintendent_pincode = $model->pincode;
        $this->superintendent_std_code = $model->std_code;
        $this->superintendent_telephone_o = $model->telephone_o;
        $this->superintendent_telephone_r = $model->telephone_r;
        $this->superintendent_mobile = $model->mobile;
        $this->superintendent_email = $model->email;
    }

    public function loadPostOfficeDetails()
    {
        $model = ExamCentreAddress::findByCentreId($this->id, [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
                    'addressType' => ExamCentreAddress::TYPE_POSTOFFICE
        ]);
        if ($model === NULL) {
            return false;
        }
        $this->postoffice_name = $model->name;
        $this->postoffice_address = $model->address1;
        $this->postoffice_country_code = $model->country_code;
        $this->postoffice_state_code = $model->state_code;
        $this->postoffice_district_code = $model->district_code;
        $this->postoffice_pincode = $model->pincode;
        $this->postoffice_mobile = $model->mobile;
        $this->postoffice_email = $model->email;
    }

    public function loadBankAccountDetails()
    {

        $model = ExamCentreAddress::findByCentreId($this->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'addressType' => ExamCentreAddress::TYPE_BANKOFFICE]);
        if ($model === NULL) {
            return false;
        }

        $this->bankmanager_name = $model->name;
        $this->bankmanager_designation = $model->designation;
        $this->bank_address = $model->address1;
        $this->bank_country_code = $model->country_code;
        $this->bank_state_code = $model->state_code;
        $this->bank_district_code = $model->district_code;
        $this->bank_pincode = $model->pincode;
        $this->bank_std_code = $model->std_code;
        $this->bank_telephone_o = $model->telephone_o;
        $this->bank_mobile = $model->mobile;
        $this->bank_email_address = $model->email;

        $model = ExamCentreAddress::findByCentreId($this->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'addressType' => ExamCentreAddress::TYPE_BANKMANAGER]);
        if ($model === NULL) {
            return false;
        }

        $this->bankmanager_resi_address = $model->address1;
        $this->bankmanager_resi_country_code = $model->country_code;
        $this->bankmanager_resi_state_code = $model->state_code;
        $this->bankmanager_resi_district_code = $model->district_code;
        $this->bankmanager_resi_pincode = $model->pincode;
        $this->bankmanager_std_code = $model->std_code;
        $this->bankmanager_telephone_r = $model->telephone_r;
        $this->bankmanager_mobile = $model->mobile;
        $this->bankmanager_email_address = $model->email;
    }
    
    public function loadGeoCoordinates()
    {
        $model = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            return false;
        }

        $this->latitude = $model->latitude;
        $this->longitude = $model->longitude;
               
    }
    
    public function generatePassword()
    {
        $length = 10;
        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'), ['@','!','&']);
        shuffle($chars);

        $password = implode(array_slice($chars, 0, $length));
        return $password;
    }
    
    public function uploadFile($file)
    {
        $uploadDir = \Yii::$app->params['upload.dir'] . "/" . \Yii::$app->params['upload.dir.tempFolderName'];
        $fileName = time() . '_' . $file->name;
        $filePath = $uploadDir . '/' . $fileName;

        //saving the file to temporary folder
        if ($file->saveAs($filePath)) {

            $options = [
                'ProcessLocalFile' => true,
                'RenameFile' => true,
            ];
            $filePath = \Yii::getAlias('@webroot') . \Yii::$app->params['upload.baseHttpPath.relative'] . '/' . \Yii::$app->params['upload.dir.tempFolderName'] . '/' . $fileName;

            $model = new \common\models\Media;
            return $model->processLocalFileAndCreateNewMedia($filePath, $options);
        }
        
        return false;
    }
    
    public function buildAddress()
    {
        $model = ExamCentre::findByGuid($this->guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model === NULL) {
            return false;
        }
        
        $country = \common\models\MstCountry::findByCode($model->country_code);
        $state = \common\models\MstState::findByCode($model->state_code);
        $district = \common\models\MstDistrict::findByCode($model->district_code);
        
        
        $address = $model->address1.", ".$country['name'].", ".$state['name'].", ".$district['name'].", ".$model->pincode;
        $this->geoAddress = $address;

    }
    
    private function updateFormStep($step)
    {
        ExamCentre::updateAll(['step' => $step], 'id=:id', [':id' => $this->id]);
    }

}
