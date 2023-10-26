<?php

namespace components;

/**
 * Description of Maintenance
 *
 * @author Pawan Kumar
 */
class Maintenance extends \yii\base\Component
{

    public $enable = false;
    public $message = NULL;

    public function init()
    {
        $this->makeSystemShutdown();
        parent::init();
    }

    public function makeSystemShutdown()
    {
        $systemArr = \common\models\System::checkSystemSutdown();
        if (!empty($systemArr) && isset($systemArr['status']) &&  $systemArr['status']) {
            $this->enable = true;
            $this->message = isset($systemArr['message']) ? $systemArr['message'] : "Sorry, we are down for scheduled maintenance right now. But soon we will up.";
        }
    }

}
