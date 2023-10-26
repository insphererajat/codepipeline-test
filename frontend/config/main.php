<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],    
    'on beforeRequest' => function ($event) {
        /*if (YII_ENV == "prod" && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'http') {
            $redirectUrl = preg_replace('/^http:/i', 'https:', Yii::$app->request->getAbsoluteUrl());
            \Yii::$app->response->redirect($redirectUrl, 301)->send();
            \Yii::$app->end();
        }*/
        if (YII_ENV == "prod" && substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.') {
            $url = \Yii::$app->request->getAbsoluteUrl();
            $url = $_SERVER['REQUEST_SCHEME'] . '://www.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = str_replace('http:', 'https:', $url);
            \Yii::$app->getResponse()->redirect($url);
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'applicant' => [
            'class' => 'common\components\Applicant',
            'identityClass' => 'common\models\Applicant',
            'enableAutoLogin' => false,
            'authTimeout' => 900,
            'loginUrl' => ['home/index'],
        ],
        'errorHandler' => [
            'errorAction' => 'home/error',
        ],
        'defaultRoute' => 'home/index',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'home/index',
                'login' => 'auth/login'
            ]
        ],              
        'i18n' => [
            'translations' => [
                'admin*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'admin' => 'base-messages.php'
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
