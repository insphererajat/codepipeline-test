<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\components;

use Yii;
use common\models\ApplicantPost;
use common\models\ApplicantDetail;
use common\models\ApplicantAddress;
use common\models\ApplicantDocument;
use common\models\ApplicantEmployment;
use common\models\ApplicantLt;
use common\models\ApplicantLtDetail;
use common\models\ApplicantQualification;
use common\models\ApplicantQualificationSubject;
use common\models\MstPost;
use common\models\caching\ModelCache;
use components\exceptions\AppException;
use components\Helper;

/**
 * Description of CloneProfileComponent
 *
 * @author Amit Handa
 */
class CloneProfileComponent extends \yii\base\Component
{
    public $applicantId;
    public $applicantPostId;
    public $postId;

    public function profile()
    {
        if(empty($this->applicantId)) {
            return false;
        }
        
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $applicantPostMaster = ApplicantPost::findByApplicantId($this->applicantId, ['postId' => MstPost::MASTER_POST, 'resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            $applicantPost = ApplicantPost::findById($this->applicantPostId, ['paymentStatus' => ApplicantPost::STATUS_PAID, 'resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

            if ($applicantPost === null) {
                return false;
            }

            $applcantDetail = ApplicantDetail::findByApplicantPostId($applicantPostMaster->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

            $applcantAddress = ApplicantAddress::findByApplicantPostId($applicantPostMaster->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => ModelCache::RETURN_ALL]);
            $applcantDocuments = ApplicantDocument::findByApplicantPostId($applicantPostMaster->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => ModelCache::RETURN_ALL]);
            $applcantEmployments = ApplicantEmployment::findByApplicantPostId($applicantPostMaster->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => ModelCache::RETURN_ALL]);
            $applcantQualifications = ApplicantQualification::findByApplicantPostId($applicantPostMaster->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => ModelCache::RETURN_ALL]);
            
            // Clone ApplicantDetail
            $existingApplcantDetail = ApplicantDetail::findByApplicantPostId($applicantPost->id, ['countOnly' => true]);
            if ($existingApplcantDetail < 1) {
                $cloneApplicantDetail = new ApplicantDetail;
                $cloneApplicantDetail->attributes = $applcantDetail->attributes;
                $cloneApplicantDetail->applicant_post_id = $applicantPost->id;
                $cloneApplicantDetail->birth_tehsil_code = ($cloneApplicantDetail->birth_tehsil_code == \common\models\location\MstTehsil::OTHER) ? null : $cloneApplicantDetail->birth_tehsil_code;
                if (!$cloneApplicantDetail->save()) {
                    throw new AppException(Helper::convertModelErrorsToString($cloneApplicantDetail->errors));
                    return false;
                }
            }

            // clone ApplicantDocument
            $existingApplcantAddress = ApplicantAddress::findByApplicantPostId($applicantPost->id, ['countOnly' => true]);
            if ($applcantAddress !== null && $existingApplcantAddress < 1) {
                foreach ($applcantAddress as $address) {
                    $cloneApplicantAddress = new ApplicantAddress;
                    $cloneApplicantAddress->attributes = $address->attributes;
                    $cloneApplicantAddress->applicant_post_id = $applicantPost->id;
                    $cloneApplicantAddress->tehsil_code = ($cloneApplicantAddress->tehsil_code == \common\models\location\MstTehsil::OTHER) ? null : $cloneApplicantAddress->tehsil_code;
                    if (!$cloneApplicantAddress->save()) {
                        throw new AppException(Helper::convertModelErrorsToString($cloneApplicantAddress->errors));
                        return false;
                    }
                }
            }
            
            // clone ApplicantDocument
            $existingApplcantDocuments = ApplicantDocument::findByApplicantPostId($applicantPost->id, ['countOnly' => true]);
            if ($applcantDocuments !== null && $existingApplcantDocuments < 1) {
                foreach ($applcantDocuments as $document) {
                    $cloneApplicantDocument = new ApplicantDocument;
                    $cloneApplicantDocument->attributes = $document->attributes;
                    $cloneApplicantDocument->applicant_post_id = $applicantPost->id;
                    if (!$cloneApplicantDocument->save()) {
                        throw new AppException(Helper::convertModelErrorsToString($cloneApplicantDocument->errors));
                        return false;
                    }
                }
            }
            
            // clone ApplicantEmployment
            $existingApplcantEmployments = ApplicantEmployment::findByApplicantPostId($applicantPost->id, ['countOnly' => true]);
            if ($applcantEmployments !== null && $existingApplcantEmployments < 1) {
                foreach ($applcantEmployments as $employment) {
                    $cloneApplicantEmployment = new ApplicantEmployment;
                    $cloneApplicantEmployment->attributes = $employment->attributes;
                    $cloneApplicantEmployment->applicant_post_id = $applicantPost->id;
                    if (!$cloneApplicantEmployment->save()) {
                        throw new AppException(Helper::convertModelErrorsToString($cloneApplicantEmployment->errors));
                        return false;
                    }
                }
            }
            
            // clone ApplicantQualification and ApplicantQualificationSubject
            $existingApplcantQualifications = ApplicantQualification::findByApplicantPostId($applicantPost->id, ['countOnly' => true]);
            if ($applcantQualifications !== null && $existingApplcantQualifications < 1) {
                foreach ($applcantQualifications as $qualification) {
                    $cloneApplicantQualification = new ApplicantQualification;
                    $cloneApplicantQualification->attributes = $qualification->attributes;
                    $cloneApplicantQualification->applicant_post_id = $applicantPost->id;
                    if (!$cloneApplicantQualification->save()) {
                        throw new AppException(Helper::convertModelErrorsToString($cloneApplicantQualification->errors));
                        return false;
                    }

                    $applcantQualificationSubjects = ApplicantQualificationSubject::findByApplicantQualificationId($qualification->id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'resultCount' => ModelCache::RETURN_ALL]);
                    if ($applcantQualificationSubjects !== null) {
                        foreach ($applcantQualificationSubjects as $qualificationSubject) {
                            $cloneApplicantQualificationSubject = new ApplicantQualificationSubject;
                            $cloneApplicantQualificationSubject->attributes = $qualificationSubject->attributes;
                            $cloneApplicantQualificationSubject->applicant_qualification_id = $cloneApplicantQualification->id;
                            if (!$cloneApplicantQualificationSubject->save()) {
                                throw new AppException(Helper::convertModelErrorsToString($cloneApplicantQualificationSubject->errors));
                                return false;
                            }
                        }
                    }
                }
            }

            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();
            //echo '<pre>'; print_r($ex->getMessage());die;
            throw new AppException("Oops! unable to clone profile :" . $ex->getMessage());
        }
    }

}
