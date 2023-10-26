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
class MinuteTaskController extends CronController
{

    public function actionIndex()
    {
        
        return "SUCCESS";
    }
}
