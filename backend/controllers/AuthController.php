<?php

namespace backend\controllers;

use Yii;
use backend\models\LoginForm;
use common\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use components\Security;
use common\models\LogUserActivity;

class AuthController extends AdminController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

        /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'common\components\captcha\CaptchaAction',
                'width' => 130,
                'fontFile' => Yii::getAlias('@webroot') . '/static/dist/deploy/times-new-roman.ttf',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if (Yii::$app->request->isPost) {
            $postParams = Yii::$app->request->post();
            $postParams['LoginForm']['username'] = Security::cryptoAesDecrypt($postParams['LoginForm']['username'], Yii::$app->params['hashKey']);
            $passwordHash = explode('||', $postParams['LoginForm']['password']);
            $time = time() - 5;

            $match = false;

            if(!empty($passwordHash[1]) && $time <= $passwordHash[1]) {
                $match = true;
            }

            $postParams['LoginForm']['password'] = Security::cryptoAesDecrypt($passwordHash[0], Yii::$app->params['hashKey']);
            

            if(!$match) {
                $postParams['LoginForm']['password'] = $postParams['LoginForm']['password'].'_test';
            }
            if ($model->load($postParams) && $model->login()) {
                return \components\Helper::outputJsonResponse(['success' => 1, 'redirectUrl' => Url::toRoute('home/index')]);
            }
            
            $errors = $model->errors;
            return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
        }

        return $this->render('login', [
                        'model' => $model,
            ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if (!\Yii::$app->user->isGuest) {
            $loginModel = new LogUserActivity();
            $loginModel->type = LogUserActivity::USER_LOGOUT;
            $loginModel->user_id = Yii::$app->user->id;

            $loginModel->status = 1;
            $loginModel->save();
        }
        Yii::$app->user->logout();

        return $this->redirect(Url::toRoute('auth/login'));
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionForgot()
    {
        // $model = new PasswordResetRequestForm();

        // if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
        //     $userModel = \common\models\User::findByUsername($model->username);
            
        //     if ($model->sendEmail()) {
        //         Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
        //         return $this->redirect(Url::toRoute('auth/login'));
        //     }
        //     else {
        //         Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
        //     }
        // }

        // return $this->render('requestPasswordResetToken', [
        //             'model' => $model,
        // ]);
    }
    
    public function actionResetPassword($token)
    {
        // try {
        //     $model = new ResetPasswordForm($token, TRUE);
        // } 
        // catch (\Exception $e) {
        //     throw new \yii\web\BadRequestHttpException($e->getMessage());
        // }

        // if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {

        //     return $this->redirect(['login']);
        // }

        // return $this->render('resetPassword', [
        //     'model' => $model,
        // ]);
    }
    
    public function actionCron()
    {
        (new \common\models\Transaction)->createSchedulerJob(\common\models\Transaction::TYPE_RAZORPAY);
    }
}
