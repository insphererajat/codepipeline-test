<?php

namespace frontend\controllers\api;

use Yii;
/**
 * Description of ApiController
 *
 * @author Azam
 */
class ApiController extends \yii\web\Controller
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
                'class' => \components\filters\AjaxFilter::className()
            ]
        ];
                    
        return \yii\helpers\ArrayHelper::merge($controllerBehaviors, parent::behaviors());
    }
}
