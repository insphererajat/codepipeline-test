<?php

namespace api\controllers\cron;

use Yii;

/**
 * Description of DailyTaskController
 *
 * @author Amit Handa
 */
class DailyTaskController extends CronController
{
    public function actionIndex()
    {
        $timeMonthAgo = time() - (60*60*24*30);
        Yii::$app->db->createCommand('Delete from log where log_time < '. $timeMonthAgo)->execute();  
        return "SUCCESS";
    }
}
