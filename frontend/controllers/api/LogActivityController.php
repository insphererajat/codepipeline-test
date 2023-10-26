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

/**
 * Description of LogActivity
 *
 * @author Amit Handa
 */
class LogActivityController extends ApiController
{
    public $attempts = 5; // allowed 5 attempts
    public $counter;

    public function actionSendOtp()
    {
        $model = new \frontend\models\VerifyOTPForm;
        $post = Yii::$app->request->post();

        if (empty($post['applicantId']) || empty($post['type'])) {
            throw new \components\exceptions\AppException('Oops! Invalid request.');
        }
        
        if ($post['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_EMAIL_OTP && empty($post['email'])) {
            throw new \components\exceptions\AppException('Oops! Email can not be empty.');
        }
        else if ($post['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_MOBILE_OTP && empty($post['mobile'])) {
            throw new \components\exceptions\AppException('Oops! Mobile can not be empty.');
        }
        
        
        $applicant = \common\models\Applicant::findById($post['applicantId']);
        if ($applicant === null) {
            throw new \components\exceptions\AppException('Oops! Applicant not found.');
        }
        $params = [];
        $params['name'] = $applicant['name'];
        if ($post['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_EMAIL_OTP) {
            $emailCount = \common\models\Applicant::findByEmail($post['email'], ['countOnly' => true]);
            if ($emailCount > 0) {
                throw new \components\exceptions\AppException('Oops! Email already exist in database.');
            }
            
            $params['email'] = $post['email'];
            $params['mobile'] = $applicant['mobile'];
            $params['type'] = LogOtp::CHANGE_EMAIL_OTP;
        }
        if ($post['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_MOBILE_OTP) {
            $mobileCount = \common\models\Applicant::findByMobileNumber($post['mobile'], ['countOnly' => true]);
            if ($mobileCount > 0) {
                throw new \components\exceptions\AppException('Oops! Mobile already exist in database.');
            }
            
            $params['email'] = $applicant['email'];
            $params['mobile'] = $post['mobile'];
            $params['type'] = LogOtp::CHANGE_MOBILE_OTP;
        }

        try {
            $sendOtp = $this->generateAndSendOtp($params);
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("While sending email or otp raise error.");
        }
        
        $model->type = $post['type'];
        $model->applicant_id = $post['applicantId'];
        $model->mobileOtpId = $sendOtp['mobileOtpId'];
        $model->email = $params['email'];
        $model->mobile = $params['mobile'];
        return \components\Helper::outputJsonResponse([
                    'status' => 1,
                    'template' => $this->renderAjax('_otp_modal', ['model' => $model]),
                    'message' => 'Otp Send successfully to Email: ' . $params['email'] . ' and Mobile: ' . $params['mobile']
        ]);
    }

    private function generateAndSendOtp($params = [])
    {
        // Send OTP in mobile
        if (!empty($params['mobile']) && !empty($params['email'])) {
            $otpLogModel = LogOtp::generateOtp($params['type'], $params['mobile']);
            //return true;
            \Yii::$app->sms->sendOtp($params['mobile'], ['otp' => $otpLogModel->otp]);
            Yii::$app->email->sendChangeRequestOtpEmail($params['email'], $params['name'], $otpLogModel->otp);
        }

        return [
            'status' => 1,
            'mobileOtpId' => isset($otpLogModel->id) ? $otpLogModel->id : 0
        ];
    }

    public function actionValidateOtp()
    {
        $model = new \frontend\models\VerifyOTPForm();
        $model->scenario = \frontend\models\VerifyOTPForm::SCENARIO_VALIDATE_CHANGE_REQUST_OTP;
        $success = 1;
        $message = '';
        if (\Yii::$app->request->isPost) {
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if($this->bruteForceCheck()) {
                    throw new \components\exceptions\AppException('Oops! You have exceeded the limit for this action.');
                }
                $post = \Yii::$app->request->post();
                $model->load($post);
                $model->type = LogOtp::CHANGE_MOBILE_OTP;
                if ($post['VerifyOTPForm']['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_EMAIL_OTP) {
                    $model->type = LogOtp::CHANGE_EMAIL_OTP;
                }

                if (empty($model->applicant_id)) {
                    throw new \components\exceptions\AppException('Oops! Applicant not found.');
                }

                if ($model->validate()) {
                    $applicant = \common\models\Applicant::findById($model->applicant_id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                    if ($applicant === null) {
                        throw new \components\exceptions\AppException('Oops! Applicant not found.');
                    }

                    $logApplicantData = [];
                    $logApplicantData['applicant_id'] = $applicant->id;
                    $logApplicantData['ip_address'] = \components\Helper::GetUserIp();
                    $logApplicantData['device_type'] = \common\models\LogApplicant::getDeviceType();
                    $logApplicantData['useragent'] = Yii::$app->mobileDetect->getUserAgent();
                    $logApplicantData['created_by'] = $applicant->id;
                    if ($post['VerifyOTPForm']['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_EMAIL_OTP) {
                        $logApplicantData['old_value'] = $applicant->email;
                        $logApplicantData['new_value'] = $model->email;
                        $logApplicantData['type'] = \common\models\LogApplicant::TYPE_EMAIL;
                        $applicant->email = $model->email;
                        $applicant->save(true, ['email']);
                        $message = 'Email Changed successfully.';
                    } else if ($post['VerifyOTPForm']['type'] == \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_MOBILE_OTP) {
                        $logApplicantData['old_value'] = (string) $applicant->mobile;
                        $logApplicantData['new_value'] = (string) $model->mobile;
                        $logApplicantData['type'] = \common\models\LogApplicant::TYPE_MOBILE;
                        $applicant->mobile = $model->mobile;
                        $applicant->save(true, ['mobile']);
                        $message = 'Mobile Changed successfully.';
                    }
                    $logApplicant = new \common\models\LogApplicant();
                    $logApplicant->isNewRecord = TRUE;
                    $logApplicant->setAttributes($logApplicantData);
                    if (!$logApplicant->save()) {
                        throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($logApplicant->errors));
                    }
                    Yii::$app->session->remove('bruteForce');
                } else {
                    $this->counter = Yii::$app->session->get('bruteForce') + 1;
            Yii::$app->session->set('bruteForce',$this->counter);
                    $errors = $model->errors;
                    return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
                }
                $transaction->commit();
            } catch (\Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        }

        return \components\Helper::outputJsonResponse(['success' => 1, 'message' => $message]);
    }

    private function bruteForceCheck()
    {           
        return Yii::$app->session->get('bruteForce') >= $this->attempts;
    }

}