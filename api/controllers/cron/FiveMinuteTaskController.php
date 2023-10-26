<?php

namespace api\controllers\cron;


use Yii;
use common\models\SqsJob;
use common\models\Transaction;

/**
 * Description of FiveMinuteTaskController
 *
 * @author Pawan Kumar
 */
class FiveMinuteTaskController extends CronController
{

    public function actionIndex()
    {
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_HDFC);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_BOB);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_CSC);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_AXIS);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_RAZORPAY);
        //(new \common\models\ApplicantExam)->sendInterviewNotification();
        //(new \common\models\ApplicantExam)->sendInterviewNotification();
        //(new \common\models\ApplicantExam)->sendAdmitCardNotification();

        return "SUCCESS";
    }
}
