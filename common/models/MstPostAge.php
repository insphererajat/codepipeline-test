<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstPostAge as BaseMstPostAge;
use common\models\caching\ModelCache;
use common\models\ApplicantDetail;
use yii\helpers\ArrayHelper;

class MstPostAge extends BaseMstPostAge
{
    
    const APPLICANT_MIN_AGE = 21;
    const MAX_AGE_CONCESSION = 5;
    
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on', 'modified_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            \components\behaviors\GuidBehavior::className(),
        ];
    }
    
    public function attributeLabels()
    {
        $baseAttr = parent::attributeLabels();
        $myArrs = [

            'is_active' => 'Status'
        ];
        return \yii\helpers\ArrayHelper::merge($baseAttr, $myArrs);
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select($tableName . '.*');
        }
        
        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id = :id', [':id' => $params['id']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['classifiedId'])) {
            $modelAQ->andWhere($tableName . '.classified_id = :classifiedId', [':classifiedId' => $params['classifiedId']]);
        }
        
        if (isset($params['postId'])) {
            $modelAQ->andWhere($tableName . '.post_id = :postId', [':postId' => $params['postId']]);
        }
        
        if (isset($params['categoryId'])) {
            $modelAQ->andWhere($tableName . '.category_id = :categoryId', [':categoryId' => $params['categoryId']]);
        }
        
        if (isset($params['subCategoryId'])) {
            $modelAQ->andWhere($tableName . '.sub_category_id = :subCategoryId', [':subCategoryId' => $params['subCategoryId']]);
        }
        
        if (isset($params['inSubCategoryIds'])) {
            $modelAQ->andWhere(['IN', $tableName . '.sub_category_id', $params['inSubCategoryIds']]);
        }
        
        if (isset($params['isNullCategoryId'])) {
            $modelAQ->andWhere($tableName . '.category_id IS NULL');
        }
        
        if (isset($params['isNullSubCategoryId'])) {
            $modelAQ->andWhere($tableName . '.sub_category_id IS NULL');
        }
        
        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere($tableName.'.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
        }
        
        return (new \common\models\caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }
    
    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }
    
    public static function findByPostId($postId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['postId' => $postId], $params));
    }
    
    public static function findByClassifiedId($classifiedId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['classifiedId' => $classifiedId], $params));
    }

    public static function findBySubCategoryId($subCategoryId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['subCategoryId' => $subCategoryId], $params));
    }
    
    public static function getPostAge($params = [], $ad = null)
    {
        if (!isset($params['applicantPostId']) || !isset($params['classifiedId'])) {
            throw new \components\exceptions\AppException("Opps, Applicant Post and Classified Id required for get age.");
        }
        if ($ad == null) {
            $ad = ApplicantDetail::findByApplicantPostId($params['applicantPostId']);
            if (empty($ad)) {
                throw new \components\exceptions\AppException("Sorry, You are trying to access applicant detail doesn't exists.");
            }
        }

        $subCategoryArr = [];
        $subCategoryArr['subCategoryId'] = MstListType::SUB_CATEGORY_NA;
        $subCategoryArr['categoryId'] = $ad['social_category_id'];
        if ($ad['is_domiciled'] != ModelCache::IS_ACTIVE_YES && ArrayHelper::isIn($ad['social_category_id'], [MstListType::ST, MstListType::SC, MstListType::OBC])) {
            $subCategoryArr['categoryId'] = MstListType::UNRESERVED_GENERAL;
        }
        if (!empty($ad['disability_id']) && $ad['disability_id'] != MstListType::NOT_APPLICABLE) {
            unset($subCategoryArr['isNullSubCategoryId']);
            $subCategoryArr['subCategoryId'] = MstListType::SUB_CATEGORY_PH;
        }
        // Ex-serviceman commented
        /*if (!empty($ad['is_exserviceman']) && $ad['is_exserviceman'] == ModelCache::IS_ACTIVE_YES) {
            unset($subCategoryArr['isNullSubCategoryId']);
            $subCategoryArr['subCategoryId'] = MstListType::SUB_CATEGORY_EX;
        }*/

        $qr = [
            'selectCols' => new \yii\db\Expression("MIN(min_age) AS min_age, MAX(max_age) AS max_age"),
            'classifiedId' => $params['classifiedId'],
            'isActive' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
        ];
        if (isset($params['postId'])) {
            $qr['postId'] = $params['postId'];
        }
        return MstPostAge::findByParams(\yii\helpers\ArrayHelper::merge($qr, $subCategoryArr));
    }
}
