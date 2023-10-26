<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace components\integration\email;

use Yii;
use common\models\MstMessageTemplate;
use common\models\caching\ModelCache;
use components\communication\Email as CommunicationEmail;

/**
 * Description of Email
 *
 * @author Azam
 */
class Email extends CommunicationEmail
{
    
    public function sendEmail($templateId, $params = [])
    {
        $templateModel = MstMessageTemplate::findById($templateId);
        if ($templateModel === NULL) {
            return FALSE;
        }
        $templateParams = [
            'email' => isset($params['email']) ? $params['email'] : '',
            'password' => isset($params['password']) ? $params['password'] : '',
            'name' => isset($params['name']) ? $params['name'] : '',
            'applicationNo' => isset($params['applicationNo']) ? $params['applicationNo'] : '',
            'gatewayId' => isset($params['gatewayId']) ? $params['gatewayId'] : '',
            'amount' => isset($params['amount']) ? $params['amount'] : '',
            'post' => isset($params['post']) ? $params['post'] : '',
            'date' => isset($params['date']) ? $params['date'] : '',
            'otp' => isset($params['otp']) ? $params['otp'] : '',
            'staticPath' => $this->assetsPath,
            'siteUrl' => \yii\helpers\Url::base(TRUE),
            'content' => $templateModel['template']
        ];

        $emailParams = [
            'toEmail' => isset($params['email']) ? $params['email'] : 'abc@test.com',
            'template' => $this->_buildTemplate($templateParams),
            'subject' => $templateModel['title'],
        ];

        return $this->__sendEmail($emailParams);
    }
}

