<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ExamCentreDetail as BaseExamCentreDetail;
use common\models\caching\ModelCache;

/**
 * Description of ExamCentreDetail
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class ExamCentreDetail extends BaseExamCentreDetail
{
    public $state, $district, $classified, $preference1, $post, $exam_centre, $shift;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on'],
                ],
            ],
            \components\behaviors\GuidBehavior::className()
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }else{
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }
        
        if (isset($params['examCentreId'])) {
            $modelAQ->andWhere($tableName . '.exam_centre_id =:examCentreId', [':examCentreId' => $params['examCentreId']]);
        }
        
        if (isset($params['classifiedId'])) {
            $modelAQ->andWhere($tableName . '.classified_id =:classifiedId', [':classifiedId' => $params['classifiedId']]);
        }
        
        if (isset($params['postId'])) {
            $modelAQ->andWhere($tableName . '.post_id =:postId', [':postId' => $params['postId']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['capacityGreater'])) {
            $modelAQ->andWhere($tableName . '.capacity > exam_centre_detail.allocated');
        }        
        
        if (isset($params['examCentreIdNotEqual'])) {
            $modelAQ->andWhere($tableName . '.exam_centre_id !=:examCentreIdNotEqual', [':examCentreIdNotEqual' => $params['examCentreIdNotEqual']]);
        }
        
        if (isset($params['joinWithExamCentre']) && in_array($params['joinWithExamCentre'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithExamCentre']}('exam_centre', 'exam_centre.id = exam_centre_detail.exam_centre_id');
            
            if (isset($params['examCentreDistrictCode'])) {
                $modelAQ->andWhere('exam_centre.district_code =:examCentreDistrictCode', [':examCentreDistrictCode' => $params['examCentreDistrictCode']]);
            }
            
            if (isset($params['examCentreClassifiedId'])) {
                $modelAQ->andWhere('exam_centre.classified_id =:examCentreClassifiedId', [':examCentreClassifiedId' => $params['examCentreClassifiedId']]);
            }
            
            if (isset($params['inExamCentreDistrictCode'])) {
                $modelAQ->andWhere(['IN', 'exam_centre.district_code', $params['inExamCentreDistrictCode']]);
            }

            if (isset($params['joinWithDistrict']) && in_array($params['joinWithDistrict'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
                $modelAQ->{$params['joinWithDistrict']}('mst_district', 'mst_district.code = exam_centre.district_code');
            }
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }
    
    public static function findByKeys($params = [])
    {
        return self::findByParams($params);
    }
    
    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }
    
    public static function findByExamCentreId($examCentreId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['examCentreId' => $examCentreId], $params));
    }
    
    public static function findByClassifiedId($classifiedId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['classifiedId' => $classifiedId], $params));
    }
    
    public static function findByPostId($postId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['postId' => $postId], $params));
    }

    /**
     * Get Room No
     */
    public static function getRoomNoDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'exam_centre_detail.id', 'exam_centre_detail.room_no'
            ],
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'resultCount' => ModelCache::RETURN_ALL,
            'orderBy' => ['exam_centre_detail.room_no' => SORT_ASC]
        ];

        $examCentreDetail = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($examCentreDetail, 'id', 'room_no');
        return $list;
    }

    public function beforeSave($insert)
    {
        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = strip_tags($this->{$key});
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        return parent::beforeSave($insert);
    }
    
    public function afterFind()
    {
        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = htmlentities($attribute);
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        parent::afterFind();
    }
}
