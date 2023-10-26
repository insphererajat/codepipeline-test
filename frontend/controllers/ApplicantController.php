<?php

namespace frontend\controllers;

use Yii;
use components\exceptions\AppException;
use common\models\caching\ModelCache;
use common\controllers\BaseApplicantController;
use common\models\Applicant;

/**
 * Description of RegistrationController
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class ApplicantController extends BaseApplicantController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'user' => 'applicant',
                'rules' => [
                    [
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
    
    public function actionPost()
    {
        try {
            $searchModel = new \common\models\search\ApplicantSearch();
            $searchModel->applicant_id = Yii::$app->applicant->id;
            $dataProvider = $searchModel->searchPost(\Yii::$app->request->queryParams);

            return $this->render('post', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        } catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }

    public function actionPreview($guid, $first = 0, $last = 0)
    {
        $this->_cloneProfileIfNotSave($guid);
        return parent::actionPreview($guid);
    }
    
    public function actionPrint($guid)
    {
        $this->_cloneProfileIfNotSave($guid);
        return parent::actionPrint($guid);
    }
    
    private function _cloneProfileIfNotSave($guid)
    {
        $applicantPostModel = $this->findByApplicantPostModel($guid);
        $applicantModel = \common\models\Applicant::findById($applicantPostModel['applicant_id']);
        $applicantDetailModel = \common\models\ApplicantDetail::findByApplicantPostId($applicantPostModel['id']);
        if ($applicantDetailModel === NULL) {
            \common\models\ApplicantPost::cloneMasterProfile($applicantPostModel['id']);
        }
    }
    
    protected function findByApplicantPostModel($guid)
    {
        $model = \common\models\ApplicantPost::findByGuid($guid, [
                    'paymentStatus' => \common\models\ApplicantPost::STATUS_PAID,
                    'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED
        ]);
        if ($model === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant post doesn't exists or deleted.");
        }
        return $model;
    }
    
    public function actionUpdate()
    {
        try {
            $model = new \frontend\models\LogProfileForm();
            $model->applicant_id = Yii::$app->applicant->id;
            $model->getData();
            if (Yii::$app->request->isPost) {

                if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveData()) {
                    $this->setSuccessMessage('Your data has been sent successfully to admin. the response will update on your dashboard soon.');
                    return $this->redirect(\yii\helpers\Url::toRoute(['post']));
                } else {

                    $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
                }
            }
            
            $searchModel = new \common\models\search\LogProfileSearch;
            $searchModel->applicant_id = Yii::$app->applicant->id;
            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

            return $this->render('update', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        } catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }

    public function actionHallTicket($guid)
    {
        return parent::actionHallTicket($guid);
    }

}