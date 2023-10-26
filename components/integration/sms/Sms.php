<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace components\integration\sms;

use components\communication\Sms as CommunicationSms;

/**
 * Description of Sms
 *
 * @author Azam
 */
class Sms extends CommunicationSms
{    
    public function sendMessage($templateId, $params = [])
    {
        if (empty($templateId)) {
            return false;
        }
        $model = $this->__getTemplateById($templateId);
        $smsTemplate = $model['template'];
        $contentID = $model['template_id'];
        
        $message = $this->_buildTemplate([
            'content' => $smsTemplate,
            'otp' => (isset($params['otp']) && !empty($params['otp'])) ? $params['otp'] : '',
            'name' => (isset($params['name']) && !empty($params['name'])) ? $params['name'] : '',
            'gatewayId' => (isset($params['gatewayId']) && !empty($params['gatewayId'])) ? $params['gatewayId'] : '',
            'applicationNo' => (isset($params['applicationNo']) && !empty($params['applicationNo'])) ? $params['applicationNo'] : '',
            'post' => (isset($params['post']) && !empty($params['post'])) ? $params['post'] : '',
            'otpFor' => (isset($params['otpFor']) && !empty($params['otpFor'])) ? $params['otpFor'] : '',
            'projectName' => (isset($params['projectName']) && !empty($params['projectName'])) ? $params['projectName'] : '',
        ]);

        return $this->send($params['mobile'], $message, ['contentID' => $contentID, 'subject' => $model['title']]);
    }

}
