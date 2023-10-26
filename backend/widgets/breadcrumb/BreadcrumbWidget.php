<?php

namespace backend\widgets\breadcrumb;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Description of BreadcrumbWidget
 *
 * @author Amit Handa
 */
class BreadcrumbWidget extends Widget
{

    private $menu;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('index');
    }

}
