<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantCriteriaDetail as BaseApplicantCriteriaDetail;

/**
 * Description of ApplicantCriteriaDetail
 *
 * @author Amit Handa
 */
class ApplicantCriteriaDetail extends BaseApplicantCriteriaDetail
{
    
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ]
        ];
    }
    
    const QUALIFICATION_TYPE = 1;
    const ADDITIONAL_QUALIFICATION_TYPE = 2;
    const INTERMEDIATE_TYPE = 3;

    public static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        $modelAQ->select($tableName . '.*');
        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['applicantId'])) {
            $modelAQ->andWhere($tableName . '.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
        }
        
        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
        }
        
        if (isset($params['applicantCriteriaId'])) {
            $modelAQ->andWhere($tableName . '.applicant_criteria_id =:applicantCriteriaId', [':applicantCriteriaId' => $params['applicantCriteriaId']]);
        }
        
        if (isset($params['graduationQualificationType'])) {
            $modelAQ->andWhere($tableName . '.graduation_qualification_type =:graduationQualificationType', [':graduationQualificationType' => $params['graduationQualificationType']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByApplicantId($applicantId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantId' => $applicantId], $params));
    }
    
    public static function findByApplicantPostId($applicantPostId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId], $params));
    }
    
    public static function findByApplicantCriteriaId($applicantCriteriaId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantCriteriaId' => $applicantCriteriaId], $params));
    }
    
    public function createApplicantCriteriaDetail($data)
    {
        try {
            $model = new ApplicantCriteriaDetail;
            $model->loadDefaultValues(TRUE);
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if (!$model->save()) {
                return FALSE;
            }
        }
        catch (\Exception $ex) {
            return FALSE;
        }
        return $model->id;
    }
    
    /**
     * Load Account Clerk
     * @param type $applicantCriteria
     * @return boolean
     */
    public static function driverLoadData($applicantCriteria) 
    {
        if (empty($applicantCriteria)) {
            return false;
        }
        
        $criteriaDetails = [];
        $postId = $applicantCriteria->applicantPostDetail->post_id;
        $criteriaDetails['post_id'] = $postId;
        $criteriaDetails['criteria_qualification_id'] = $applicantCriteria->field1;
        $criteriaDetails['criteria_experience_id'] = $applicantCriteria->field2;
        
        return $criteriaDetails;
    }

}
