<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use components\Security;
use common\models\Applicant;
use common\models\Transaction;
use yii\helpers\ArrayHelper;
use components\Helper;
use yii\helpers\Url;
use common\models\LogUserActivity;

/**
 * Description of AuthController
 * 
 * @author Amit Handa
 */
class AuthController extends base\AppController
{
    public $attempts = 5; // allowed 5 attempts
    public $counter;
    private $redirectUrl = "";
    private $_applicantModel = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \components\behaviors\AccessControl::className(),
                'only' => ['logout', 'change-password'],
                'rules' => [
                    [
                        'actions' => ['logout', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!Applicant::checkSessionHijackingPreventions(Applicant::FRONTEND_LOGIN_KEY, Applicant::FRONTEND_FIXATION_COOKIE, Applicant::FRONTEND_SESSION_VALUE)) {
                                Yii::$app->applicant->logout();
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
                'height' => 50,
                'fontFile' => Yii::getAlias('@webroot') . '/static/dist/deploy/times-new-roman.ttf',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin($guid = null)
    {
        if (!Yii::$app->applicant->isGuest) {
            $this->_loginRedirect(Yii::$app->applicant->identity->id);
            return $this->redirect(Url::toRoute(Helper::stepsUrl($this->redirectUrl, \Yii::$app->request->queryParams)));
        }

        $getParams = Yii::$app->request->get();
        $model = new \frontend\models\LoginForm;
        if (Yii::$app->request->isAjax) {
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
                    
                    if (\Yii::$app->session->has('firstLogin')) {
                        \Yii::$app->session->remove('firstLogin');
                    }
                    $this->_loginRedirect($model->_user->id);
                    return \components\Helper::outputJsonResponse(['success' => 1, 'redirectUrl' => Url::toRoute(Helper::stepsUrl($this->redirectUrl, \Yii::$app->request->queryParams))]);
                }

                $errors = $model->errors;
                return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
            }
        }

        return $this->render('auth', [
                    'model' => $model
        ]);
    }
    
    private function _loginRedirect($applicantId)
    {        
        $this->redirectUrl = \common\models\Applicant::getRedirectionBasedOnFormStep($applicantId);
    }

    public function actionValidate()
    {
        $model = new \frontend\models\LoginForm;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        if (!\Yii::$app->applicant->isGuest) {
            $loginModel = new LogUserActivity();
            $loginModel->type = LogUserActivity::USER_LOGOUT;
            $loginModel->applicant_id = Yii::$app->applicant->id;

            $loginModel->status = 1;
            $loginModel->save();
        }
        \Yii::$app->applicant->logout();
        return $this->redirect(['home/index']);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new \frontend\models\PasswordResetRequestForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email or mobile for further instructions.');
                \Yii::$app->session->remove('bruteForce');
                return $this->redirect(Url::toRoute(['/auth/reset-password']));
            }
            else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
            return $this->redirect('/auth/request-password-reset');
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword()
    {
        if($this->bruteForceCheck()) {
            throw new \components\exceptions\AppException('Oops! You have exceeded the limit to perform this action.');
        }
        $model = new \frontend\models\ResetPasswordForm();
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $postParams = Yii::$app->request->post();
                $postParams['ResetPasswordForm']['otp'] = Security::cryptoAesDecrypt($postParams['ResetPasswordForm']['otp'], Yii::$app->params['hashKey']);
                $postParams['ResetPasswordForm']['password'] = Security::cryptoAesDecrypt($postParams['ResetPasswordForm']['password'], Yii::$app->params['hashKey']);
                $postParams['ResetPasswordForm']['verifypassword'] = Security::cryptoAesDecrypt($postParams['ResetPasswordForm']['verifypassword'], Yii::$app->params['hashKey']);
                if ($model->load($postParams) && $model->validate() && $model->resetPassword()) {
                    Yii::$app->session->setFlash('success', 'Your Password has been updated successfully.');
                    return \components\Helper::outputJsonResponse(['success' => 1, 'redirectUrl' => Url::toRoute(['/auth/login'])]);
                }

                $this->counter = Yii::$app->session->get('bruteForce') + 1;
                Yii::$app->session->set('bruteForce',$this->counter);
                $errors = $model->errors;
                return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
            }
        }

