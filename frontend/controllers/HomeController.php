<?php

namespace frontend\controllers;

use common\models\SqsJob;
use Yii;

/**
 * HomeController
 */
class HomeController extends \yii\web\Controller
{

    public $applicantId;
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {

        if (!Yii::$app->applicant->isGuest) {
            $this->applicantId = Yii::$app->applicant->id;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        
        return $this->render('index');
    }
    
    public function actionPosts($guid)
    {
        return $this->goHome();
        //return $this->render('index');
    }
    
    public function actionContactUs()
    {
        return $this->render('contact-us');
    }
    public function actionAboutUs()
    {
        return $this->render('about-us');
    }
    public function actionAdvertisement()
    {
        return $this->render('advertisement');
    }

}
