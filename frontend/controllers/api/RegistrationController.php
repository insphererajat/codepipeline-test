<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers\api;

use Yii;
use common\models\LogOtp;
use common\models\caching\ModelCache;
use common\models\Media;
use common\models\MstListType;
use components\Helper;
use components\Security;

/**
 * Description of Registration
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class RegistrationController extends ApiController
{
    public $attempts = 5; // allowed 5 attempts
    public $counter;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \components\behaviors\AccessControl::className(),
                'only' => ['get-qualification-degree', 'get-degree-subject', 'get-university', 'check-age-validation', 'get-category-and-disability-list', 'remove-media', 'get-calculate-age', 'remove-otr-document', 'delete-qualification', 'delete-employment'],
                'rules' => [
                    [
                        'actions' => ['get-qualification-degree', 'get-degree-subject', 'get-university', 'check-age-validation', 'get-category-and-disability-list', 'remove-media', 'get-calculate-age', 'remove-otr-document', 'delete-qualification', 'delete-employment'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!\common\models\Applicant::checkSessionHijackingPreventions(\common\models\Applicant::FRONTEND_LOGIN_KEY, \common\models\Applicant::FRONTEND_FIXATION_COOKIE, \common\models\Applicant::FRONTEND_SESSION_VALUE)) {
                                Yii::$app->applicant->logout();
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionSendOtp()
    {
        $model = new \frontend\models\VerifyOTPForm;

        $postData = Yii::$app->request->post();

        if (empty($postData['mobile'])) {
            throw new \components\exceptions\AppException('Oops! Mobile number can not be empty.');
        }

        if (empty($postData['email'])) {
            throw new \components\exceptions\AppException('Oops! Email can not be empty.');
        }
        
        $emailCount = \common\models\Applicant::findByEmail($postData['email'], ['countOnly' => true]);
        if ($emailCount > 0) {
            throw new \components\exceptions\AppException('Oops! Email already exist in database. Please try with login.');
        }
        $mobileCount = \common\models\Applicant::findByMobileNumber($postData['mobile'], ['countOnly' => true]);
        if ($mobileCount > 0) {
            throw new \components\exceptions\AppException('Oops! Mobile already exist in database.');
        }

        try {
            $sendOtp = $this->generateAndSendOtp($postData);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("While sending email or otp raise error.");
        }

        return Helper::outputJsonResponse([
                    'status' => 1,
                    'template' => $this->renderAjax('_otp_modal', ['model' => $model, 'mobileOtpId' => $sendOtp['mobileOtpId']]),
                    'message' => 'Otp Send successfully to Email: ' . $postData['email'] . ' and Mobile: ' . $postData['mobile']
        ]);
    }

    private function generateAndSendOtp($params = [])
    {
        // Send OTP in mobile
        if (!empty($params['mobile']) && !empty($params['email'])) {
            $otpLogModel = LogOtp::generateOtp(\common\models\LogOtp::MOBILE_OTP, $params['mobile']);
            //return true;
            \Yii::$app->sms->sendOtp($params['mobile'], ['otp' => $otpLogModel->otp, 'name' => isset($params['name']) ? $params['name'] : 'Applicant']);
            Yii::$app->email->sendRegistrationOtpEmail($params['email'], $params['name'], $otpLogModel->otp);
            \Yii::$app->session->remove('bruteForce');
        }

        return [
            'status' => 1,
            'mobileOtpId' => isset($otpLogModel->id) ? $otpLogModel->id : 0
        ];
    }

    public function actionValidateOtp()
    {
        $model = new \frontend\models\VerifyOTPForm();
        $model->scenario = $model::SCENARIO_VALIDATE_OTP;
        $success = 1;

        $time = time();

        $timeEncrytped = base64_encode($time);
        if (\Yii::$app->request->isPost) {

            if($this->bruteForceCheck()) {
                throw new \components\exceptions\AppException('Oops! You have exceeded the limit to perform this action.');
            }

            $post = \Yii::$app->request->post();
            $post['VerifyOTPForm']['mobileOtp'] = Security::cryptoAesDecrypt($post['VerifyOTPForm']['mobileOtp'], Yii::$app->params['hashKey']);
            $post['mobileOtpId'] = Security::cryptoAesDecrypt($post['mobileOtpId'], Yii::$app->params['hashKey']);
            $model->mobileOtpId = $post['mobileOtpId'];

            $otpValue = base64_encode($model->mobileOtpId);
            if ($model->load($post) && $model->validate()) {
                return Helper::outputJsonResponse(['success' => 1, 'encString' => $timeEncrytped, 'timestamp' => $time, 'otpValue' => $otpValue]);
            }
            $this->counter = Yii::$app->session->get('bruteForce') + 1;
            Yii::$app->session->set('bruteForce',$this->counter);
            $errors = $model->errors;
            return Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
        }

        return Helper::outputJsonResponse(['success' => $success, 'encString' => $timeEncrytped, 'timestamp' => $time]);
    }

    private function bruteForceCheck()
    {           
        return Yii::$app->session->get('bruteForce') >= $this->attempts;
    }

    public function actionReSendOtp()
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams)) {
            throw new \components\exceptions\AppException('Sorry, Invalid Args supplied !!');
        }

        $sendOtp = $this->generateAndSendOtp($postParams);
        if ($sendOtp['status']) {
            return Helper::outputJsonResponse([
                        'status' => 1,
                        'mobileOtpId' => $sendOtp['mobileOtpId'],
                        'message' => 'Otp Send successfully !!'
            ]);
        }

        return Helper::outputJsonResponse([
                    'status' => 0,
                    'message' => 'Oops!! can\'t sent OTP right now. Please try after sometimes'
        ]);
    }
    
    public function actionGetQualificationDegree()
    {
         
        $qualificationId = Yii::$app->request->post('id');
        if (empty($qualificationId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $qualificationDegreeModel = \common\models\MstQualification::getQualificationDropdown(['parentId' => $qualificationId]);
        $qualificationDegreeModel[\common\models\MstQualification::CHILD_OTHER] = 'Other';
        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $qualificationDegreeModel, 'prompt' => '']);

        return Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }
    
    public function actionGetDegreeSubject()
    {

        $degreeId = Yii::$app->request->post('degreeId');
        if (empty($degreeId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $qualificationDegreeSubjectModel = \common\models\MstQualificationSubject::getQualificationSubjectDropdown(['qualificationId' => $degreeId]);

        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $qualificationDegreeSubjectModel, 'prompt' => '']);

        return Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }
    
    public function actionGetUniversity()
    {

        $statecode = Yii::$app->request->post('statecode');
        $qualificationType = Yii::$app->request->post('qualificationType');
        if (empty($statecode)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        $params = [];
        if(\yii\helpers\ArrayHelper::isIn($qualificationType, [\common\models\MstQualification::PARENT_8TH, \common\models\MstQualification::PARENT_10TH, \common\models\MstQualification::PARENT_12])) {
            $params = ['parentId' => \common\models\MstUniversity::BOARD];
        }
        else if(\yii\helpers\ArrayHelper::isIn($qualificationType, [\common\models\MstQualification::CERTIFICATIONS])) {
            $universityModel = [\common\models\MstUniversity::OTHER => 'Other'];
            $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $universityModel]);
            return Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
        }
        else {
            $params = ['stateCode' => $statecode, 'parentId' => \common\models\MstUniversity::UNIVERSITY];
        }

        $universityModel = \common\models\MstUniversity::getUniversityDropdown($params);
        $universityModel[\common\models\MstUniversity::OTHER] = 'Other';
        $template = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $universityModel, 'prompt' => '']);

        return Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
    }
    
    public function actionGetPostDetail()
    {

        $id = Yii::$app->request->post('post_id');
        if (empty($id)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $postModel = \common\models\MstPost::findById($id);
        return Helper::outputJsonResponse(['success' => 1, 'data' => $postModel]);
    }
    
    public function actionCheckAgeValidation()
    {
        $applicantId = Yii::$app->applicant->id;
        $posts = Yii::$app->request->post();
        if (empty($posts) || empty($applicantId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        $response = \Yii::$app->criteria->validateAge($posts);
        return Helper::outputJsonResponse(['success' => 1, 'status' => $response]);
    }
    
    public function actionGetCategoryAndDisabilityList()
    {

        $isDomcile = Yii::$app->request->post('is_domcile');
        if ($isDomcile == \frontend\models\RegistrationForm::SELECT_TYPE_YES) {
            $categoryList = MstListType::getListTypeDropdownByParentId(MstListType::SOCIAL_CATEGORY);
            $disabilityList = MstListType::getListTypeDropdownByParentId(MstListType::DISABILITY);
            $exServiceList = MstListType::selectTypeList();
            $dffList = MstListType::selectTypeList();
        } else {
            $categoryList = MstListType::getListTypeDropdownByParentId(MstListType::SOCIAL_CATEGORY, ['id' => MstListType::UNRESERVED_GENERAL]);
            $disabilityList = MstListType::getListTypeDropdownByParentId(MstListType::DISABILITY, ['id' => MstListType::NOT_APPLICABLE]);
            $exServiceList = [ModelCache::IS_ACTIVE_NO => 'No'];
            $dffList = [ModelCache::IS_ACTIVE_NO => 'No'];
        }
        $categoryTemplate = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $categoryList]);
        $distabilityTemplate = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $disabilityList]);
        $exServiceTemplate = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $exServiceList]);
        $dffTemplate = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $dffList]);
        return Helper::outputJsonResponse([
            'success' => 1, 
            'categoryTemplate' => $categoryTemplate, 
            'distabilityTemplate' => $distabilityTemplate,
            'exServiceTemplate' => $exServiceTemplate,
            'dffTemplate' => $dffTemplate,
        ]);
    }
    
    /**
     * Remove Media
     * @return type
     * @throws \components\exceptions\AppException
     */
    public function actionRemoveMedia()
    {
        $guid = (string) \Yii::$app->request->post('guid');
        $applicantPostGuid =  \Yii::$app->request->post('applicantPostGuid');
        
        if (empty($guid) || empty($applicantPostGuid)) {
            throw new \components\exceptions\AppException("Oops! you trying to delete media doesn't exist.");
        }       

        try {
            $mediaModel = \common\models\Media::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if ($mediaModel === null) {
                throw new \components\exceptions\AppException("Invalid Request.");
            }
            if(!Media::validateDelete(Yii::$app->applicant->id, $mediaModel['cdn_path'])) {
                throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
            }

            $applicantPostModel = \common\models\ApplicantPost::findByGuid($applicantPostGuid, [
                'selectCols' => ['id'],
                'applicantId' => Yii::$app->applicant->id,
            ]);
            if (empty($applicantPostModel)) {
                throw new \components\exceptions\AppException("Invalid Request.");
            }

            $applicantPostId = $applicantPostModel['id'];

            if ($mediaModel['id'] > 0) {
                $status = 1;
                $applicantDocumentModel = [];
                if (!empty($applicantPostId)) {

                    $applicantDocumentModel = \common\models\ApplicantDocument::findByApplicantPostId($applicantPostId, [
                            'mediaId' => $mediaModel['id'],
                            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                    ]);
                    
                    if ($applicantDocumentModel !== null) {
                        $status = 2;
                        $applicantDocumentModel->delete();
                    }
                }
                if ($mediaModel !== null) {
                    $mediaModel->delete();
                }
                return \components\Helper::outputJsonResponse(['success' => $status]);
            }
            return \components\Helper::outputJsonResponse(['success' => 0]);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Error:" . $ex->getMessage());
        }
    }
    
    public function actionGetCalculateAge()
    {
        $post = Yii::$app->request->post();
        $age = 0;
        if (isset($post['dob']) && isset($post['date'])) {
            if (!Helper::validateDateFormat($post['date'])) {
                throw new \components\exceptions\AppException("Oops! invalid date format.");
            }
            $age = Helper::displayAge($post['dob'], $post['date']);
        }

        return Helper::outputJsonResponse([
                    'success' => 1,
                    'age' => $age,
        ]);
    }
    
    /**
     * Remove Media
     * @return type
     * @throws \components\exceptions\AppException
     */
    public function actionRemoveOtrDocument()
    {
        $guid = (string) \Yii::$app->request->post('guid');
        $logProfileGuid = (string) \Yii::$app->request->post('logProfileGuid');
        $id = (int) \Yii::$app->request->post('id');
        
        if (empty($guid) || empty($id)) {
            throw new \components\exceptions\AppException("Oops! you trying to delete media doesn't exist.");
        }

        try {
            if ($id > 0) {
                $mediaModel = \common\models\Media::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if ($mediaModel === null) {
                    throw new \components\exceptions\AppException("Oops! you trying to delete media doesn't exist.");
                }
                if(!Media::validateDelete(Yii::$app->applicant->id, $mediaModel['cdn_path'])) {
                    throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
                }
                if(!empty($logProfileGuid)){
                    $logProfileModel = \common\models\LogProfile::findByGuid($logProfileGuid);
                    if ($logProfileModel === null) {
                        throw new \components\exceptions\AppException("Oops! you trying to delete media doesn't exist.");
                    }
                    if($logProfileModel['applicant_id'] != Yii::$app->applicant->id) {
                        throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
                    }
                    $logProfileMediaModel = \common\models\LogProfileMedia::findByLogProfileId($logProfileModel['id'], [
                                'applicantId' => Yii::$app->applicant->id,
                                'mediaId' => $id,
                                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
                    ]);
                    if($logProfileMediaModel !== null) {
                        $logProfileMediaModel->delete();
                    }
                }
                
                $mediaModel->delete();
                return Helper::outputJsonResponse(['success' => 1]);
            }
            return Helper::outputJsonResponse(['success' => 0]);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Error:" . $ex->getMessage());
        }
    }

    public function actionDeleteQualification()
    {
        $id = (int) \Yii::$app->request->post('id');
        try {
            if (empty($id)) {
                throw new \components\exceptions\AppException("Oops! you trying to delete record doesn't exist.");
            }
            $applicantId = Yii::$app->applicant->id;
            $model = \common\models\ApplicantQualification::findById($id, [
                'joinWithApplicantPost' => 'innerJoin',
                'applicantId' => $applicantId,
                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);
            if ($model === NULL) {
                throw new \components\exceptions\AppException("Oops! Look like you trying to access record doesn't exist or deleted.");
            }            
            
            \common\models\ApplicantQualificationSubject::deleteAll('applicant_qualification_id=:applicantQualificationId', [':applicantQualificationId' => $model->id]);
            $model->delete();
            return Helper::outputJsonResponse(['success' => 1]);
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }

    public function actionDeleteEmployment()
    {
        $id = (int) \Yii::$app->request->post('id');
        try {
            if (empty($id)) {
                throw new \components\exceptions\AppException("Oops! you trying to delete record doesn't exist.");
            }
            $applicantId = Yii::$app->applicant->id;
            $model = \common\models\ApplicantEmployment::findById($id, [
                'joinWithApplicantPost' => 'innerJoin',
                'applicantId' => $applicantId,
                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);
            if ($model === NULL) {
                throw new \components\exceptions\AppException("Oops! Look like you trying to access record doesn't exist or deleted.");
            }
            $model->delete();
            return Helper::outputJsonResponse(['success' => 1]);
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }
}