<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'on beforeRequest' => function ($event) {
        if (YII_ENV == "prod" && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'http') {
            $redirectUrl = preg_replace('/^http:/i', 'https:', Yii::$app->request->getAbsoluteUrl());
            \Yii::$app->response->redirect($redirectUrl, 301)->send();
            \Yii::$app->end();
        }
    },
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => (YII_ENV == "prod") ? true : false,
            ],
        ],
        'user' => [
            'class' => 'common\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 900,
            'loginUrl' => ['auth/login'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
        'urlManager' => [
            'rules' => [
                '' => 'home/index',
                'login' => 'auth/login'
            ],
        ],
    ],
    'params' => $params,
];
