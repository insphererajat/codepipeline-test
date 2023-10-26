<?php
$isProd =  (isset(\Yii::$app->params['applicationEnv']) && \Yii::$app->params['applicationEnv'] === 'PROD')  ? true : false;
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Kolkata',
    'components' => [
        'response' => [
            'on beforeSend' => function($event) {
                $event->sender->headers->add('X-Frame-Options', 'SAMEORIGIN'); //SAMEORIGIN
                $event->sender->headers->add('X-XSS-Protection', '1; mode=block');
                $event->sender->headers->add('Referrer-Policy', 'no-referrer-when-downgrade');
                $event->sender->headers->add('X-Content-Type-Options', 'nosniff');
                $event->sender->headers->add('Strict-Transport-Security', 'max-age=31536000');
                // $event->sender->headers->add('Content-Security-Policy', "default-src 'none';");
                // $event->sender->headers->add('Content-Security-Policy', "script-src 'self' https://d2qnd23etpajj1.cloudfront.net;");
                // $event->sender->headers->add('Content-Security-Policy', "style-src 'self' https://d2qnd23etpajj1.cloudfront.net https://fonts.googleapis.com https://fonts.gstatic.com;");
                // $event->sender->headers->add('Content-Security-Policy', "img-src 'self' https://d2qnd23etpajj1.cloudfront.net;");
                // $event->sender->headers->add('Content-Security-Policy', "font-src 'self' https://fonts.googleapis.com https://d2qnd23etpajj1.cloudfront.net https://fonts.gstatic.com;");
                // $event->sender->headers->add('X-Permitted-Cross-Domain-Policies', "none");
                $event->sender->headers->add('Referrer-Policy', "no-referrer");
                //$event->sender->headers->add('Permissions-Policy', "fullscreen 'none'");
                //$event->sender->headers->add('Clear-Site-Data', "cache");
                $event->sender->headers->add('Permissions-Policy', "fullscreen=*,microphone=(),sync-xhr=*");
                
            },
        ],
        'on afterRequest' => function ($event) {
            //avoid click jacking
            if (\Yii::$app->id !== 'app-console') {
                if (isset(\Yii::$app->response->headers)) {
                    $headers = \Yii::$app->response->headers;
                    $headers->add('X-FRAME-OPTIONS', 'SAMEORIGIN');
                }
            }
        },
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php'
                    ],
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 3600 * 24 * 30,
            'useCookies' => true,
            'cookieParams' => [
                'lifetime' => 3600 * 24 * 30,
                'httpOnly' => true,
                'secure' => (YII_ENV == "prod") ? true : false,
                'sameSite' => 'Strict'
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User'
        ],
        'application' => [
            'class' => 'common\components\ApplicationComponent',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                //to be defined in each area (front or back)
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => []
                ],
                'yii\web\YiiAsset' => [
                    'js' => []
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],
                'yii\validators\ValidationAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\widgets\PjaxAsset' => [
                    'js' => []
                ],
                'yii\grid\GridViewAsset' => [
                    'js' => []
                ],
                'yii\captcha\CaptchaAsset' => [
                    'js' => [],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'db' => [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error'],
                    'logVars' => ['_GET', '_POST'],
                ],
            ],
        ],
        'email' => [
            'class' => 'components\Email',
        ],
        'sms' => [
            'class' => 'components\Sms',
        ],
        'criteria' => [
            'class' => 'components\Criteria',
        ],
        'amazons3' => [
            'class' => 'components\AmazonS3'
        ],
        'amazonSqs' => [
            'class' => 'common\components\amazon\AmazonSqs'
        ],
        'mobileDetect' => [
            'class' => '\ezze\yii2\mobiledetect\MobileDetect'
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LcT7jUaAAAAAEezhGxynVQDA3th99jZVPCI8H5Q',
            'secret' => '6LcT7jUaAAAAAAltkLhwAqmP3wX1FbiDsq8IJMoq',
        ],
    ],
];
