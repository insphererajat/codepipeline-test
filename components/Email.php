<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace components;

use Yii;
use common\models\MstMessageTemplate;
use common\models\caching\ModelCache;

/**
 * Description of Email
 *
 * @author Amit Handa
 */
class Email extends communication\Email
{

    public function sendWelcomeApplicantEmail($applicantId, $password)
    {
        $applicantModel = $this->findApplicantModel($applicantId);
        if ($applicantModel === FALSE) {
            return FALSE;
        }

        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_LOGIN_DETAIL
        ]);

        if ($templateModel === NULL) {
            return FALSE;
        }
        $templateParams = [
            'name' => $applicantModel->name,
            'email' => $applicantModel->email,
            'password' => $password,
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toName' => $applicantModel->name,
            'toEmail' => $applicantModel->email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'],
            //'bccEmail' => $templateModel['bcc_email']
        ];

        return $this->__sendEmail($emailParams);
    }
    
    public function forgotPasswordEmail($email, $applicantName, $otp)
    {
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_FORGOT_PASSWORD
        ]);

        if ($templateModel === NULL) {
            return FALSE;
        }

        $templateParams = [
            'name' => $applicantName,
            'otp' => $otp,
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toName' => $applicantName,
            'toEmail' => $email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'], //'OTP for verification'
        ];

        return $this->__sendEmail($emailParams);
    }

    private function findApplicantModel($applicantId)
    {
        if (empty($applicantId)) {
            return FALSE;
        }

        $applicantModel = \common\models\Applicant::findById($applicantId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($applicantModel == NULL) {
            return FALSE;
        }

        return $applicantModel;
    }
    
    public function sendRegistrationOtpEmail($email, $applicantName, $otp)
    {
        
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_OTP
        ]);

        if ($templateModel === NULL) {
            return FALSE;
        }

        $templateParams = [
            'name' => $applicantName,
            'otp' => $otp,
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toName' => $applicantName,
            'toEmail' => $email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'], //'OTP for verification'
            //'bccEmail' => $templateModel['bcc_email']
        ];
        
        $this->__sendEmail($emailParams);
    }
    
    public function sendPaymentSuccessEmail($applicantPostId)
    {
        $applicantPostModel = \common\models\ApplicantPost::findById($applicantPostId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($applicantPostModel === FALSE) {
            return FALSE;
        }

        if ($applicantPostModel->application_status == \common\models\ApplicantPost::APPLICATION_STATUS_PENDING || $applicantPostModel->payment_status == \common\models\ApplicantPost::STATUS_UNPAID) {
            //return false;
        }

        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_PAYMENT_SUCCESS
        ]);

        if (empty($templateModel)) {
            return FALSE;
        }

        $transParams = [
            'joinWithApplicantFee' => 'innerJoin',
            'module' => \common\models\ApplicantFee::MODULE_APPLICATION,
            'applicantPostId' => $applicantPostId,
            'orderBy' => [
                'transaction.is_consumed' => SORT_DESC
            ],
            'limit' => 1
        ];
        $transactionModel = \common\models\Transaction::findByApplicantId($applicantPostModel->applicant->id, $transParams);
        if (empty($transactionModel)) {
            return FALSE;
        }
        
        $templateParams = [
            'name' => $applicantPostModel->applicant->name,
            'applicationNo' => $applicantPostModel->application_no,
            'transactionId' => $transactionModel['transaction_id'],
            'amount' => $transactionModel['amount'],
            'date' => date('d-m-Y H:i:s', $transactionModel['created_on']),
            'staticPath' => $this->assetsPath,
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toName' => $applicantPostModel->applicant->name,
            'toEmail' => $applicantPostModel->applicant->email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'],
            //'bccEmail' => $templateModel['bcc_email']
        ];

        return $this->__sendEmail($emailParams);
    }
    
    public function sendCancelPostOtpEmail($email, $applicantName, $otp, $service)
    {
        
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => $service
        ]);

        if ($templateModel === NULL) {
            return FALSE;
        }

        $templateParams = [
            'name' => $applicantName,
            'otp' => $otp,
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toName' => $applicantName,
            'toEmail' => $email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'], //'OTP for verification'
            //'bccEmail' => $templateModel['bcc_email']
        ];
        
        $this->__sendEmail($emailParams);
    }
    
    public function sendChangeRequestOtpEmail($email, $applicantName, $otp)
    {
        
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_CHANGE_REQUEST_OTP
        ]);

        if ($templateModel === NULL) {
            return FALSE;
        }

        $templateParams = [
            'name' => $applicantName,
            'otp' => $otp,
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toName' => $applicantName,
            'toEmail' => $email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'], //'OTP for verification'
            //'bccEmail' => $templateModel['bcc_email']
        ];
        
        $this->__sendEmail($emailParams);
    }
    
    /**
     * send mail in case of registration
     * @param type $userModel
     * @return boolean
     */
    public function sendStudyCenterRegistrationSuccessEmail($userModel)
    {        
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_LOGIN_DETAIL
        ]);

        if (empty($templateModel)) {
            return FALSE;
        }
        
        $templateParams = [
            'name' => $userModel->firstname,
            'username' => $userModel->username,
            'password' => $userModel->password,
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];
        $emailParams = [
            'fromName' => 'NIOS TEAM',
            'fromEmail' => 'admin@nios.ac.in',
            'bccEmail' => 'mr.nbhatia@gmail.com',
            'toName' => $userModel->firstname,
            'toEmail' => $userModel->email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title']
        ];
        return $this->__sendEmail($emailParams);
    }
    
    public function resetPasswordEmail($applicantId)
    {
        $applicantModel = $this->findApplicantModel($applicantId);
        if ($applicantModel === FALSE) {
            return FALSE;
        }

        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_EMAIL, [
                    'service' => MstMessageTemplate::SERVICE_FORGOT_PASSWORD
        ]);
        if ($templateModel === NULL) {
            return FALSE;
        }

        $resetPasswordLink = Yii::$app->urlManager->createAbsoluteUrl(['/auth/reset-password', 'token' => $applicantModel->password_reset_token]);
        $templateParams = [
            'name' => $applicantModel->name,
            'staticPath' =>  $this->assetsPath,
            'content' => $templateModel['template'],
            'resetLink' => $resetPasswordLink
        ];

        $emailParams = [
            'toName' => $applicantModel->name,
            'toEmail' => $applicantModel->email,
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'],
            //'bccEmail' => $templateModel['bcc_email']
        ];

        return $this->__sendEmail($emailParams);
    }
    
    public function notificationEmail($email, $template = '', $name = 'Developer', $subject = 'Notification', $params = [])
    {
        $emailParams = [
            'toName' => $name,
            'toEmail' => $email,
            'subject' => $subject, //'OTP for verification'
            'template' => $template
        ];

        return $this->__sendEmail(\yii\helpers\ArrayHelper::merge($emailParams, $params));
    }
}

