<?php

namespace console\controllers;

use common\models\Media;
use Yii;
use common\models\SqsJob;
use components\exceptions\AppException;

/**
 * Description of AmazonSqsController
 *
 * @author Pawan Kumar
 */
class QueueWorkerController extends \yii\console\Controller
{
    const JOB_ERROR = 0;
    const JOB_SUCCESS = 1;
    const MAX_COUNT_GC = 100; //To avoid memory leaks, the process will exist after MAX_COUNT_GC executions

    public function actionIndex()
    {
        $gcCounter = 0;
        while (TRUE) {

            //Retrive a message/job from queue. there is a long polling of 15 seconds enabled at queue level.
            $result = Yii::$app->amazonSqs->receiveMessages();

            //lets close the db connection after getting the messags
            Yii::$app->db->close();

            //increasing the number of times a process has executed.
            $gcCounter++;

            if (isset($result['Messages']) && !empty($result['Messages'])) {
                $messages = $result['Messages'];
                foreach ($messages as $message) {
                    if (!isset($message['MessageId']) || empty($message['Body'])) {
                        continue;
                    }

                    $messageId = $message['MessageId'];
                    $receiptHandle = $message['ReceiptHandle']; // Required to delete a message
                    $body = \yii\helpers\Json::decode($message['Body']);

                    if (!isset($body['tubeName']) || empty($body['tubeName'])) {
                        continue;
                    }

                    $tube = $body['tubeName'];
                    $sqsJobId = $body['sqsJobId'];

                    try {

                        //Check job is in waiting condition
                        $checkBeanstalkCount = 0;
                        while ($checkBeanstalkCount < 3) {
                            $beanstalkJobArr = SqsJob::getJobBySqsID($sqsJobId);
                            if (empty($beanstalkJobArr) && $sqsJobId > 0) {
                                sleep(2);
                                $checkBeanstalkCount++;
                            } else {
                                break;
                            }
                        }

                        //Delete from amazon if db record absent, or job has error, or job is completed
                        if (empty($beanstalkJobArr) || $beanstalkJobArr['is_completed'] == SqsJob::JOB_ERROR || $beanstalkJobArr['is_completed'] == SqsJob::JOB_COMPLETED) {
                            if (isset($receiptHandle) && !empty($receiptHandle)) {
                                Yii::$app->amazonSqs->deleteMessage($receiptHandle);
                            }
                            continue;
                        }
                        //Job is in process && created less then 15 mins ago then skip
                        if ($beanstalkJobArr['is_completed'] == SqsJob::JOB_IN_PROCESS &&  time() < ($beanstalkJobArr['created_on'] + 15 * 60)) {
                            continue;
                        }

                        //Update job to in process
                        SqsJob::setJobInProcess($messageId, $sqsJobId);

                        //Execute specific worker based on tube
                        switch ($tube) {
                            case SqsJob::PAYMENT_SCHEDULER:
                                self::processPaymentScheduler($body, $messageId);
                                break;
                            case SqsJob::TEST_JOB:
                                self::processTestJob($body, $messageId);
                                break;
                            default:
                                throw new AppException("Invalid SQS Queue Message Tube Name : " . $tube);
                        }
                    } catch (\Exception $ex) {
                        SqsJob::appendJobLog($messageId, $sqsJobId, $ex->getMessage());
                        SqsJob::setJobAsError($messageId, $sqsJobId);
                    }

                    //Delete a message, deletion is mandatory else message will be returned again & again
                    //NOTE: message will never be deleted by amazon                    
                    if (isset($receiptHandle) && !empty($receiptHandle)) {
                        Yii::$app->amazonSqs->deleteMessage($receiptHandle);
                    }

                    //lets close the db connection if any model opened any connection
                    Yii::$app->db->close();
                }
            } else if (Yii::$app->amazonSqs->error !== null) {
                throw new AppException("SQS Queue Error : " . Yii::$app->amazonSqs->error);
            }

            if ($gcCounter >= self::MAX_COUNT_GC) {
                break;
            }
        }
    }

    private static function processTestJob($data, $jobId)
    {
        Yii::error(json_encode($data)) ;

        if (empty($data) || empty($jobId)) {
            return self::JOB_ERROR;
        }

        $sqsJobId = isset($data['sqsJobId']) ? $data['sqsJobId'] : NULL;
        try {

            Yii::error("Job Tested Successfuly") ;

            SqsJob::setJobAsCompleted($jobId, $sqsJobId);
        } catch (\Exception $ex) {
            SqsJob::appendJobLog($jobId, $sqsJobId, $ex->getMessage());
            SqsJob::setJobAsError($jobId, $sqsJobId);
            return self::JOB_ERROR;
        }

        return self::JOB_SUCCESS;
    }
    

    private static function processPaymentScheduler($data, $jobId)
    {
        if (empty($data) || empty($jobId)) {
            return self::JOB_ERROR;
        }

        if (!isset($data['transactionData']) || empty($data['transactionData'])) {
            return self::JOB_ERROR;
        }
        
        if (!isset($data['paymentApi']) || empty($data['paymentApi'])) {
            return self::JOB_ERROR;
        }

        $sqsJobId = isset($data['sqsJobId']) ? $data['sqsJobId'] : NULL;
        try {

            $transactionModel = new \common\models\Transaction;
            if (!$transactionModel->processScheduler($data['transactionData'], $data['paymentApi'])) {
                SqsJob::setJobAsError($jobId, $sqsJobId);
                SqsJob::appendJobLog($jobId, $sqsJobId, $transactionModel->schedulerJobError);
                return self::JOB_ERROR;
            }
            SqsJob::setJobAsCompleted($jobId, $sqsJobId);
        } catch (\Exception $ex) {
            SqsJob::appendJobLog($jobId, $sqsJobId, $ex->getMessage());
            SqsJob::setJobAsError($jobId, $sqsJobId);
            return self::JOB_ERROR;
        }

        return self::JOB_SUCCESS;
    }
}
