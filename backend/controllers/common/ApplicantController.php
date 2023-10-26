<?php

namespace backend\controllers\common;

use Yii;
use common\models\Applicant;
use common\models\ApplicantDetail;
use common\models\ApplicantLtDetail;
use common\models\ApplicantQualification;
use common\models\ApplicantAddress;
use common\models\ApplicantPost;
use common\models\ApplicantEmployment;
use common\models\ApplicantDocument;
use common\models\ApplicantPostExamCentre;
use backend\models\ApplicantResetPassword;
use common\models\search\ApplicantSearch;
use common\models\caching\ModelCache;
use components\exceptions\AppException;
use components\Security;

/**
 * Description of ApplicantController
 *
 * @author Nitish
 */
class ApplicantController extends \backend\controllers\AdminController
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->hasAdminRole() && !Yii::$app->user->hasClientAdminRole() && !Yii::$app->user->hasHelpdeskRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        return parent::beforeAction($action);
    }
    
    public function actionProfile()
    {
        try {
            $model = new ApplicantSearch;
            $dataProvider = $model->searchProfile(\Yii::$app->request->queryParams);

            return $this->render('profile', [
                        'searchModel' => $model,
                        'dataProvider' => $dataProvider
            ]);
        }
        catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }
    
    public function actionIndex()
    {
        try {
            $model = new ApplicantSearch;
            $dataProvider = $model->search(\Yii::$app->request->queryParams);

            return $this->render('index', [
                        'searchModel' => $model,
                        'dataProvider' => $dataProvider
            ]);
        }
        catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }
    
    public function actionPost()
    {
        try {
            $model = new ApplicantSearch;
            $dataProvider = $model->searchPost(\Yii::$app->request->queryParams);

            return $this->render('post', [
                        'searchModel' => $model,
                        'dataProvider' => $dataProvider
            ]);
        }
        catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }
    
    public function actionExportPreview()
    {
        if (!Yii::$app->user->hasAdminRole() && !Yii::$app->user->hasClientAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        $this->layout = false;
        try {
            $model = new ApplicantSearch;
            $params = [
                'selectCols' => ['applicant_post.guid']
            ];
            $model->application_status = ApplicantPost::APPLICATION_STATUS_SUBMITTED;
            $model->payment_status = ApplicantPost::STATUS_PAID;
            $dataProvider = $model->searchPost(\yii\helpers\ArrayHelper::merge(\Yii::$app->request->queryParams, $params));            

            return $this->render('export-preview', [
                        'dataProvider' => $dataProvider
            ]);
        }
        catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }
    
    public function actionResetPassword($guid, $redirect = '/home/index')
    {        
        $applicantModal = Applicant::findByGuid($guid);
        
        if(empty($applicantModal)){
            throw new AppException('Invalid Guid');
        }
        
        $model = new ApplicantResetPassword;
        $model->applicant_id = $applicantModal['id'];
        if(Yii::$app->request->isPost){
            $postParams = Yii::$app->request->post();
            if(!empty($postParams['ApplicantResetPassword']['new_password'])) {
                $postParams['ApplicantResetPassword']['new_password'] = Security::cryptoAesDecrypt($postParams['ApplicantResetPassword']['new_password'], Yii::$app->params['hashKey']);
            }
            if(!empty($postParams['ApplicantResetPassword']['confirm_new_password'])) {
                $postParams['ApplicantResetPassword']['confirm_new_password'] = Security::cryptoAesDecrypt($postParams['ApplicantResetPassword']['confirm_new_password'], Yii::$app->params['hashKey']);
            }
            $model->load($postParams);
            if($model->validate() && $model->save()){
                $this->setSuccessMessage('Password updated successfully');
                return $this->redirect($redirect);
            }
            
            $this->setErrorMessage('Oops! something went wrong while saving new password');
        }
        
        return $this->renderAjax('partials/_reset-password', [
            'model' => $model
        ]);
    }
    
    public function actionPreview($guid)
    {
        $baseConrtoller = new \common\controllers\BaseApplicantController('baseapplicant', 'backend');
        return $baseConrtoller->actionPreview($guid);
    }
    
    public function actionPrint($guid)
    {
        $baseConrtoller = new \common\controllers\BaseApplicantController('baseapplicant', 'backend');
        return $baseConrtoller->actionPrint($guid);
    }
    
    public function actionView($guid)
    {
        $this->layout = false;
        $applicantModel = $this->findByApplicant($guid);
        $applicantPostModel = ApplicantPost::findByApplicantId($applicantModel['id'], ['postId' => \common\models\MstPost::MASTER_POST]);
        
        $applicantDetailModel = $applicantPermanentAddressModel = $applicantCorrespondenceAddressModel = $applicantQualificationModel = $applicantEmploymentModel = $applicantDocumentModel = [];
        if ($applicantPostModel !== null) {
            $applicantDetailModel = ApplicantDetail::findByApplicantPostId($applicantPostModel['id']);
            $applicantPermanentAddressModel = ApplicantAddress::findByApplicantPostId($applicantPostModel['id'], ['addressType' => \common\models\ApplicantAddress::CURRENT_ADDRESS]);
            $applicantCorrespondenceAddressModel = ApplicantAddress::findByApplicantPostId($applicantPostModel['id'], ['addressType' => \common\models\ApplicantAddress::PERMANENT_ADDRESS]);
            $applicantQualificationModel = ApplicantQualification::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
            $applicantEmploymentModel = ApplicantEmployment::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
            $applicantDocumentModel = ApplicantDocument::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        }

        return $this->render('view.php', [
                    'guid' => $guid,
                    'applicantModel' => $applicantModel,
                    'applicantPostModel' => $applicantPostModel,
                    'applicantDetailModel' => $applicantDetailModel,
                    'applicantPermanentAddressModel' => $applicantPermanentAddressModel,
                    'applicantCorrespondenceAddressModel' => $applicantCorrespondenceAddressModel,
                    'applicantQualificationModel' => $applicantQualificationModel,
                    'applicantEmploymentModel' => $applicantEmploymentModel,
                    'applicantDocumentModel' => $applicantDocumentModel
        ]);
    }
    
    protected function findByApplicantPostModel($guid)
    {
        $model = ApplicantPost::findByGuid($guid, [
                    'paymentStatus' => ApplicantPost::STATUS_PAID,
                    'applicationStatus' => ApplicantPost::APPLICATION_STATUS_SUBMITTED
        ]);
        if ($model === NULL) {
            throw new AppException("Sorry, The applicant post You are trying to access doesn't exists or deleted.");
        }

        return $model;
    }
    
    protected function findByApplicant($guid)
    {
        $model = Applicant::findByGuid($guid);
        if ($model === NULL) {
            throw new AppException("Sorry, The applicant post You are trying to access doesn't exists or deleted.");
        }

        return $model;
    }
    
    public function actionStatus($guid)
    {
        if (!Yii::$app->user->hasAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        try {
            $model = Applicant::findByGuid($guid, [
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);

            if ($model === NULL) {
                throw new AppException("Oops! model not found.");
            }

            $status = ($model->is_active == ModelCache::IS_ACTIVE_YES) ? ModelCache::IS_ACTIVE_NO : ModelCache::IS_ACTIVE_YES;
            $model->is_active = $status;
            $model->save(TRUE, ['is_active']);

            return \components\Helper::outputJsonResponse(['success' => 1, 'status' => $status]);
        }
        catch (\Exception $ex) {
            throw new AppException("Invalid request.");
        }
    }
    
    public function actionExportPost()
    {
        if (!Yii::$app->user->hasAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        try {
            set_time_limit(-1);
            ini_set('memory_limit', '-1');
            $model = new ApplicantSearch;
            $params = [
                'selectCols' => [new \yii\db\Expression('applicant.id, applicant_post.id as applicant_post_id, applicant_post.application_no, applicant_post.place, applicant.name, applicant.email, applicant.mobile, applicant_detail.date_of_birth, applicant_detail.father_name, '
                        . 'applicant_detail.is_orphan, applicant_detail.orphan_name, applicant_detail.mother_name, applicant_detail.birth_state_code, applicant_detail.birth_district_code, applicant_post.application_status, applicant_post.payment_status ')]
            ];
            $dataProvider = $model->searchPost(\yii\helpers\ArrayHelper::merge($params, \Yii::$app->request->queryParams), true);
            
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Sr.No.')
                    ->setCellValue('B1', 'Application No')
                    ->setCellValue('C1', 'Applicant Name')
                    ->setCellValue('D1', 'Email')
                    ->setCellValue('E1', 'Mobile')
                    ->setCellValue('F1', 'Date of Birth')
                    ->setCellValue('G1', 'Are you Orphan?')
                    ->setCellValue('H1', 'Father Name')
                    ->setCellValue('I1', 'Mother Name')
                    ->setCellValue('J1', 'Orphanage Name')
                    ->setCellValue('K1', 'Birth State')
                    ->setCellValue('L1', 'Birth District')
                    ->setCellValue('M1', 'Place')
                    ->setCellValue('N1', 'Preference 1')
                    ->setCellValue('O1', 'Preference 2')
                    ->setCellValue('P1', 'Application Status')
                    ->setCellValue('Q1', 'Payment Status');

            if (!empty($dataProvider)) {
                $counter = 2;
                $states = \common\models\location\MstState::getStateDropdown();
                $districts = \common\models\location\MstDistrict::getDistrictDropdown();
                foreach ($dataProvider as $key => $model) {
                    
                    $birthState = isset($states[$model['birth_state_code']]) ? $states[$model['birth_state_code']] : '';
                    $birthDistrict = isset($districts[$model['birth_district_code']]) ? $districts[$model['birth_district_code']] : '';
                    $orphan = empty($model['is_orphan']) ? 'No' : 'Yes';
                    $father = empty($model['father_name']) ? '' : $model['father_name'];
                    $mother = empty($model['mother_name']) ? '' : $model['mother_name'];
                    /*$applicantPostExamCentre = ApplicantPostExamCentre::findByApplicantPostId($model['applicant_post_id'], [
                        'selectCols' => [new \yii\db\Expression('district_code, preference ')],
                        'returnAll' => ModelCache::RETURN_ALL,
                    ]);*/
                    
                    $applicantPostExamCentre = null;
                    $prefrence1 = $prefrence2 = "";
                    if ($applicantPostExamCentre !== null) {
                        foreach ($applicantPostExamCentre as $examCentre) {
                            if ($examCentre['preference'] == ApplicantPostExamCentre::PREFERENCE_1) {
                                $prefrence1 = isset($districts[$examCentre['district_code']]) ? $districts[$examCentre['district_code']] : '';
                            }
                            if ($examCentre['preference'] == ApplicantPostExamCentre::PREFERENCE_2) {
                                $prefrence2 = isset($districts[$examCentre['district_code']]) ? $districts[$examCentre['district_code']] : '';
                            }
                        }
                    }

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, ($key + 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, $model['application_no']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, $model['name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $counter, $model['email']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $model['mobile']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $model['date_of_birth']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $orphan);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $father);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, $mother);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $counter, $model['orphan_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $counter, $birthState);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $counter, $birthDistrict);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $counter, $model['place']);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $counter, $prefrence1);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $counter, $prefrence2);
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $counter, ApplicantPost::getApplicationStatus($model['application_status']));
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $counter, ApplicantPost::getPaymentStatus($model['payment_status']));
                    $counter++;
                }
            } else {
                \Yii::$app->session->setFlash('error', 'Oops no record found.');
                return $this->redirect(\yii\helpers\Url::toRoute(['index']));
            }

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="applicant-post.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            $objWriter->save('php://output');
            exit;
        }
        catch (\Exception $ex) {
            throw new AppException($ex->getMessage());
        }
    }
}
