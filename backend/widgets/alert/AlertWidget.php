<?php

namespace backend\widgets\alert;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Description of AlertWidget
 *
 * @author Amit Handa
 */
class AlertWidget extends Widget
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
