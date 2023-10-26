<?php

namespace components\communication;

use Yii;
use common\models\MstConfiguration;
use common\models\MstMessageTemplate;
use common\models\caching\ModelCache;
use components\Helper;

/**
 * Description of Sms
 *
 * @author Amit Handa
 * @email <insphere.amit@gmail.com>
 */
class Sms extends \yii\base\Component
{
    private $config = [];
    public $response = [];
    
    public function init()
    {
        $configuration = MstConfiguration::findByType(MstConfiguration::TYPE_SMS, ['isActive' => ModelCache::IS_ACTIVE_YES]);
        if (empty($configuration)) {
            throw new \yii\base\InvalidConfigException("Invalid SMS configuration.");
        }
        
        $this->config['type'] = Helper::decryptString($configuration['config_val1']);
        if (empty($this->config['type'])) {
            throw new \yii\base\InvalidConfigException("Invalid SMS type configuration.");
        }
        
        if ($this->config['type'] == 'EZYSMS') {
            $this->config['senderId'] = Helper::decryptString($configuration['config_val2']);
            $this->config['username'] = Helper::decryptString($configuration['config_val3']);
            $this->config['password'] = Helper::decryptString($configuration['config_val4']);
            $this->config['url'] = Helper::decryptString($configuration['config_val5']);
        }
        else if ($this->config['type'] == 'VIDEOCON') {
            $this->config['senderId'] = Helper::decryptString($configuration['config_val2']);
            $this->config['username'] = Helper::decryptString($configuration['config_val3']);
            $this->config['authkey'] = Helper::decryptString($configuration['config_val4']);
            $this->config['url'] = Helper::decryptString($configuration['config_val5']);
        }
        else if ($this->config['type'] == 'BULKSMS') {
            $this->config['username'] = Helper::decryptString($configuration['config_val3']);
            $this->config['password'] = Helper::decryptString($configuration['config_val4']);
            $this->config['senderId'] = Helper::decryptString($configuration['config_val2']);
            $this->config['url'] = Helper::decryptString($configuration['config_val5']);
            $this->config['entityId'] = Helper::decryptString($configuration['config_val6']);
            $this->config['contentID'] = 0;
        }

        return parent::init();
    }

    public function send($mobile, $message, $params = [])
    {
        try {
            if ($this->config['type'] == 'EZYSMS') {
                return $this->_sendEzysms($mobile, $message);
            }
            if ($this->config['type'] == 'VIDEOCON') {
                return $this->_sendVideoconSms($mobile, $message);
            }
            if ($this->config['type'] == 'BULKSMS') {
                $this->config['contentID'] = isset($params['contentID']) ? $params['contentID']: 0;
                return $this->_sendBulkSms($mobile, $message, $params);
            }
             
        }
        catch (\Exception $ex) {
            Yii::error('SMS ERROR :' . $ex->getMessage());
        }
        return false;
    }

    private function _sendVideoconSms($mobile, $message)
    {
        $url = $this->config['url'] . "/vapi/pushsms?user={$this->config['username']}&authkey={$this->config['authkey']}&sender={$this->config['senderId']}&mobile=91$mobile&text=$message";
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => false
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);

        if ($output['STATUS'] == 'ERROR') {
            return false;
        }
        return true;
    }

    private function _sendEzysms($mobile, $message)
    {
        $message = str_replace(' ', '%20', trim($message));
        $mobiles = is_array($mobile) ? implode(',', $mobile) : $mobile;
        
        $url = "http://push.ezysms.in/api.php?username={$this->config['username']}&password={$this->config['password']}&sender={$this->config['senderId']}&sendto=$mobiles&message=$message";
        $response = \components\Helper::httpGet($url);
        if (!empty($response)) {
            $responseArr = explode('=', $response);
            return (!empty($responseArr[1])) ? true : false;
        }
        return false;
    }

    public function _sendBulkSms($mobile, $message, $params = [])
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        
        $url = $this->config['url'] . "?username={$this->config['username']}&password={$this->config['password']}&messageType=text&mobile=91$mobile&message=" . urlencode($message) . "&senderId={$this->config['senderId']}&ContentID={$this->config['contentID']}&EntityID={$this->config['entityId']}";
        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $messageDataArr = explode('#', $response);
        if ($messageDataArr[0] !== '0') {
            return FALSE;
        }
        if (isset($params['subject']) && !empty($params['subject'])) {
            $logMessageModel = new \common\models\LogMessage();
            $data = [
                'type' => \common\models\LogMessage::TYPE_SMS,
                'subject' => isset($params['subject']) ? $params['subject'] : NULL,
                'message' => $message,
                'template_id' => $this->config['contentID'],
                'to_applicant_id' => isset($params['applicantId']) ? $params['applicantId'] : '',
                'reference_id' => isset($params['applicantPostId']) ? $params['applicantPostId'] : '',
                'detail' => \yii\helpers\Json::encode($response),
                'sent_to' => (string)$mobile,
                'created_by' => NULL
            ];
            $logMessageModel->createLogMessage($data);
        }
        return TRUE;
    }

    public function __otpTemplate($service)
    {
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_SMS, [
                    'service' => $service
        ]);
        
        if($templateModel == NULL) {
            return FALSE;
        }
        
        return $templateModel['template'];
    }
    
    public function _buildTemplate($params = [])
    {
        $pattern = '{{%s}}';
        foreach ($params as $key => $val) {
            $varMap[sprintf($pattern, $key)] = $val;
        }
        return strtr($params['content'], $varMap);
    }
    
    public function __otpTemplateId($service)
    {
        $templateModel = MstMessageTemplate::findByType(MstMessageTemplate::TEMPLATE_SMS, [
                    'service' => $service
        ]);
        
        if($templateModel == NULL) {
            return FALSE;
        }
        
        return $templateModel['template_id'];
    }

}