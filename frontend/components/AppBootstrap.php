<?php

namespace frontend\components;

use Yii;

/**
 * Description of AppBootstrap
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class AppBootstrap implements \yii\base\BootstrapInterface
{

    public function bootstrap($app)
    {
        $pslManager = new \Pdp\PublicSuffixListManager;
        $parser = new \Pdp\Parser($pslManager->getList());
        $hostInfo = $parser->parseHost($app->request->getServerName());
        $domain = $hostInfo->registerableDomain;
        $url = $app->request->absoluteUrl;
        $parsedUrl = $parser->parseUrl($url);
        $pathInfoArr = explode('/', $parsedUrl->path);
        
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '25M');

        /**
         * making sure there is no trailing slash at the end
         */
        $pathInfo = $app->request->pathInfo;
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            $app->response->redirect('/' . rtrim($pathInfo, '/'), 301)->send();
            exit;
        }
        try {

            if ($pathInfoArr[1] == 'admin') {

                //die('Sorry! This site is currently under maintenance. We will be back shortly.');

                $app->homeUrl = '/admin/home/index';

                //set domain for identity cookie
                $app->set('user', [
                    'class' => 'common\components\User',
                    'identityClass' => 'common\models\User',
                    'enableAutoLogin' => false,
                    'enableSession' => true,
                    'loginUrl' => ['/admin/auth/login'],
                    'identityCookie' => [
                        'name' => '_identity-schooladmin',
                    ],
                ]);
                $routes = [
                    "admin/<type:\w+>/library" => "admin/library/index",
                    "admin/<type:\w+>/library/index" => "admin/library/index",
                    "admin/<type:\w+>/library/create" => "admin/library/create",
                    "admin/<type:\w+>/library/update" => "admin/library/update",
                    "admin/<type:\w+>/library/delete" => "admin/library/delete",
                    "admin/<type:\w+>/library/import" => "admin/library/import",
                    "admin/<type:\w+>/library/download-format" => "admin/library/download-format",
                ];
                $app->getUrlManager()->addRules($routes, true);
            }
            else {

                $app->set('view', [
                    'class' => 'frontend\components\AppView'
                ]);

                $routes = [
                    '' => 'home/index',
                    'auth' => 'auth/login',
                    "page/<slug:\w+[-\w+]+>.html" => "page/index",
                    "<slug:\w+[-\w+]+>.html" => "page/index",
                    "<id:\d+>/<slug:\w+[-\w+]+>.html" => "page/index",
                ];

                $app->getUrlManager()->addRules($routes, false);
            }
        }
        catch (\Exception $ex) {

            $this->setErrorLayout($app);
            throw new \components\exceptions\AppException("Oops! Looks like there's an issue with school configurations.Please see issue found: " . $ex->getMessage());
        }
    }

    public function setErrorLayout($app)
    {
        $app->viewPath = \Yii::getAlias('@frontend/views/error/default');
        $app->layoutPath = $app->viewPath . '/layouts';
    }

    private function __setSchoolConfig($app, $school)
    {
        // set School frontend layout and themes
        $baseViewPath = \Yii::getAlias('@frontend/views/base/default');
        $schoolDefault = Yii::getAlias('@frontend/views/' . $school['folder_name'] . '/default');

        //set viewPath based on school
        $app->viewPath = is_dir($schoolDefault . '/layouts/') ? $schoolDefault : $baseViewPath;

        // Set Default Configuration for school
        if (file_exists($schoolDefault . '/config.php')) {
            $configArr = require ($schoolDefault . '/config.php');
            if (is_array($configArr) && !empty($configArr)) {
                foreach ($configArr as $configKey => $configVal) {
                    $app->params[$configKey] = $configVal;
                }
            }
        }

        // School specific config
        $schoolParams = Yii::getAlias('@common/config/' . $school['folder_name'] . '/params.php');
        $defaultParams = Yii::getAlias('@common/config/base/params.php');
        $callableParams = file_exists($schoolParams) ? $schoolParams : $defaultParams;
        $app->params[$school['folder_name']] = require_once $callableParams;
    }

}
