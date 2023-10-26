<?php

namespace frontend\widgets\alert;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Description of AlertWidget
 *
 * @author Amit Handa<insphere.amit@gmail.com>
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
