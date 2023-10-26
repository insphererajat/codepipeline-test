<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers\api;

use Yii;
use common\models\ApplicantPost;
use common\models\caching\ModelCache;
use components\exceptions\AppException;
use common\models\LogOtp;
use common\models\Applicant;

/**
 * Description of ApplicantPost
 *
 * @author Amit Handa
 */
class ApplicantPostController extends ApiController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'user' => 'applicant',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!Applicant::checkSessionHijackingPreventions(Applicant::FRONTEND_LOGIN_KEY, Applicant::FRONTEND_FIXATION_COOKIE, Applicant::FRONTEND_SESSION_VALUE)) {
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

    private $_user = [];
    
    public function beforeAction($action) {

        if (Yii::$app->applicant->isGuest) {
            throw new AppException(Yii::t('app', 'invalid.request'));
        }
        
        $this->_user = Yii::$app->applicant->identity;
        return parent::beforeAction($action);
    }

    public function actionSendOtp()
    {
        $post = \Yii::$app->request->post();
        if (!isset($post['type']) || !\yii\helpers\ArrayHelper::isIn($post['type'], [LogOtp::CANCEL_POST_OTP, LogOtp::ESERVICE_POST_OTP])) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.attributes', ['title' => 'Type']));
        }
        $model = new \frontend\models\VerifyOTPForm;

        try {
            $sendOtp = $this->generateAndSendOtp($post['type']);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("While sending email or otp raise error.");
        }

