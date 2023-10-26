<?php

namespace backend\controllers;

use Yii;

/**
 * ErrorController
 */
class ErrorController extends \yii\web\Controller
{
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

}