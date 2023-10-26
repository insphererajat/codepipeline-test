<?php

namespace console\controllers;

use Yii;
use common\models\Transaction;

/**
 * Description of TransactionController
 *
 * @author Nitish
 */
class TransactionController extends \yii\console\Controller
{
    public function actionIndex()
    {
        //$isconsumed = Transaction::processAfterTransaction(3, 13);
        //(new Transaction)->createSchedulerJob(Transaction::TYPE_HDFC);
        (new Transaction)->createSchedulerJob(Transaction::TYPE_RAZORPAY);
    }
}
