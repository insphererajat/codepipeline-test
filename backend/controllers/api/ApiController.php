<?php

namespace backend\controllers\api;

use Yii;
/**
 * Description of ApiController
 *
 * @author Azam
 */
class ApiController extends \backend\controllers\AdminController
{
    /**
    * Access Control
    * 
    * @return type
    */
    public function behaviors()
    {
        $controllerBehaviors = [
            'ajax' => [
                'class' => \common\components\filters\AjaxFilter::className()
            ]
        ];
                    
        return \yii\helpers\ArrayHelper::merge($controllerBehaviors, parent::behaviors());
    }
}
