<?php

namespace common\controllers;

use Yii;
use components\exceptions\AppException;
use common\models\caching\ModelCache;

/**
 * Description of RegistrationController
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class BaseApplicantController extends \yii\web\Controller
{

    public function actionPreview($guid, $first = 0, $last = 0)
    {
        $this->layout = false;
        $applicantPostModel = $this->findByApplicantPostModel($guid);
        $applicantModel = \common\models\Applicant::findById($applicantPostModel['applicant_id']);
        $applicantDetailModel = \common\models\ApplicantDetail::findByApplicantPostId($applicantPostModel['id']);
        if ($applicantDetailModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists or deleted.");
        }
        $applicantPostDetailModel = \common\models\ApplicantPostDetail::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantPermanentAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($applicantPostModel['id'], ['addressType' => \common\models\ApplicantAddress::CURRENT_ADDRESS]);
        $applicantCorrespondenceAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($applicantPostModel['id'], ['addressType' => \common\models\ApplicantAddress::PERMANENT_ADDRESS]);
        $applicantEmploymentModel = \common\models\ApplicantEmployment::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantDocumentModel = \common\models\ApplicantDocument::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantFeeModel = \common\models\ApplicantFee::findByApplicantPostId($applicantPostModel['id']);
        //$applicantCriteriaModel = \common\models\ApplicantLt::findByApplicantPostId($applicantPostModel['id']);
        //$applicantCriteriaDetailModel = \common\models\ApplicantLtDetail::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantCriteriaModel = \common\models\ApplicantCriteria::findByApplicantPostId($applicantPostModel['id']);
        //$applicantCriteriaDetailModel = \common\models\ApplicantCriteriaDetail::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantQualificationModel = \common\models\ApplicantQualification::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantFeeModel = \common\models\ApplicantFee::findByApplicantPostId($applicantPostModel['id']);
        $applicantPostExamCentreModel = \common\models\ApplicantPostExamCentre::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $transactionModel = \common\models\Transaction::findByApplicantId($applicantPostModel['applicant_id'], [
                    'joinWithApplicantFee' => 'innerJoin',
                    'applicantPostId' => $applicantPostModel['id'],
                    'isConsumed' => \common\models\Transaction::IS_CONSUMED_YES,
                    'payStatus' => \common\models\Transaction::TYPE_STATUS_PAID,
                    'resultCount' => ModelCache::RETURN_ALL
        ]);

        return $this->render('@common/views/receipt/preview-post.php', [
            'guid' => $guid,
            'applicantPostModel' => $applicantPostModel,
            'applicantPostDetailModel' => $applicantPostDetailModel,
            'applicantModel' => $applicantModel,
            'applicantDetailModel' => $applicantDetailModel,
            'applicantPermanentAddressModel' => $applicantPermanentAddressModel,
            'applicantCorrespondenceAddressModel' => $applicantCorrespondenceAddressModel,
            'applicantEmploymentModel' => $applicantEmploymentModel,
            'applicantDocumentModel' => $applicantDocumentModel,
            'applicantFeeModel' => $applicantFeeModel,
            'applicantCriteriaModel' => $applicantCriteriaModel,
            //'applicantCriteriaDetailModel' => $applicantCriteriaDetailModel,
            'applicantPostExamCentreModel' => $applicantPostExamCentreModel,
            'applicantQualificationModel' => $applicantQualificationModel,
            'applicantFeeModel' => $applicantFeeModel,
            'transactionModel' => $transactionModel,
            'first' => $first,
            'last' => $last
        ]);
    }
    
    public function actionPrint($guid)
    {
        $applicantPostModel = $this->findByApplicantPostModel($guid);
        $applicantModel = \common\models\Applicant::findById($applicantPostModel['applicant_id']);
        $applicantDetailModel = \common\models\ApplicantDetail::findByApplicantPostId($applicantPostModel['id']);
        if ($applicantDetailModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists or deleted.");
        }
        $applicantPostDetailModel = \common\models\ApplicantPostDetail::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantPermanentAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($applicantPostModel['id'], ['addressType' => \common\models\ApplicantAddress::CURRENT_ADDRESS]);
        $applicantCorrespondenceAddressModel = \common\models\ApplicantAddress::findByApplicantPostId($applicantPostModel['id'], ['addressType' => \common\models\ApplicantAddress::PERMANENT_ADDRESS]);
        $applicantEmploymentModel = \common\models\ApplicantEmployment::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantDocumentModel = \common\models\ApplicantDocument::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantFeeModel = \common\models\ApplicantFee::findByApplicantPostId($applicantPostModel['id']);
        $applicantCriteriaModel = \common\models\ApplicantCriteria::findByApplicantPostId($applicantPostModel['id']);
        $applicantCriteriaDetailModel = \common\models\ApplicantCriteriaDetail::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantQualificationModel = \common\models\ApplicantQualification::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $applicantFeeModel = \common\models\ApplicantFee::findByApplicantPostId($applicantPostModel['id']);
        $applicantPostExamCentreModel = \common\models\ApplicantPostExamCentre::findByApplicantPostId($applicantPostModel['id'], ['resultCount' => ModelCache::RETURN_ALL]);
        $transactionModel = \common\models\Transaction::findByApplicantId($applicantPostModel['applicant_id'], [
                    'joinWithApplicantFee' => 'innerJoin',
                    'applicantPostId' => $applicantPostModel['id'],
                    'isConsumed' => \common\models\Transaction::IS_CONSUMED_YES,
                    'payStatus' => \common\models\Transaction::TYPE_STATUS_PAID,
                    'resultCount' => ModelCache::RETURN_ALL
        ]);

        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf();
        $template = $this->renderPartial('@common/views/receipt/print-post.php', [
            'applicantPostModel' => $applicantPostModel,
            'applicantPostDetailModel' => $applicantPostDetailModel,
            'applicantModel' => $applicantModel,
            'applicantDetailModel' => $applicantDetailModel,
            'applicantPermanentAddressModel' => $applicantPermanentAddressModel,
            'applicantCorrespondenceAddressModel' => $applicantCorrespondenceAddressModel,
            'applicantEmploymentModel' => $applicantEmploymentModel,
            'applicantDocumentModel' => $applicantDocumentModel,
            'applicantFeeModel' => $applicantFeeModel,
            'applicantCriteriaModel' => $applicantCriteriaModel,
            'applicantCriteriaDetailModel' => $applicantCriteriaDetailModel,
            'applicantPostExamCentreModel' => $applicantPostExamCentreModel,
            'applicantQualificationModel' => $applicantQualificationModel,
            'applicantFeeModel' => $applicantFeeModel,
            'transactionModel' => $transactionModel
        ]);

        $html2pdf->writeHTML($template);
        $html2pdf->output('form-print.pdf');
        ob_flush();die;
    }
    
    protected function findByApplicantPostModel($guid)
    {
        $model = \common\models\ApplicantPost::findByGuid($guid, [
                    'paymentStatus' => \common\models\ApplicantPost::STATUS_PAID
        ]);
        if ($model === NULL) {
            throw new AppException("Sorry, You are trying to access applicant post doesn't exists or deleted.");
        }
        
        if ($model['application_status'] == \common\models\ApplicantPost::APPLICATION_STATUS_CANCELED) {
            throw new AppException(Yii::t('app', 'post.cancel.message'));
        }
        return $model;
    }
    
    public function setSuccessMessage($message)
    {
        Yii::$app->session->setFlash('success', $message);
    }

    public function setErrorMessage($message)
    {
        Yii::$app->session->setFlash('error', $message);
    }

    public function actionHallTicket($guid)
    {
        $this->layout = false;
        $applicantPostModel = $this->findByApplicantPostModel($guid);
        if ($applicantPostModel === NULL) {
            throw new \components\exceptions\AppException("Sorry, You are trying to access applicant doesn't exists or deleted.");
        }
        if (!\common\models\MstClassified::validateAdmitCard($applicantPostModel['classified_id'])) {
            throw new AppException(Yii::t('app', 'admit.notfound', ['title' => 'Admit Card']));
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