<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\base\SqsJob as BaseSqsJob;

/**
 * Description of SqsJob
 *
 * @author Amit Handa
 */
class SqsJob extends BaseSqsJob
{

    const JOB_WAITING_TO_START = 0;
    const JOB_COMPLETED = 1;
    const JOB_ERROR = -1;
    const JOB_IN_PROCESS = 2;

    const PAYMENT_SCHEDULER = 'payment-scheduler';
    const TEST_JOB = 'test-job';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    public static function getSqsTubeNames()
    {
        return [
            self::PAYMENT_SCHEDULER,
            self::TEST_JOB
        ];
    }

    private static function _createJob($tube, $data, $ttr = 90)
    {
        if ($tube == '') {
            return false;
        }

        //lets create a job in the database for backup
        $modelData = [
            'SqsJob' => [
                'tube' => $tube,
                'data' => base64_encode(serialize($data)),
            ]
        ];

        $sqsJobModel = new SqsJob;
        $sqsJobModel->load($modelData);
        if ($sqsJobModel->save(FALSE)) {
            $data['sqsJobId'] = $sqsJobModel->id;
            $data['tubeName'] = $tube;
            $jobId = self::addToSqsQueue($data, $sqsJobModel->id);
            if (!empty($jobId)) {
                $sqsJobModel->job_id = $jobId;
                $sqsJobModel->save(FALSE);
            }

            return $sqsJobModel->id;
        }
        return FALSE;
    }

    private static function addToSqsQueue($data, $sqsJobId, $options = [])
    {
        $messageId = NULL;
        $delaySeconds = NULL;
        if (isset($options['delaySeconds']) && $options['delaySeconds'] > 0) {
            $delaySeconds = $options['delaySeconds'];
        }

        //max 3 tries 
        for ($i = 0; $i <= 2; $i++) {
            $results = \Yii::$app->amazonSqs->sendMessage($data, [], $delaySeconds);
            if (isset($results['MessageId']) && !empty($results['MessageId'])) {
                $messageId = $results['MessageId'];
                break;
            }
            else if (\Yii::$app->amazonSqs->error !== null) {
                SqsJob::appendJobLog(NULL, $sqsJobId, \Yii::$app->amazonSqs->error);
            }
        }

        return $messageId;
    }

    public static function getJobBySqsID($sqsId)
    {
        return SqsJob::find()
                        ->where('id=:id', [':id' => $sqsId])
                        ->asArray()
                        ->one();
    }

    public static function setJobInProcess($jobId, $sqsJobId = NULL)
    {
        $id = (isset($sqsJobId) && !empty($sqsJobId)) ? $sqsJobId : $jobId;
        $cols = (isset($sqsJobId) && !empty($sqsJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        SqsJob::updateAll(['is_completed' => self::JOB_IN_PROCESS], $cols, [':jobId' => $id]);
    }

    public static function setJobAsCompleted($jobId, $sqsJobId = NULL)
    {
        $id = (isset($sqsJobId) && !empty($sqsJobId)) ? $sqsJobId : $jobId;
        $cols = (isset($sqsJobId) && !empty($sqsJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        if (empty($id)) {
            return false;
        }

        //Now delete completed job from db
        SqsJob::deleteAll($cols, [':jobId' => $id]);
    }

    public static function setJobAsError($jobId, $sqsJobId = NULL)
    {
        $id = (isset($sqsJobId) && !empty($sqsJobId)) ? $sqsJobId : $jobId;
        $cols = (isset($sqsJobId) && !empty($sqsJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        SqsJob::updateAll(['is_completed' => -1], $cols, [':jobId' => $id]);
    }

    public static function appendJobLog($jobId, $sqsJobId = NULL, $log = '')
    {
        if (empty($log)) {
            return;
        }
        $id = (isset($sqsJobId) && !empty($sqsJobId)) ? $sqsJobId : $jobId;
        $cols = (isset($sqsJobId) && !empty($sqsJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        SqsJob::updateAll(['logs' => new \yii\db\Expression("CONCAT_WS('\n', logs, " . \Yii::$app->db->quoteValue($log) . ")")], $cols, [':jobId' => $id]);
    }
    
    public static function createPaymentSchedulerJob($data)
    {
        return self::_createJob(self::PAYMENT_SCHEDULER, $data);
    }
    public static function createTestJob($data)
    {
        return self::_createJob(self::TEST_JOB, $data);
    }

}
