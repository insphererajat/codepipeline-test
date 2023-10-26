<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ExamCentre as BaseExamCentre;
use common\models\caching\ModelCache;

/**
 * Description of ExamCentre
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class ExamCentre extends BaseExamCentre
{
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
            ],
            \components\behaviors\GuidBehavior::className()
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'capacity' => 'Capacity',
            'district_code' => 'District',
            'state_code' => 'State',
            'is_active' => 'Status',
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
        
        if (isset($params['classifiedId'])) {
            $modelAQ->andWhere($tableName . '.classified_id =:classifiedId', [':classifiedId' => $params['classifiedId']]);
        }

        if (isset($params['code'])) {
            $modelAQ->andWhere($tableName . '.code =:code', [':code' => $params['code']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['name'])) {
            $modelAQ->andWhere($tableName . '.name =:name', [':name' => $params['name']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere($tableName . '.is_deleted =:isDeleted', [':isDeleted' => $params['isDeleted']]);
        }
        
        if (isset($params['stateCode'])) {
            $modelAQ->andWhere($tableName.'.state_code = :stateCode', [':stateCode' => $params['stateCode']]);
        }

        if (isset($params['districtCode'])) {
            $modelAQ->andWhere($tableName.'.district_code = :districtCode', [':districtCode' => $params['districtCode']]);
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

    public static function findByCode($code, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['code' => $code], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function getExamCentreDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'exam_centre.id', "CONCAT(exam_centre.name,'-',exam_centre.code) AS name"
            ],
            'status' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'resultCount' => ModelCache::RETURN_ALL,
            'orderBy' => ['exam_centre.name' => SORT_ASC]
        ];

        $districtModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($districtModel, 'id', 'name');
        return $list;
    }
    
    public static function getExamCentreCodeDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'exam_centre.id', "exam_centre.code"
            ],
            'status' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'resultCount' => ModelCache::RETURN_ALL,
            'orderBy' => ['exam_centre.code' => SORT_ASC]
        ];

        $districtModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($districtModel, 'id', 'code');
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
