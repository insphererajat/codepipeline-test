<?php

namespace backend\controllers; 

use Yii;
use yii\web\Controller;

/**
 * Description of AdminController
 *
 * @author Azam
 */
class AdminController extends Controller
{   

    public $onlyAdmin = true;
    public $userId;
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!\common\models\User::checkSessionHijackingPreventions(\common\models\User::BACKEND_LOGIN_KEY, \common\models\User::BACKEND_FIXATION_COOKIE, \common\models\User::BACKEND_SESSION_VALUE)) {
                                Yii::$app->user->logout();
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        if(!Yii::$app->user->isGuest) {
            $this->userId = Yii::$app->user->id;
        }
        return parent::beforeAction($action);
    }

    
    public function setSuccessMessage($message)
    {
        Yii::$app->session->setFlash('success', $message);
    }

    public function setErrorMessage($message)
    {
        Yii::$app->session->setFlash('error', $message);
    }
}