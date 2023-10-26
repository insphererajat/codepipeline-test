<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', 
        require __DIR__ . '/../../common/config/params-local.php', 
        require __DIR__ . '/params.php', 
        require __DIR__ . '/params-local.php'
);

$domain = (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';

return [
    'id' => '_ukssc-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'on beforeRequest' => function ($event) { 
        if (YII_ENV == "prod" && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'http') {
            $redirectUrl = preg_replace('/^http:/i', 'https:', Yii::$app->request->getAbsoluteUrl());
            \Yii::$app->response->redirect($redirectUrl, 301)->send();
            \Yii::$app->end();
        }
    },
    'components' => [
        'applicationUrl' => 'http://' . $domain,
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => FALSE,
            'loginUrl' => null
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];
