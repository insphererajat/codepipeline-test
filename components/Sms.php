<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace components;

/**
 * Description of Sms
 *
 * @author Amit Handa
 */
class Sms extends communication\Sms
{

    public function sendOtp($mobile, $params = [])
    {
        $smsTemplate = $this->__otpTemplate(\common\models\MstMessageTemplate::SERVICE_OTP);
        $templateId = $this->__otpTemplateId(\common\models\MstMessageTemplate::SERVICE_OTP);
        
        $message = $this->_buildTemplate([
            'content' => $smsTemplate,
            'otp' => !empty($params['otp']) ? $params['otp'] : '',
            'name' => isset($params['name']) ? $params['name'] : ''
        ]);

        return $this->send($mobile, $message, ['contentID' => $templateId]);
    }
    
    public function sendMesssage($mobile, $message = '', $params = [])
    {
        if(isset($params['service'])) {
            $params['contentID'] = $this->__otpTemplateId($params['service']);
        }
        return $this->send($mobile, $message, $params);
    }

}
