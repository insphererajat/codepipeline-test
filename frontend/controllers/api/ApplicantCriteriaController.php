<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers\api;

use Yii;
use common\models\LogOtp;
use common\models\caching\ModelCache;
use common\models\MstPost;

/**
 * Description of ApplicantCriteria
 *
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class ApplicantCriteriaController extends ApiController
{
    
    public function actionGetQualification()
    {

        $postId = Yii::$app->request->post('postId');
        if (empty($postId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        $mstPostCriteria = \common\models\MstPostCriteria::findByPostId($postId);
        if ($mstPostCriteria === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        $qualificationListModel = \common\models\MstPostQualification::findByPostCriteriaId($mstPostCriteria['id'], [
            'selectCols' => [new \yii\db\Expression("DISTINCT mst_qualification.id, mst_qualification.name"),],
            'joinWithQualification' => 'innerJoin',
            'groupBy' => ['mst_post_qualification.option_seq'],
            'isActive' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'returnAll' => ModelCache::RETURN_ALL
        ]);
        
        $list = \yii\helpers\ArrayHelper::map($qualificationListModel, 'id', 'name');
        $qualification = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $list, 'prompt' => '']);
        
        $durationArr = \common\models\ApplicantCriteria::getDurationArr();
        if(\yii\helpers\ArrayHelper::isIn($postId, [MstPost::SKA_SUPERVISOR, MstPost::SKA_SANVIKSHAK, MstPost::SKA_DEO])) {
            unset($durationArr[\common\models\ApplicantCriteria::DURATION_FOUR_YEAR]);
        }
        $duration = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $durationArr, 'prompt' => '']);
        return \components\Helper::outputJsonResponse(['success' => 1, 'qualification' => $qualification, 'durationArr' => $duration]);
    }
    
    public function actionGetUniversity()
    {

        $postId = Yii::$app->request->post('postId');
        $qualificationId = Yii::$app->request->post('qualificationId');
        if (empty($postId) || empty($qualificationId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        $mstPostCriteria = \common\models\MstPostCriteria::findByPostId($postId);
        if ($mstPostCriteria === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        $ulModel = \common\models\MstPostQualification::findByPostCriteriaId($mstPostCriteria['id'], [
            'selectCols' => [new \yii\db\Expression("DISTINCT mst_university.id, mst_university.name as name")],
            'qualificationId' => $qualificationId,
            'joinWithUniversity' => 'innerJoin',
            'groupBy' => ['mst_post_qualification.qualification_id'],
            'isActive' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO
        ]);
        
        return \components\Helper::outputJsonResponse(['success' => 1, 'universityModel' => $ulModel]);
    }
    
    public function actionGetAdditionalUniversity()
    {

        $postId = Yii::$app->request->post('postId');
        $qualificationId = Yii::$app->request->post('qualificationId');
        if (empty($postId) || empty($qualificationId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        $mstPostCriteria = \common\models\MstPostCriteria::findByPostId($postId);
        if ($mstPostCriteria === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        $ulModel = \common\models\MstPostQualification::findByPostCriteriaId($mstPostCriteria['id'], [
            'selectCols' => [new \yii\db\Expression("DISTINCT mst_university.id, mst_university.name as name")],
            'additionalQualificationId' => $qualificationId,
            'joinWithAdditionalUniversity' => 'innerJoin',
            'groupBy' => ['mst_post_qualification.additional_qualification_id'],
            'isActive' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'orderBy' => ['mst_university.name' => SORT_ASC]
        ]);
        
        return \components\Helper::outputJsonResponse(['success' => 1, 'universityModel' => $ulModel]);
    }
    
    public function actionGetQualificationSubject()
    {

        $postId = Yii::$app->request->post('postId');
        $qualificationId = Yii::$app->request->post('qualificationId');
        if (empty($postId) || empty($qualificationId)) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        $mstPostCriteria = \common\models\MstPostCriteria::findByPostId($postId);
        if ($mstPostCriteria === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        $subjectListModel = \common\models\MstPostQualification::findByPostCriteriaId($mstPostCriteria['id'], [
            'selectCols' => [new \yii\db\Expression("DISTINCT mst_subject.id, mst_subject.name")],
            'qualificationId' => $qualificationId,
            'joinWithSubject' => 'innerJoin',
            'isActive' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'returnAll' => ModelCache::RETURN_ALL
        ]);
        
        $list = \yii\helpers\ArrayHelper::map($subjectListModel, 'id', 'name');
        $template = $this->renderPartial('_criteria-subjects.php', ['subjectList' => $list, 'prompt' => '', 'qualificationId' => $qualificationId, 'postId' => $postId]);
        
        $additionalQualificationListModel = \common\models\MstPostQualification::findByPostCriteriaId($mstPostCriteria['id'], [
            'selectCols' => [new \yii\db\Expression("DISTINCT mst_qualification.id, mst_qualification.name"),],
            'qualificationId' => $qualificationId,
            'joinWithAdditionalQualification' => 'innerJoin',
            'groupBy' => ['mst_post_qualification.option_seq'],
            'isActive' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'returnAll' => ModelCache::RETURN_ALL,
            'orderBy' => ['mst_qualification.name' => SORT_ASC]
        ]);
        
        $list = \yii\helpers\ArrayHelper::map($additionalQualificationListModel, 'id', 'name');
        $additionalTemplate = $this->renderPartial('/api/location/_dropdown.php', ['dropdownArr' => $list, 'prompt' => '']);
        return \components\Helper::outputJsonResponse(['success' => 1, 'template' => $template, 'additionalTemplate' => $additionalTemplate, 'additionalFlag' => !empty($additionalQualificationListModel) ? 1 : 0]);
    }

}