        return $this->render('reset-password', [
                    'model' => $model,
        ]);
    }

    private function bruteForceCheck()
    {           
        return Yii::$app->session->get('bruteForce') >= $this->attempts;
    }

    public function actionChangePassword()
    {
        $model = new \frontend\models\ChangePasswordForm;
        $model->id = Yii::$app->applicant->id;
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $postParams = Yii::$app->request->post();
                $postParams['ChangePasswordForm']['current_password'] = Security::cryptoAesDecrypt($postParams['ChangePasswordForm']['current_password'], Yii::$app->params['hashKey']);
                $postParams['ChangePasswordForm']['password'] = Security::cryptoAesDecrypt($postParams['ChangePasswordForm']['password'], Yii::$app->params['hashKey']);
                $postParams['ChangePasswordForm']['verifypassword'] = Security::cryptoAesDecrypt($postParams['ChangePasswordForm']['verifypassword'], Yii::$app->params['hashKey']);
                if ($model->load($postParams) && $model->validate() && $model->resetPassword()) {
                    Yii::$app->session->setFlash('success', 'Your Password has been changed successfully.');
                    return \components\Helper::outputJsonResponse(['success' => 1]);
                }

                $errors = $model->errors;
                return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
            }
        }
        return $this->render('change-password', [
                    'model' => $model,
        ]);
    }
    
    public function actionRecoverEmail()
    {
        $model = new \frontend\models\LogActivityForm;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->applicant()) {
                    Yii::$app->session->setFlash('success', 'Email: ' . $model->_applicant->email);
                } else {
                    $this->setErrorMessage("Sorry, Applicant not found.");
                }
            } else {
                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }

        return $this->render('log-appicant-form', [
                    'model' => $model,
                    'title' => 'RECOVER Email Id'
        ]);
    }
    
    public function actionChangeEmail()
    {
        $model = new \frontend\models\LogActivityForm;
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    if ($model->applicant()) {
                        $verifyModel = new \frontend\models\VerifyOTPForm;
                        $verifyModel->setScenario(\frontend\models\VerifyOTPForm::SCENARIO_CHANGE_EMAIL_OTP);
                        $verifyModel->type = \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_EMAIL_OTP;
                        $verifyModel->applicant_id = $model->_applicant->id;
                        $template = $this->renderAjax('partials/_change-email-otp-modal', ['model' => $verifyModel]);
                        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
                    } else {
                        return \components\Helper::outputJsonResponse(['success' => 2, 'errors' => "Sorry, Applicant not found."]);
                    }
                } else {
                    $errors = $model->errors;
                    return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
                }
            }
        }

        return $this->render('log-appicant-form', [
                    'model' => $model,
                    'title' => 'Change Email Id'
        ]);
    }
    
    public function actionChangeMobile()
    {
        $model = new \frontend\models\LogActivityForm;
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    if ($model->applicant()) {
                        $verifyModel = new \frontend\models\VerifyOTPForm;
                        $verifyModel->setScenario(\frontend\models\VerifyOTPForm::SCENARIO_CHANGE_MOBILE_OTP);
                        $verifyModel->type = \frontend\models\VerifyOTPForm::SCENARIO_CHANGE_MOBILE_OTP;
                        $verifyModel->applicant_id = $model->_applicant->id;
                        $template = $this->renderAjax('partials/_change-mobile-otp-modal', ['model' => $verifyModel]);
                        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template]);
                    } else {
                        return \components\Helper::outputJsonResponse(['success' => 2, 'errors' => "Sorry, Applicant not found."]);
                    }
                } else {
                    $errors = $model->errors;
                    return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $errors]);
                }
            }
        }

        return $this->render('log-appicant-form', [
                    'model' => $model,
                    'title' => 'Change Mobile'
        ]);
    }
    
    public function actionMessageScript()
    {
        die('stop');
        $query = "select applicant_post.id, applicant.mobile, applicant.email from applicant_post inner join applicant on applicant.id = applicant_post.applicant_id where applicant_post.application_status = ".\common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED." AND applicant_post.payment_status = ".\common\models\ApplicantPost::STATUS_PAID." and applicant_post.id <= 436236 limit 500 offset 3000";
        $applicantPosts = Yii::$app->db->createCommand($query)->queryAll();
        foreach ($applicantPosts as $applicant) {
            \Yii::$app->sms->sendMesssage($applicant['mobile'], 'Dear candidate please download your application form');
        }
        die('all done');
    }
    
    public function actionPending()
    {
        $params = [
            'selectCols' => ['transaction.id', 'transaction.transaction_id', 'transaction.type'],
            'isProcessed' => \common\models\Transaction::IS_NOT_PROCESS,
            'inPayStatus' => [
                \common\models\Transaction::TYPE_STATUS_FOR_CREATED,
                \common\models\Transaction::TYPE_STATUS_PENDING,
                \common\models\Transaction::TYPE_STATUS_FAILED,
            ],
            'type' => \common\models\Transaction::TYPE_AXIS,
            'limit' => 1,
            'orderBy' => [
                'id' => SORT_DESC
            ],
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
        ];

        $transactionModel = \common\models\Transaction::findByKeys($params);
        if ($transactionModel == NULL) {
            return FALSE;
        }
        //echo '<pre>'; print_r($transactionModel);die;
        foreach ($transactionModel as $transaction) {
            $model = new \common\models\Transaction();
            $model->processScheduler($transaction['id'], $transaction['type']);
            echo '<pre>'; print_r($transaction);die;
        }
    }
    
    public function actionScheduler()
    {
        (new Transaction)->createSchedulerJob(Transaction::TYPE_HDFC);
        (new Transaction)->createSchedulerJob(Transaction::TYPE_BOB);
        (new Transaction)->createSchedulerJob(Transaction::TYPE_CSC);
        (new Transaction)->createSchedulerJob(Transaction::TYPE_AXIS);

        return "SUCCESS";
    }
    
    public function actionApplicationNoScheduler()
    {
        (new \common\models\ApplicantPost)->createSchedulerJob();

        return "SUCCESS";
    }
    
    public function actionLogProfile()
    {
        (new \common\models\LogProfile)->createSchedulerJob();

        return "SUCCESS";
    }

    public function actionGetAdmitCard()
    {
        $model = new \frontend\models\AdmitCardForm;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->applicant()) {
                    return $this->redirect(Url::toRoute(['hall-ticket', 'guid' => $model->guid]));
                } else {
                    Yii::$app->session->setFlash('error', 'Sorry, Applicantion not found.');
                }
            } else {
                $errors = $model->errors;
                Yii::$app->session->setFlash('error', Helper::convertModelErrorsToString($errors));
            }
        }

        return $this->render('get-admit-card-form', [
                    'model' => $model,
                    'title' => 'Download Admit Card'
        ]);
    }

    public function actionHallTicket($guid)
    {
        $this->layout = false;
        $applicantPostModel = \common\models\ApplicantPost::findByGuid($guid, [
            'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED,
            'paymentStatus' => \common\models\ApplicantPost::STATUS_PAID
        ]);
        if ($applicantPostModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists or deleted.");
        }
        if (!\common\models\MstClassified::validateAdmitCard($applicantPostModel['classified_id'])) {
            throw new \components\exceptions\AppException(Yii::t('app', 'admit.notfound', ['title' => 'Admit Card']));
        }

        $applicantExam = \common\models\ApplicantExam::getHallTicketDate($applicantPostModel['id'], ['examType' => \common\models\ApplicantExam::EXAM_TYPE_WRITTEN]);
        if ($applicantExam === NULL) {
            throw new \components\exceptions\AppException("Sorry, Rollno not assign. Please contact with administrator.");
        }
        return $this->render('@common/views/receipt/hall-ticket.php', [
                    'guid' => $guid,
                    'applicantPostModel' => $applicantPostModel,
                    'model' => $applicantExam
        ]);
    }
}