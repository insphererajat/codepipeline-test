<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstPostFee as BaseMstPostFee;
use common\models\caching\ModelCache;
use yii\helpers\ArrayHelper;

class MstPostFee extends BaseMstPostFee
{    
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
    
    public static function getPostFee($params = [], $ad = null)
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
        $subCategoryArr['isNullSubCategoryId'] = true;
        $subCategoryArr['categoryId'] = $ad['social_category_id'];
        if ($ad['is_domiciled'] != ModelCache::IS_ACTIVE_YES && ArrayHelper::isIn($ad['social_category_id'], [MstListType::ST, MstListType::SC, MstListType::OBC])) {
            $subCategoryArr['categoryId'] = MstListType::UNRESERVED_GENERAL;
        }
        if (!empty($ad['disability_id']) && $ad['disability_id'] != MstListType::NOT_APPLICABLE) {
            $subCategoryArr['categoryId'] = NULL;
            unset($subCategoryArr['isNullSubCategoryId']);
            $subCategoryArr['subCategoryId'] = MstListType::SUB_CATEGORY_PH;
        }
        /*if (!empty($ad['is_exserviceman']) && $ad['is_exserviceman'] == ModelCache::IS_ACTIVE_YES) {
            $subCategoryArr['categoryId'] = NULL;
            unset($subCategoryArr['isNullSubCategoryId']);
            $subCategoryArr['subCategoryId'] = MstListType::SUB_CATEGORY_EX;
        }*/

        $qr = [
            'selectCols' => new \yii\db\Expression("MIN(amount) AS amount"),
            'classifiedId' => $params['classifiedId'],
            'isActive' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
        ];
        $record = MstPostFee::findByParams(\yii\helpers\ArrayHelper::merge($qr, $subCategoryArr));
        if(empty($record['amount'])) {
            $qr = [
                'selectCols' => new \yii\db\Expression("MIN(amount) AS amount"),
                'classifiedId' => $params['classifiedId'],
                'isActive' => caching\ModelCache::IS_ACTIVE_YES,
                'isDeleted' => caching\ModelCache::IS_DELETED_NO,
                'isNullCategoryId' => true,
                'subCategoryId' => MstListType::SUB_CATEGORY_NA
            ];
            $record = MstPostFee::findByParams($qr);
        }
        if (isset($record['amount'])) {
            /*if (\Yii::$app->session->has('_connectData')) {
                $mstConfigModel = MstConfiguration::findByType(MstConfiguration::TYPE_PAYMENT_CSC);
                if ($mstConfigModel != null) {
                    $mstConfigModel = MstConfiguration::decryptValues($mstConfigModel);
                    if (!empty($mstConfigModel['configuration_rule'])) {
                        $rule = \yii\helpers\Json::decode($mstConfigModel['configuration_rule']);
                        $record['amount'] += isset($rule['rule']['wallet']) ? $rule['rule']['wallet'] : 0;
                    }
                }
            }*/
        }
        return $record;
    }
}
