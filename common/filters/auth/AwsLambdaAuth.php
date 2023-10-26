<?php

namespace common\filters\auth;

/**
 * Description of AwsLambdaAuth
 *
 * @author Amit Handa
 */
class AwsLambdaAuth extends \yii\filters\auth\HttpBearerAuth
{
    /**
     * @inheritdoc
     */
    
    public function authenticate($user, $request, $response)
    {
        $lambdaAuthCode = $request->getHeaders()->get('X-AwsLambda-AuthCode');
        if ($lambdaAuthCode !== \Yii::$app->params['AwsLambdaAuthCode']) {
            $this->handleFailure($response);
        }
        return true;
    }
}