        return \components\Helper::outputJsonResponse([
                    'status' => 1,
                    'template' => $this->renderAjax('@frontend/views/api/registration/_otp_modal', ['model' => $model, 'mobileOtpId' => $sendOtp['mobileOtpId'], 'url' => \yii\helpers\Url::toRoute(['/api/applicant-post/validate-otp']), 'otpType' => $post['type'], 'scenario' => $post['type']]),
                    'message' => 'Otp Send successfully to Email: ' . $this->_user->email . ' and Mobile: ' . $this->_user->mobile
        ]);
    }

    private function generateAndSendOtp($type)
    {
        // Send OTP in mobile
        if (!empty($this->_user->mobile) && !empty($this->_user->email)) {
            $otpLogModel = LogOtp::generateOtp($type, $this->_user->mobile);
            //return true;
            \Yii::$app->sms->sendOtp($this->_user->mobile, ['otp' => $otpLogModel->otp]);
            
            if ($type == LogOtp::ESERVICE_POST_OTP) {
                Yii::$app->email->sendCancelPostOtpEmail($this->_user->email, $this->_user->name, $otpLogModel->otp, \common\models\MstMessageTemplate::SERVICE_ESERVICE_OTP);
            }
            else {
                Yii::$app->email->sendCancelPostOtpEmail($this->_user->email, $this->_user->name, $otpLogModel->otp, \common\models\MstMessageTemplate::SERVICE_CANCEL_POST_OTP);
            }
        }

        return [
            'status' => 1,
            'mobileOtpId' => isset($otpLogModel->id) ? $otpLogModel->id : 0
        ];
    }

    public function actionValidateOtp()
    {
        $post = \Yii::$app->request->post();
        if(!isset($post['scenario']) || !\yii\helpers\ArrayHelper::isIn($post['scenario'], [\frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_ESERVICE_POST_OTP, \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_CANCEL_POST_OTP])) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.attributes', ['title' => 'scenario']));
        }
        $model = new \frontend\models\VerifyOTPForm();
        $model->setScenario(\frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_CANCEL_POST_OTP);
        if ($post['scenario'] == \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_ESERVICE_POST_OTP) {
            $model->setScenario(\frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_ESERVICE_POST_OTP);
        }

        $success = 1;
        if (\Yii::$app->request->isPost) {

            $post = \Yii::$app->request->post();
            $model->mobileOtpId = $post['mobileOtpId'];

            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                $url = \yii\helpers\Url::toRoute(['/api/applicant-post/cancel-post']);
                if ($post['scenario'] == \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_ESERVICE_POST_OTP) {
                    $url = \yii\helpers\Url::toRoute(['/api/applicant-post/eservice']);
                }
                return \components\Helper::outputJsonResponse(['success' => 1, 'url' => $url]);
            }
            $errors = $model->errors;
            return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
        }
        return \components\Helper::outputJsonResponse(['success' => $success]);
    }

    public function actionReSendOtp()
    {
        $post = \Yii::$app->request->post();
        if(!isset($post['type']) || !\yii\helpers\ArrayHelper::isIn($post['type'], [LogOtp::CANCEL_POST_OTP, LogOtp::ESERVICE_POST_OTP])) {
            throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound'));
        }
        $sendOtp = $this->generateAndSendOtp($post['type']);
        if ($sendOtp['status']) {
            return \components\Helper::outputJsonResponse([
                        'status' => 1,
                        'mobileOtpId' => $sendOtp['mobileOtpId'],
                        'message' => 'Otp Send successfully !!'
            ]);
        }

        return \components\Helper::outputJsonResponse([
                    'status' => 0,
                    'message' => 'Oops!! can\'t sent OTP right now. Please try after sometimes'
        ]);
    }
    
    public function actionCancelPost()
    {
        $post = Yii::$app->request->post();
        if (!isset($post['id']) || !isset($post['guid'])) {
            throw new AppException(Yii::t('app', 'invalid.request'));
        }

        $model = ApplicantPost::findById($post['id'], [
                    'applicantId' => Yii::$app->applicant->identity->id,
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);
        if ($model === null) {
            throw new AppException(Yii::t('app', 'model.notfound'));
        }
        if (ApplicantPost::checkStatusForCancel($post['id'], ['applicantId' => Yii::$app->applicant->identity->id]) !== ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
            throw new AppException(Yii::t('app', 'forbidden.cancel.post'));
        }

        $model->application_status = ApplicantPost::APPLICATION_STATUS_CANCELED;
        $model->modified_on = time();
        $model->save(TRUE, ['application_status', 'modified_on']);

        return \components\Helper::outputJsonResponse(['success' => 1, 'url' => \yii\helpers\Url::toRoute(['/applicant/post'])]);
    }
    
    public function actionValidateEservice()
    {
        $post = Yii::$app->request->post();
        if (!isset($post['id']) || !isset($post['guid'])) {
            throw new AppException(Yii::t('app', 'invalid.request'));
        }

        $model = ApplicantPost::findById($post['id'], [
                    'applicantId' => Yii::$app->applicant->identity->id,
                    'applcationStatus' => ApplicantPost::APPLICATION_STATUS_SUBMITTED
        ]);

        if ($model === null) {
            throw new AppException(Yii::t('app', 'model.notfound'));
        }
        if (!ApplicantPost::checkStatusForEservice($model['id']) && $model['application_status'] !== ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
            throw new AppException(Yii::t('app', 'eservice.closed'));
        }

        $classifiedModel = \common\models\MstClassified::findById($model['classified_id']);
        if ($classifiedModel === NULL) {
            throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound', ['title' => 'Advertisement']));
        }

        $applicantPost = ApplicantPost::findByApplicantId(Yii::$app->applicant->identity->id, [
                    'parentApplicantPostId' => $model['id'],
                    'applicationStatus' => ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE
        ]);

        $status = 0;
        if ($applicantPost !== null) {
            $status = 1;
        }

        return \components\Helper::outputJsonResponse(['status' => $status]);
    }
    
    public function actionDiscardEservice()
    {
        $post = Yii::$app->request->post();
        if (!isset($post['id']) || !isset($post['guid'])) {
            throw new AppException(Yii::t('app', 'invalid.request'));
        }

        $model = ApplicantPost::findById($post['id'], [
                    'applicantId' => Yii::$app->applicant->identity->id,
                    'applcationStatus' => ApplicantPost::APPLICATION_STATUS_SUBMITTED
        ]);

        if ($model === null) {
            throw new AppException(Yii::t('app', 'model.notfound'));
        }
        if (!ApplicantPost::checkStatusForEservice($model['id']) && $model['application_status'] !== ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
            throw new AppException(Yii::t('app', 'eservice.closed'));
        }

        $classifiedModel = \common\models\MstClassified::findById($model['classified_id']);
        if ($classifiedModel === NULL) {
            throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound', ['title' => 'Advertisement']));
        }

        $applicantPost = ApplicantPost::findByApplicantId(Yii::$app->applicant->identity->id, [
                    'parentApplicantPostId' => $model['id'],
                    'applicationStatus' => ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE
        ]);
        
        if ($applicantPost !== null) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                \common\models\ApplicantCriteria::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantCriteriaDetail::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantDocument::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantDetail::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantAddress::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantEmployment::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);

                $applicantQualification = \common\models\ApplicantQualification::findByApplicantPostId($applicantPost['id'], ['selectCols' => ['id'], 'resultCount' => ModelCache::RETURN_ALL]);
                if ($applicantQualification !== null) {
                    $qualificaitonIds = \yii\helpers\ArrayHelper::map($applicantQualification, 'id', 'id');
                    \common\models\ApplicantQualificationSubject::deleteAll(['in', 'applicant_qualification_id', array_values($qualificaitonIds)]);
                }
                \common\models\ApplicantQualification::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                
                $applicantFee = \common\models\ApplicantFee::findByApplicantPostId($applicantPost['id'], ['selectCols' => ['id'], 'resultCount' => ModelCache::RETURN_ALL]);
                if ($applicantFee !== null) {
                    $feeIds = \yii\helpers\ArrayHelper::map($applicantFee, 'id', 'id');
                    \common\models\Transaction::deleteAll(['in', 'applicant_fee_id', array_values($feeIds)]);
                }
                \common\models\ApplicantFee::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantPostExamCentre::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantPostDetail::deleteAll('applicant_post_id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                \common\models\ApplicantPost::deleteAll('id =:applicantPostId', [':applicantPostId' => $applicantPost['id']]);
                $transaction->commit();
            }
            catch (\Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        }

        return \components\Helper::outputJsonResponse(['status' => 1]);
    }


    public function actionEservice()
    {
        $post = Yii::$app->request->post();
        if (!isset($post['id']) || !isset($post['guid'])) {
            throw new AppException(Yii::t('app', 'invalid.request'));
        }

        $model = ApplicantPost::findById($post['id'], [
                    'applicantId' => Yii::$app->applicant->identity->id,
                    'applcationStatus' => ApplicantPost::APPLICATION_STATUS_SUBMITTED
        ]);

        if ($model === null) {
            throw new AppException(Yii::t('app', 'model.notfound'));
        }
        if (!ApplicantPost::checkStatusForEservice($model['id']) && $model['application_status'] !== ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
            throw new AppException(Yii::t('app', 'eservice.closed'));
        }
        
        $classifiedModel = \common\models\MstClassified::findById($model['classified_id']);
        if ($classifiedModel === NULL) {
            throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound', ['title' => 'Advertisement']));
        }

        $applicantPost = ApplicantPost::findByApplicantId(Yii::$app->applicant->identity->id, [
                    'parentApplicantPostId' => $model['id'],
                    'applicationStatus' => ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE
        ]);
        
        if ($applicantPost === NULL) {

            $transaction = \Yii::$app->db->beginTransaction();
            try {

                $applicantPost = new ApplicantPost;
                $applicantPost->isNewRecord = true;
                $applicantPost->setAttributes($model);
                $applicantPost->guid = NULL;
                $applicantPost->application_no = NULL;
                $applicantPost->eservice_tabs = ApplicantPost::ESERVICE_TAB_INITAL_VALUE;
                $applicantPost->parent_applicant_post_id = $model['id'];
                $applicantPost->application_status = ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE;

                if (!$applicantPost->save()) {
                    throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($applicantPost->errors));
                }

                $applicantPostDetails = \common\models\ApplicantPostDetail::findByApplicantPostId($applicantPost->id, [
                            'resultCount' => ModelCache::RETURN_ALL
                ]);

                if ($applicantPostDetails !== NULL) {
                    foreach ($applicantPostDetails as $applicantPostDetail) {
                        $applicantPostDetailModel = new \common\models\ApplicantPostDetail();
                        $applicantPostDetailModel->isNewRecord = true;
                        $applicantPostDetailModel->setAttributes($applicantPostDetail);
                        $applicantPostDetailModel->guid = NULL;
                        if (!$applicantPostDetailModel->save()) {
                            throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($applicantPostDetailModel->errors));
                        }
                    }
                }

                $clone = new \frontend\components\CloneProfileComponent();
                $clone->applicantId = $applicantPost->applicant_id;
                $clone->applicantPostId = $applicantPost->id;
                $clone->profile();
                
                $applicantCriterias = \common\models\ApplicantCriteria::findByApplicantPostId($model['id'], [
                            'resultCount' => ModelCache::RETURN_ALL
                ]);

                if ($applicantCriterias !== NULL) {
                    foreach ($applicantCriterias as $applicantCriteria) {
                        $applicantCriteriaModel = new \common\models\ApplicantCriteria();
                        $applicantCriteriaModel->isNewRecord = true;
                        $applicantCriteriaModel->setAttributes($applicantCriteria);
                        $applicantCriteriaModel->applicant_post_id = $applicantPost->id;
                        if (!$applicantCriteriaModel->save()) {
                            throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($applicantCriteriaModel->errors));
                        }
                        
                        $applicantCriteriaDetails = \common\models\ApplicantCriteriaDetail::findByApplicantCriteriaId($applicantCriteria['id'], [
                                    'resultCount' => ModelCache::RETURN_ALL
                        ]);
                        
                        if ($applicantCriteriaDetails !== NULL) {
                            foreach ($applicantCriteriaDetails as $applicantCriteriaDetail) {
                                $applicantCriteriaDetailModel = new \common\models\ApplicantCriteriaDetail();
                                $applicantCriteriaDetailModel->isNewRecord = true;
                                $applicantCriteriaDetailModel->setAttributes($applicantCriteriaDetail);
                                $applicantCriteriaDetailModel->applicant_post_id = $applicantPost->id;
                                $applicantCriteriaDetailModel->applicant_criteria_id = $applicantCriteriaModel->id;
                                if (!$applicantCriteriaDetailModel->save()) {
                                    throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($applicantCriteriaDetailModel->errors));
                                }
                            }
                        }
                    }
                }
                
                $applicantPostExamCentres = \common\models\ApplicantPostExamCentre::findByApplicantPostId($model['id'], [
                            'resultCount' => ModelCache::RETURN_ALL
                ]);

                if ($applicantPostExamCentres !== NULL) {
                    foreach ($applicantPostExamCentres as $applicantPostExamCentre) {
                        $applicantPostExamCentreModel = new \common\models\ApplicantPostExamCentre();
                        $applicantPostExamCentreModel->isNewRecord = true;
                        $applicantPostExamCentreModel->setAttributes($applicantPostExamCentre);
                        $applicantPostExamCentreModel->applicant_post_id = $applicantPost->id;
                        if (!$applicantPostExamCentreModel->save()) {
                            throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($applicantPostExamCentreModel->errors));
                        }
                    }
                }

                $guid = $applicantPost->guid;

                $transaction->commit();
            } catch (\Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        } else {
            $guid = $applicantPost['guid'];
        }
        
        $mstPost = \common\models\MstPost::findById($model['post_id']);
        if ($mstPost === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound', ['title' => 'Master Post']));
        }
        return \components\Helper::outputJsonResponse(['success' => 1, 'url' => \yii\helpers\Url::toRoute(['registration/personal-details', 'guid' => $classifiedModel['guid'], 'eservice' => $guid, 'post_id' => $model['post_id'], 'epguid' => $mstPost['guid']])]);
    }

}
