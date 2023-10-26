<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstUniversity as BaseMstUniversity;

/**
 * Description of MstUniversity
 *
 * @author Amit Handa
 */
class MstUniversity extends BaseMstUniversity
{
    //parent university
    const BOARD = 386;
    const GOVT_RECOGNIZED_INSTITUTE = 1194;
    const RECOGNIZED_INSTITUTE_BY_NCTE = 1198;
    const NCERT = 1506;
    const UNIVERSITY = 1513;
    const OTHER = 1184;
    
    const SCENARIO_CHILD_UNIVERSITY = 'child_university';

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
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['name']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'name' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'name' => 'cleanEncodeUTF8',
                    ]
                ]
            ],
            \components\behaviors\GuidBehavior::className()
        ];
    }
    
    public function rules()
    {
        $baseRules = parent::rules();

        $myRules = [
            [['parent_id', 'name', 'is_active'], 'required', 'on' => self::SCENARIO_CHILD_UNIVERSITY],
            [['name'], 'match', 'pattern' => \components\Helper::alphanumericWithSpecialRegex(), 'message' => Yii::t('app', 'alphabet')],
        ];

        return \yii\helpers\ArrayHelper::merge($baseRules, $myRules);
    }

    private static function findByParams($params = [])
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

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['parentId'])) {
            $modelAQ->andWhere($tableName . '.parent_id =:parentId', [':parentId' => $params['parentId']]);
        }
        
        if (isset($params['isNullParentId'])) {
            $modelAQ->andWhere($tableName . '.parent_id IS NULL');
        }
        
        if (isset($params['isNotNullParentId'])) {
            $modelAQ->andWhere($tableName . '.parent_id IS NOT NULL');
        }
        
        if (isset($params['inId'])) {
            $modelAQ->andWhere(['IN', $tableName . '.id', $params['inId']]);
        }

        if (isset($params['stateCode']) && $params['stateCode'] > 0) {
            $modelAQ->andWhere($tableName . '.state_code =:stateCode', [':stateCode' => $params['stateCode']]);
        }
        
        if (isset($params['status'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['status']]);
        }
        
        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere($tableName . '.is_deleted =:isDeleted', [':isDeleted' => $params['isDeleted']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function getUniversityDropdown($params = [])
    {

        $queryParams = [
            'selectCols' => [
                'mst_university.id', 'mst_university.name'
            ],
            // 'forceCache' => TRUE,
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            'orderBy' => ['mst_university.name' => SORT_ASC]
        ];


        $districtModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($districtModel, 'id', 'name');
        return $list;
    }
    
    public static function getUniversityDropdownByParentId($params = [])
    {

        $queryParams = [
            'selectCols' => [
                'mst_university.id', 'mst_university.name'
            ],
            'forceCache' => TRUE,
            'inId' => [self::BOARD, self::GOVT_RECOGNIZED_INSTITUTE, self::RECOGNIZED_INSTITUTE_BY_NCTE, self::NCERT, self::UNIVERSITY],
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            'status' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
            'orderBy' => ['mst_university.name' => SORT_ASC]
        ];


        $districtModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($districtModel, 'id', 'name');
        return $list;
    }
    
    public static function getName($id, $params = [])
    {
        if($id == NULL){
            return '';
        }
        $data = self::findById($id, $params);
        if(!empty($data)){
            return $data['name'];
        }
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
