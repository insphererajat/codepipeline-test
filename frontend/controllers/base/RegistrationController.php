<?php

namespace frontend\controllers\base;

use Yii;
use yii\bootstrap\ActiveForm;
use frontend\models\RegistrationForm;
use yii\web\UploadedFile;
use frontend\models\ReviewForm;
use components\exceptions\AppException;
use common\models\Applicant;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use components\Helper;
use components\Security;

/**
 * Description of RegistrationController
 *
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class RegistrationController extends AppController
{

    private $applicantId;
    private $applicantGuid;
    private $applicantPostId;
    private $classifiedId;
    private $postId;
    private $step = 0;
    private $paymentStatus = 0;
    private $applicationStatus = 0;
    private $sameAsPresentAddress = 0;
    private $_applicantModel = null;
    private $folder;
    private $is_eservice = false;
    private $qp = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \components\behaviors\AccessControl::className(),
                'only' => ['basic-details','personal-details',],
                'rules' => [
                    [
                        'actions' => ['basic-details', 'validate'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['basic-details','personal-details', 'validate', 'address-details', 'other-details', 'qualification-details', 'employment-details', 'document-details', 'criteria-details', 'review'],
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

    public function beforeAction($action)
    {
        $queryParams = \Yii::$app->request->queryParams;
        $this->qp = $queryParams;
        $guid = \common\models\MstClassified::MASTER_PROFILE_GUID;
        if ($action->id !== 'validate') {
            
            if (isset($queryParams['guid']) && !empty($queryParams['guid'])) {
                $guid = $queryParams['guid'];
            }

            $mstClassified = \common\models\MstClassified::findByGuid($guid);
            if ($mstClassified === null) {
                $this->setErrorMessage("Opps, Invalid Advertisement!");
                Yii::$app->response->redirect('/home/index', 301)->send();exit;
            }
            if($mstClassified['is_active'] == \common\models\caching\ModelCache::IS_ACTIVE_NO) {
                $this->setErrorMessage("Sorry, Advertisement: ".$mstClassified['title']." is closed now!");
                Yii::$app->response->redirect('/home/index', 301)->send();exit;
            }
            $this->classifiedId = $mstClassified['id'];
            $pguid = isset($queryParams['pguid']) ? $queryParams['pguid'] : \common\models\MstPost::MASTER_POST_GUID;
            $mstPostModel = \common\models\MstPost::findByGuid($pguid, ['selectCols' => ['id', 'guid', 'folder_name']]);
            // get applicant post details
            $this->folder = $mstPostModel['folder_name'];
            $this->postId = $mstPostModel['id'];
        }
        
        if (ArrayHelper::isIn($action->id, ['criteria-details', 'review'])) {

            if (!isset($queryParams['guid']) || empty($queryParams['guid'])) {
                $this->setErrorMessage("Opps, Invalid request!");
                Yii::$app->response->redirect('/home/index', 301)->send();die;
            }
        }

        if (!Yii::$app->applicant->isGuest) {

            $this->_applicantModel = Yii::$app->applicant->identity;
            $this->applicantId = Yii::$app->applicant->id;
            $this->applicantGuid = Yii::$app->applicant->getIdentity()->guid;

            if (!empty($mstPostModel)) {

                if (isset($queryParams['eservice']) && !empty($queryParams['eservice'])) {
                    
                    if (!isset($queryParams['post_id']) || empty($queryParams['post_id']) || !isset($queryParams['epguid']) || empty($queryParams['epguid'])) {
                        throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound', ['title' => 'Applicant Post']));
                    }
                    $applicantPostModel = \common\models\ApplicantPost::findByApplicantIdAndPostId($this->applicantId, $queryParams['post_id'], [
                                'selectCols' => ['applicant_post.id', 'applicant_post.post_id', 'applicant_post.application_status', 'applicant_post.same_as_present_address', 'applicant_post.payment_status'],
                                'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE,
                                'joinWithPost' => 'innerJoin',
                                'pguid' => $queryParams['epguid']
                    ]);
                    
                    if ($applicantPostModel === NULL) {
                        throw new \components\exceptions\AppException(Yii::t('app', 'model.notfound', ['title' => 'Applicant Post']));
                    }

                    $this->applicantPostId = $applicantPostModel['id'];
                    $this->step = $this->_applicantModel->form_step;
                    $this->sameAsPresentAddress = $applicantPostModel['same_as_present_address'];
                    $this->is_eservice = true;
                }
                else
                {
                    $applicantPostModel = \common\models\ApplicantPost::findByApplicantIdAndPostId($this->applicantId, $mstPostModel['id'], ['selectCols' => ['id', 'post_id', 'application_status', 'same_as_present_address', 'payment_status']]);
                    if (!empty($applicantPostModel)) {

                        $this->applicantPostId = $applicantPostModel['id'];
                        $this->step = $this->_applicantModel->form_step;
                        $this->sameAsPresentAddress = $applicantPostModel['same_as_present_address'];
                    }

                    $applicantPostCompoment = new \frontend\components\ApplicantPostComponent();
                    $applicantPostCompoment->applicantId = Yii::$app->applicant->id;
                    $applicantPostCompoment->checkApplicantPost($guid);
                }
            }
        }
        elseif(!\yii\helpers\ArrayHelper::isIn($action->id, ['basic-details','personal-details'])) {
            //Yii::$app->response->redirect('/home/index', 301)->send();
        }
        if(ArrayHelper::isIn($action->id, ['criteria-details','review'])) {
            $applicantPost = \common\models\ApplicantPost::findByApplicantId(Yii::$app->applicant->id, ['classifiedId' => $this->classifiedId, 'countOnly' => true, 'inApplicationStatus' => [\common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED, \common\models\ApplicantPost::APPLICATION_STATUS_ARCHIVE]]);
            if($applicantPost > 0) {
                throw new \components\exceptions\AppException("Opps, You application already submitted for this advertisement.");
            }
            
            $applicantPost = \common\models\ApplicantPost::findByApplicantId(Yii::$app->applicant->id, ['classifiedId' => $this->classifiedId, 'countOnly' => true, 'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_CANCELED]);
            if($applicantPost > 0) {
                if($mstClassified['cancellation_status'] != \common\models\MstClassified::CANCELLED_REAPPLY) {
                    throw new \components\exceptions\AppException("Sorry, You can't re-apply for this advertisement.");
                }
            }
        }
        return parent::beforeAction($action);
    }

    public function render($view, $params = array())
    {

        $path = '@frontend/views/base';
        $controllerView = \Yii::$app->controller->id . "/" . $view;

        $viewFile = $path . '/' . $controllerView;
        $this->layout = '@frontend/views/layouts/main';

        if (!empty($this->folder)) {

            $path = '@frontend/views/' . $this->folder;
            $viewFile = $path . '/' . $controllerView;

            // Check if  folder exists
            if (!file_exists(\Yii::getAlias($viewFile . ".php"))) {
                // default layout and view file
                $path = '@frontend/views/base';
                $controllerView = \Yii::$app->controller->id . "/" . $view;
                $viewFile = $path . '/' . $controllerView;
            }
        }

        return parent::render($viewFile, $params);
    }

    public function actionBasicDetails($guid = null)
    {
        if(!\common\models\MstClassified::checkClassifiedActiveStatus($this->classifiedId)) {
            throw new AppException("Sorry, There is no active advertisement.");
        }

        $model = new RegistrationForm;
        $model->post_id = $this->postId;

        if (!empty($this->applicantId)) {

            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->classifiedId = $this->classifiedId;
            $model->applicantPostFormStep = $this->step;
            return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/personal-details', $this->qp)));
        }
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveBasicDetails()) {
                $this->setSuccessMessage('Username is your email ID. Password has been emailed to you on your registered email.');
                return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/personal-details', $this->qp)));
            }
            else {

                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('basic-details', ['model' => $model, 'guid' => $guid, 'step' => $this->step]);
    }

    public function actionPersonalDetails($guid = null)
    {
        $model = new RegistrationForm;
        $model->setScenario(RegistrationForm::SCENARIO_FIRST_STEP);
        $model->post_id = $this->postId;
        $model->is_eservice = $this->is_eservice;
        $model->classifiedId = $this->classifiedId;

        if (!empty($this->applicantId)) {

            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->loadBasicDetails();
            $model->loadPersonalDetails();
        }
        
        $model->is_aadhaar_card_holder = \common\models\caching\ModelCache::IS_ACTIVE_NO;
        $model->aadhaar_no = NULL;
        $model->name_on_aadhaar = NULL;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->savePersonalDetails()) {
                $this->setSuccessMessage('Personal details saved successfully.');
                
                return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/address-details', $this->qp)));
            }
            else {
                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }


        return $this->render('personal-details', ['model' => $model, 'guid' => $guid, 'step' => $this->step]);
    }

    public function actionAddressDetails($guid = null)
    {
        if ($this->step < 1) {
            return $this->redirect(Helper::stepsUrl('personal-details', $this->qp));
        }

        $model = new RegistrationForm;
        $model->setScenario(RegistrationForm::SCENARIO_SECOND_STEP);
        $model->post_id = $this->postId;
        $model->is_eservice = $this->is_eservice;
        if (!empty($this->applicantId)) {

            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->applicantPostFormStep = $this->step;
            $model->loadAddressDetails();
        }

        if (Yii::$app->request->isPost) {

            $model->same_as_present_address = Yii::$app->request->post('same_as_present_address');
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveAddressDetails()) {
                $this->setSuccessMessage('Address details saved successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/qualification-details', $this->qp)));
            }
            else {

                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('address-details', ['model' => $model, 'guid' => $guid, 'step' => $this->step]);
    }

    public function actionQualificationDetails($guid = null, $id = null)
    {
        if (\common\models\ApplicantQualification::minimumQualificationValidation($this->applicantPostId) && $this->step < 4) {
            $this->updateFormStep(3);
        }
        if ($this->step < 2) {
            return $this->redirect(Helper::stepsUrl('address-details', $this->qp));
        }

        $model = new RegistrationForm;
        $model->setScenario(RegistrationForm::SCENARIO_FOURTH_STEP);
        $model->post_id = $this->postId;
        $model->is_eservice = $this->is_eservice;
        if (!empty($this->applicantId)) {
            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->applicantPostFormStep = $this->step;
            if (isset($id) && $id > 0) {
                $model->applicantQualificationId = $id;
                $model->loadQualificationDetails();
            }
            $applicantPostmodel = $model->loadQualificationList();

            $qualifications = new \yii\data\ArrayDataProvider([
                'allModels' => $applicantPostmodel,
                'pagination' => [
                    'pageSize' => 100,
                    'params' => \Yii::$app->request->queryParams,
                ],
            ]);
        }
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveQualificationDetails()) {
                unset($this->qp['id']);
                $this->setSuccessMessage('Qualification details saved successfully.');
                return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/qualification-details', $this->qp)));
            }
            else {

                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('qualification-details', ['model' => $model, 'guid' => $guid, 'qualifications' => $qualifications, 'step' => $this->step]);
    }

    public function actionEmploymentDetails($guid = null, $id = null)
    {
        if (\common\models\ApplicantQualification::minimumQualificationValidation($this->applicantPostId) && $this->step < 4) {
            $this->updateFormStep(3);
        }
        if ($this->step < 3) {
            return $this->redirect(Helper::stepsUrl('qualification-details', $this->qp));
        }

        $model = new RegistrationForm;
        $model->setScenario(RegistrationForm::SCENARIO_FIFTH_STEP);
        $model->post_id = $this->postId;
        $model->is_eservice = $this->is_eservice;
        if (!empty($this->applicantId)) {
            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->applicantPostFormStep = $this->step;
            if (isset($id) && $id > 0) {
                $model->applicantEmploymentId = $id;
                $model->loadEmploymentDetails();
            }
            $applicantEmploymentModel = $model->loadEmploymentList();

            $employments = new \yii\data\ArrayDataProvider([
                'allModels' => $applicantEmploymentModel,
                'pagination' => [
                    'pageSize' => 100,
                    'params' => \Yii::$app->request->queryParams,
                ],
            ]);
            
            if (!\common\models\ApplicantQualification::minimumQualificationValidation($model->applicantPostId)) {
                $this->setErrorMessage(Yii::t('app', 'qualification.incomplete'));
                return $this->redirect(\yii\helpers\Url::toRoute(['qualification-details', $this->qp]));
            }
        }
        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveEmploymentDetails()) {
                $this->setSuccessMessage('Employment details saved successfully.');
                unset($this->qp['id']);
                if($model->is_employed == RegistrationForm::SELECT_TYPE_YES) {
                    return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/employment-details', $this->qp)));
                }
                return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/document-details', $this->qp)));
            }
            else {

                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('employment-details', ['model' => $model, 'guid' => $guid, 'employments' => $employments, 'step' => $this->step]);
    }

    public function actionDocumentDetails($guid = null)
    {
        if ($this->step < 4) {
            return $this->redirect(Helper::stepsUrl('employment-details', $this->qp));
        }
        $model = new RegistrationForm;
        $model->setScenario(RegistrationForm::SCENARIO_SIXTH_STEP);
        $model->post_id = $this->postId;
        $model->is_eservice = $this->is_eservice;
        if (!empty($this->applicantId)) {

            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->applicantPostFormStep = $this->step;
            $model->loadApplicantDocuments();
        }

        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveDocuments()) {
                if (!empty($guid)) {
                    $this->setSuccessMessage('Document details saved successfully.');
                    return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/criteria-details', $this->qp)));
                } else {
                    $this->setSuccessMessage('Profile completed successfully.');
                    return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/applicant/post', $this->qp)));
                }
            }
            else {
                $this->setErrorMessage(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('document-details', ['model' => $model, 'guid' => $guid, 'step' => $this->step]);
    }
    
    public function actionCriteriaDetails($guid)
    {
        if ($this->step < 5) {
            return $this->redirect(Helper::stepsUrl('document-details', $this->qp));
        }

        $model = new RegistrationForm;
        switch ($this->classifiedId) {
            case RegistrationForm::SCENARIO_4:
                $model->setScenario(RegistrationForm::SCENARIO_4);
                break;
        }
        $model->post_id = $this->postId;
        $model->is_eservice = $this->is_eservice;
        $model->classifiedId = $this->classifiedId;
        if (!empty($this->applicantId)) {

            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->applicantPostFormStep = $this->step;
            $model->loadApplicantCriteriaDetails();
        }

        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $model->load(Yii::$app->request->post());
                if ($model->validate() && $model->validatePosts() && $model->criteriaValidation() && $model->saveApplicantCriteria()) {
                    return \components\Helper::outputJsonResponse(['success' => 1, 'url' => \yii\helpers\Url::toRoute(['review', 'guid' => $guid])]);
                } else {
                    return \components\Helper::outputJsonResponse(['success' => 0, 'errors' => $model->errors]);
                }
            }
            
            return \components\Helper::outputJsonResponse(['success' => 1]);
        }
        return $this->render('criteria-details', ['model' => $model, 'guid' => $guid, 'step' => $this->step]);
    }
    
    public function actionReview($guid)
    {
        if ($this->step < 6) {
            return $this->redirect(Helper::stepsUrl('document-details', $this->qp));
        }
        $model = new RegistrationForm;
        $model->is_eservice = $this->is_eservice;
        $reviewFormModel = new ReviewForm;
        
        if (false && ArrayHelper::isIn($this->classifiedId, [RegistrationForm::SCENARIO_4])) {
            $reviewFormModel->setScenario(ReviewForm::SCENARIO_PREFERENCE);
        }
        $applicantPost = \common\models\ApplicantPost::findByClassifiedId($this->classifiedId);
        if($applicantPost === null) {
            return $this->redirect(Helper::stepsUrl('criteria-details', $this->qp));
        }
        
        $model->post_id = $this->postId;
        $model->classifiedId = $this->classifiedId;
        if (!empty($this->applicantId)) {

            $model->id = $this->applicantId;
            $model->guid = $this->applicantGuid;
            $model->applicantPostId = $this->applicantPostId;
            $model->applicantPostFormStep = $this->step;
            
            if (!\common\models\ApplicantQualification::minimumQualificationValidation($model->applicantPostId)) {
                $this->setErrorMessage(Yii::t('app', 'qualification.incomplete'));
                return $this->redirect(\yii\helpers\Url::toRoute(['qualification-details', $this->qp]));
            }
            
            if (!Helper::calculateAge(['classifiedId' => $model->classifiedId, 'applicantPostId' => $model->applicantPostId])) {
                $this->setErrorMessage(Yii::t('app', 'dob.notification'));
                return $this->redirect(Helper::stepsUrl('personal-details', $this->qp));
            }
        }

        $model->loadPersonalDetails();
        $model->loadBasicDetails();
        $model->loadAddressDetails();
        $model->loadOtherDetails();
        $model->loadApplicantDocuments();
        $model->loadApplicantCriteriaDetails();
        if(!$model->validatePosts() || !$model->criteriaValidation()) {
            $this->setErrorMessage(Helper::convertModelErrorsToString($model->errors));
            return $this->redirect(\yii\helpers\Url::toRoute(Helper::stepsUrl('/registration/criteria-details', $this->qp)));
        }
        if ($this->is_eservice) {
            $model->loadEserviceFee();
        } else {
            $model->loadApplicantFee();
        }

        $isPaid = $model->checkPostPayment($this->applicantId);
        $reviewFormModel->loadDetails($isPaid['feeId']);
        
        $model->same_as_present_address = $this->sameAsPresentAddress;
        $applicantEmploymentModel = $model->loadEmploymentList();
        $employments = new \yii\data\ArrayDataProvider([
            'allModels' => $applicantEmploymentModel,
            'pagination' => [
                'pageSize' => 100,
                'params' => \Yii::$app->request->queryParams,
            ],
        ]);
        $applicantPostmodel = $model->loadQualificationList();

        $qualifications = new \yii\data\ArrayDataProvider([
            'allModels' => $applicantPostmodel,
            'pagination' => [
                'pageSize' => 100,
                'params' => \Yii::$app->request->queryParams,
            ],
        ]);
        
        if (!$model->validateDocuments($applicantEmploymentModel)) {
            $this->setErrorMessage("Kindly update your documents.");
            return $this->redirect(Helper::stepsUrl('document-details', $this->qp));
        }
        
        return $this->render('review', [
                    'guid' => $guid,
                    'model' => $model,
                    'reviewFormModel' => $reviewFormModel,
                    'employments' => $employments,
                    'qualifications' => $qualifications,
                    'step' => $this->step,
                    'isPaid' => $isPaid
        ]);
    }

    public function actionValidate()
    {
        $model = new RegistrationForm;
        $model->applyRules = false;
        $model->setScenario(RegistrationForm::SCENARIO_OTP_SCREEN);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    protected function findApplicantPostModel($guid)
    {
        $model = \common\models\MstPost::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Sorry, You trying to access type doesn't exist or deleted.");
        }

        return $model;
    }
    
    private function updateFormStep($step)
    {
        Applicant::updateAll(['form_step' => $step], 'id=:applicantId', [':applicantId' => $this->applicantId]);
    }

}
