<?php

namespace api\controllers\cron;

use common\models\SqsJob;
use Yii;
use common\models\Transaction;

/**
 * Description of HourlyTaskController
 *
 * @author Pawan Kumar
 */
class HourlyTaskController extends CronController
{

    public function actionIndex()
    {

        return "SUCCESS";
    }
}
