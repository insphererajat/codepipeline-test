<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\base\BeanstalkJob as BaseBeanstalkJob;

/**
 * Description of BeanstalkJob
 *
 * @author Amit Handa
 */
class BeanstalkJob extends BaseBeanstalkJob
{

    const JOB_WAITING_TO_START = 0;
    const JOB_COMPLETED = 1;
    const JOB_ERROR = -1;
    const JOB_IN_PROCESS = 2;
    const PAYMENT_SCHEDULER = 'payment-scheduler';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public static function getBeanstalkTubeNames()
    {
        return [
            self::PAYMENT_SCHEDULER
        ];
    }

    private static function _createJob($tube, $data, $ttr = 90)
    {
        if ($tube == '') {
            return false;
        }

        //lets create a job in the database for backup
        $modelData = [
            'BeanstalkJob' => [
                'tube' => $tube,
                'data' => base64_encode(serialize($data))
            ]
        ];

        $beanstalkJobModel = new BeanstalkJob;
        $beanstalkJobModel->load($modelData);
        if ($beanstalkJobModel->save(FALSE)) {
            $data['beanstalkJobId'] = $beanstalkJobModel->id;
            $data['tubeName'] = $tube;
            $jobId = self::addToSqsQueue($data, $beanstalkJobModel->id);
            if (!empty($jobId)) {
                $beanstalkJobModel->job_id = $jobId;
                $beanstalkJobModel->save(FALSE);
            }

            return $beanstalkJobModel->id;
        }
        return FALSE;
    }

    private static function addToSqsQueue($data, $beanstalkJobId, $options = [])
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
                BeanstalkJob::appendJobLog(NULL, $beanstalkJobId, \Yii::$app->amazonSqs->error);
            }
        }

        return $messageId;
    }

    public static function getJobByBeanstalkID($beanstalkId)
    {
        return BeanstalkJob::find()
                        ->where('id=:id', [':id' => $beanstalkId])
                        ->asArray()
                        ->one();
    }

    public static function setJobInProcess($jobId, $beanstalkJobId = NULL)
    {
        $id = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? $beanstalkJobId : $jobId;
        $cols = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        BeanstalkJob::updateAll(['is_completed' => self::JOB_IN_PROCESS], $cols, [':jobId' => $id]);
    }

    public static function setJobAsCompleted($jobId, $beanstalkJobId = NULL)
    {
        $id = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? $beanstalkJobId : $jobId;
        $cols = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        if (empty($id)) {
            return false;
        }

        //Now delete completed job from db
        BeanstalkJob::deleteAll($cols, [':jobId' => $id]);
    }

    public static function setJobAsError($jobId, $beanstalkJobId = NULL)
    {
        $id = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? $beanstalkJobId : $jobId;
        $cols = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        BeanstalkJob::updateAll(['is_completed' => -1], $cols, [':jobId' => $id]);
    }

    public static function appendJobLog($jobId, $beanstalkJobId = NULL, $log = '')
    {
        if (empty($log)) {
            return;
        }
        $id = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? $beanstalkJobId : $jobId;
        $cols = (isset($beanstalkJobId) && !empty($beanstalkJobId)) ? 'id = :jobId' : 'job_id = :jobId';

        BeanstalkJob::updateAll(['logs' => new \yii\db\Expression("CONCAT_WS('\n', logs, " . \Yii::$app->db->quoteValue($log) . ")")], $cols, [':jobId' => $id]);
    }

    public static function createSchedulerJob($data)
    {
        return self::_createJob(self::PAYMENT_SCHEDULER, $data);
    }

}
