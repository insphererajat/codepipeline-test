<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantCriteria as BaseApplicantCriteria;

/**
 * Description of ApplicantCriteria
 *
 * @author Amit Handa
 */
class ApplicantCriteria extends BaseApplicantCriteria
{
    
    // applied category
    const APPLIED_CATEGORY_YES = 1;
    const APPLIED_CATEGORY_NO = 0;
    
    public static function getTypeOfLicenceArr($key = null)
    {
        $list = [
            1 => 'LMV (Light moter)',
            2 => 'MMV (Medium moter)',
            3 => 'HMV (Heavy moter)',
        ];
        
        return (isset($list[$key])) ? $list[$key] : $list;
    }


        /**
     * @inheritdoc
     */
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
        
        if (isset($params['joinWithApplicantPostDetail']) && in_array($params['joinWithApplicantPostDetail'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPostDetail']}('applicant_post_detail', 'applicant_post_detail.id = applicant_criteria.applicant_post_detail_id');
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
    
    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
    
    public function afterFind()
    {
        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = htmlentities($attribute);
        }
        parent::afterFind();
    }
    
    public static function getAppliedCategoryArr($categoryId, $key = null)
    {
        $list = [
            caching\ModelCache::IS_ACTIVE_NO => MstListType::getName($categoryId),
            caching\ModelCache::IS_ACTIVE_YES => 'Unreserved/General',
        ];
        
        return (isset($list[$key])) ? $list[$key] : $list;
    }
    
    public function create($data)
    {
        try {
            $model = new ApplicantCriteria;
            $model->loadDefaultValues(TRUE);
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if (!$model->save()) {
                echo '<pre>'; print_r($model->errors);die;
                return FALSE;
            }
        }
        catch (\Exception $ex) {
            return FALSE;
        }
        return $model->id;
    }
    
    public static function loadData4($applicantCriteria) 
    {
        if (empty($applicantCriteria)) {
            return false;
        }
        
        $criteriaDetails = [];
        $p = $c = 0;
        foreach ($applicantCriteria as $key => $criteria) {
            $postId = $criteria['post_id'];
            $c = ($p != $postId) ? 1 : $c+1;
            $criteriaDetails[$postId]['post_id'] = $postId;            
            $criteriaDetails[$postId]['field'.$c] = $criteria['field1'].'~'.$criteria['field2'].'~'.$criteria['field3'];
            $p = $postId;
        }
        
        return $criteriaDetails;
    }
    
    public static function loadData5($applicantCriteria) 
    {
        if (empty($applicantCriteria)) {
            return false;
        }
        
        $criteriaDetails = [];
        foreach ($applicantCriteria as $key => $criteria) {
            $postId = $criteria['post_id'];
            $criteriaDetails[$postId]['post_id'] = $postId;            
            $criteriaDetails[$postId]['field1'] = $criteria['field1'];
            $criteriaDetails[$postId]['field2'] = $criteria['field2'];
        }
        //echo '<pre>';print_r($criteriaDetails);die;
        return $criteriaDetails;
    }
}
