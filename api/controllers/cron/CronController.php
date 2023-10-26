<?php

namespace api\controllers\cron;

use Yii;
use yii\rest\Controller;
use common\filters\auth\AwsLambdaAuth;
/**
 * Description of CronController
 *
 * @author Pawan Kumar
 */
class CronController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => AwsLambdaAuth::className()
        ];

        return $behaviors;
    }
}
