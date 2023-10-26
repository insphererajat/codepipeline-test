<?php

namespace components\integration\amazon;
 
use Yii;
use yii\base\Component;
use Aws\Sqs\SqsClient;

/**
 * Description of AmazonSqs
 *
 * @author Pawan Kumar
 */
class AmazonSqs extends Component
{
    private $amazons3Key;
    private $amazons3Secret;
    private $sqsQueueUrl;
    private $sqsClient;
    private $region;
    public $result;
    public $error;
    
    public function init()
    {
        $this->amazons3Key = Yii::$app->params['amazons3.sqs.key'];
        $this->amazons3Secret = Yii::$app->params['amazons3.sqs.secret'];
        $this->sqsQueueUrl = Yii::$app->params['amazons3.sqs.url'];
        $this->region = Yii::$app->params['amazons3.sqs.region'];

        //Initilize sqs client
        $this->sqsClient = new SqsClient(array(
            'credentials' => array(
                'key' => $this->amazons3Key,
                'secret' => $this->amazons3Secret
            ),
            'region' => $this->region,
            'version' => 'latest'
        ));

        parent::init();
    }

    public function setSqsQueueUrl($queueUrl)
    {
        $this->sqsQueueUrl = $queueUrl;
    }
    
    // http://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html
    // http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue
    public function createQueue($queueName = NULL)
    {
        if(empty($queueName)) {
            throw new \Exception('Not a valid Queue Name');
        }
        
        try {
            $result = $this->sqsClient->createQueue(array(
                'QueueName' => $queueName,
                'Attributes' => array(
                    'VisibilityTimeout' => 2 * 60, //2 min
                    'DelaySeconds' => 0,    //10 SEC (MAX 15 MIN)
                    'MaximumMessageSize' => 262144, //256 KB (MAX=256KB)
                    'ReceiveMessageWaitTimeSeconds' => 10
                ),
            ));
            
            $resultArr = $result->toArray();
            $this->result = $resultArr;
            return $this->result;
        } 
        catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }

    public function getQueueUrl($queueName = NULL)
    {
        if(empty($queueName)) {
            throw new \Exception('Not a valid Queue Name');
        }
        
        //Get Queue Url
        try {
            $result = $this->sqsClient->getQueueUrl([
                'QueueName' => $queueName
            ]);
            $resultArr = $result->toArray();
            $this->result = $resultArr['QueueUrl'];
            return $this->result;
        } 
        catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }
    
    /**
     *  http://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
     *  http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
     * 
     *  Example : 'MessageAttributes' => [
                "Title" => [
                    'DataType' => "String",
                    'StringValue' => "The Hitchhiker's Guide to the Galaxy"
                ],
                "Author" => [
                    'DataType' => "String",
                    'StringValue' => "Douglas Adams."
                ]
            ],
            'MessageBody' => "Information about current NY Times fiction bestseller for week of 12/11/2016.",
     * @param array $msgBody
     * @param array $msgAttribs
     * @param String $queueUrl
     */
    public function sendMessage($msgBody, $msgAttribs = [], $delaySeconds = NULL)
    {
        $this->error = null;
        $queueUrl = $this->sqsQueueUrl;
        
        if(empty($queueUrl)) {
            throw new \components\exceptions\AppException("SQS Queue URL is required to send a SQS message");
        }
        
        if (is_array($msgBody)) {
            $msgBody = \yii\helpers\Json::encode($msgBody);
        }
        
        $params = [
            'MessageBody' => $msgBody,
            'QueueUrl' => $queueUrl
        ];
        if($delaySeconds !== NULL && $delaySeconds > 0) {
            $params['DelaySeconds'] = $delaySeconds;
        }
        if (!empty($msgAttribs)) {
            $params['MessageAttributes'] = $msgAttribs;
        }
        
        try {
            $result = $this->sqsClient->sendMessage($params);
            $this->result = $result->toArray();
            return $this->result;
        } 
        catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }
    
    //http://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ReceiveMessage.html
    //VisibilityTimeout = The duration (in seconds) that the received messages are hidden from subsequent retrieve requests after being retrieved by a ReceiveMessage request.
    //	Default Visibility Timeout	2 minutes
    public function receiveMessages($messagesToRetrieve = 10)
    {
        $this->error = null;
        
        $queueUrl = $this->sqsQueueUrl;
        if(empty($queueUrl)) {
            throw new \components\exceptions\AppException("SQS Queue URL is required to receive a SQS message");
        }
        
        $params = [
            'MessageAttributeNames' => ['All'],
            'MaxNumberOfMessages' => $messagesToRetrieve,
            'QueueUrl' => $queueUrl
        ];
        
        try {
            $result = $this->sqsClient->receiveMessage($params);
            $this->result = $result->toArray();
            return $this->result;
        } 
        catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }
    
    public function deleteMessage($receiptHandle)
    {
        $this->error = null;
        $queueUrl = $this->sqsQueueUrl;
        if(empty($queueUrl)) {
            throw new \components\exceptions\AppException("SQS Queue URL is required to delete a SQS message");
        }
        
        $params = [
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $receiptHandle
        ];
        
        try {
            $result = $this->sqsClient->deleteMessage($params);
            $this->result = empty($result) ? TRUE : FALSE;
            return $this->result;
        } 
        catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
    }
}
