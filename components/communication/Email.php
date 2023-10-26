<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace components\communication;
use Yii;
use common\models\MstConfiguration;
use common\models\caching\ModelCache;
use components\Helper;

/**
 * Description of Email
 *
 * @author Pawan Kumar
 * @email <pkb.pawan@gmail.com>
 */
class Email extends yii\base\Component
{
    public $fromName = null;
    public $fromEmail = null;
    public $assetsPath = null;
    
    private $_config = [];

    public function init()
    {
        $type = MstConfiguration::TYPE_EMAIL_AES;
        $this->_config = MstConfiguration::findByType($type, ['isActive' => ModelCache::IS_ACTIVE_YES]);
        if (empty($this->_config)) {
            throw new \components\exceptions\AppException("Invalid Email configuration.");
        }
        $this->assetsPath = Helper::decryptString($this->_config['config_val4']);
        $this->fromName = Helper::decryptString($this->_config['config_val5']);
        $this->fromEmail = Helper::decryptString($this->_config['config_val6']);

        \Yii::$app->set('mailer', [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            //'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => Helper::decryptString($this->_config['config_val1']), // amazon smtp host 
                'username' => Helper::decryptString($this->_config['config_val2']), // ses user username
                'password' => Helper::decryptString($this->_config['config_val3']), // ses user password
                'port' => Helper::decryptString($this->_config['config_val7']),
                'encryption' => 'tls',
            //'useFileTransport' => true,
            ]
        ]);

        return parent::init();
    }

    public function _buildTemplate($params = [])
    {
        $pattern = '{{%s}}';
        foreach ($params as $key => $val) {
            $varMap[sprintf($pattern, $key)] = $val;
        }
        return strtr($params['content'], $varMap);
    }

    public function __sendEmail($emailParams)
    {
        //echo '<pre>';print_r($this);die;
        try {
            $emailParams['fromName'] = $this->fromName;
            $emailParams['fromEmail'] = $this->fromEmail;
            return $this->_sendSesEmail($emailParams);
        }
        catch (\Exception $ex) {
            //\Yii::error('Email Client Error : ' . $ex->getMessage());
            return FALSE;
        }

        return TRUE;
    }

    private function _sendSesEmail($params)
    {
        try {
            $emailAQ = Yii::$app->mailer->compose()
                    ->setFrom([$params['fromEmail'] => $params['fromName']])
                    ->setTo($params['toEmail'])
                    ->setHtmlBody($params['template'])
                    ->setSubject($params['subject']);

            if (isset($params['cc']) && !empty($params['cc'])) {
                $emailAQ->setCc($params['cc']);
            }
            if (isset($params['bccEmail']) && !empty($params['bccEmail'])) {
                $emailAQ->setBcc($params['bccEmail']);
            }

            if ($emailAQ->send()) {
                $logMessageModel = new \common\models\LogMessage();
                $data = [
                    'type' => \common\models\LogMessage::TYPE_EMAIL,
                    'subject' => isset($params['subject']) ? $params['subject'] : 'Notification',
                    'message' => $params['template'],
                    'to_applicant_id' => isset($params['applicantId']) ? $params['applicantId'] : NULL,
                    'reference_id' => isset($params['applicantPostId']) ? $params['applicantPostId'] : NULL,
                    'detail' => 'Sent',
                    'sent_to' => $params['toEmail'],
                    'created_by' => NULL
                ];
                $logMessageModel->createLogMessage($data);
                return true;
            }
            return false;
        }
        catch (\Exception $ex) {
           
            Yii::error($ex->getMessage());
        }
        return false;
    }

}
