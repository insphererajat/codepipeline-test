<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstSubject as BaseMstSubject;

class MstSubject extends BaseMstSubject
{

    const HINDI_LITERATURE = 1032;
    const SANSKRIT = 102;
    const SANSKRIT_LITERATURE = 1031;
    const HINDI = 8;
    const ENGLISH = 101;
    const COMPUTER = 364;
    const COMMERCE = 4;
    
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
            \components\behaviors\GuidBehavior::className(),
        ];
    }
    
    public function rules()
    {
        $baseRules = parent::rules();
        $myRules = [
            [['name', 'is_active'], 'required'],
            ['name', 'string', 'max' => 255],
            ['is_active', 'integer'],
            ['is_deleted', 'default', 'value' => \common\models\caching\ModelCache::IS_DELETED_NO]
        ];

        return \yii\helpers\ArrayHelper::merge($baseRules, $myRules);
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

        if (isset($params['type'])) {
            $modelAQ->andWhere($tableName . '.type = :type', [':type' => $params['type']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['name'])) {
            $modelAQ->andWhere($tableName . '.name =:name', [':name' => $params['name']]);
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

    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }

    public static function getSubjectDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_subject.id', 'mst_subject.name'
            ],
            'status' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['mst_subject.name' => SORT_ASC],
        ];
        $listTypeModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($listTypeModel, 'id', 'name');
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
