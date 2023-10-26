<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstListType as BaseMstListType;
/**
 * Description of MstListType
 *
 * @author Amit Handa
 */
class MstListType extends  BaseMstListType
{
    // parent list type
    const SOCIAL_CATEGORY = 1;
    const EMPLOYER_TYPE = 2;
    const DISABILITY = 3;
    const NATIONALITY = 4;
    const ISSUING_AUTHORITY = 5;
    const RESERVATION_CATEGORY = 6;
    const COUNCIL = 8;
    const DEPARTMENT = 9;
    const EMPLOYMENT_OFFICE = 10;
    const EXPERIENCE_TYPE = 105;
    const PARENT_QUALIFICATION = 146;
    const FATHER_OCCUPATION = 154;
    const MOTHER_OCCUPATION = 155;
    const IDENTITY_TYPE = 167;
    const EMPLOYMENT_TYPE = 173;
    const EMPLOYMENT_NATURE = 176;
    
    // child list type
    const NOT_APPLICABLE = 25;
    const UNRESERVED_GENERAL = 11;
    const ST = 12;
    const EWS = 13;
    const SC = 14;
    const OBC = 15;
    
    // sub category
    const SUB_CATEGORY_NA = 58;
    const SUB_CATEGORY_UW = 59;
    const SUB_CATEGORY_DFF = 60;
    const SUB_CATEGORY_EX = 61;
    const SUB_CATEGORY_PH = 147;
    
    //Are you Differently Abled Person
    const PH_PD = 37;
    const PH_PB = 35;
    const PH_OA = 30;
    const PH_OL = 29;
    // Experience Type
    const EXPERIENCE_TYPE_DRIVER = 212;
    //Identity Type
    const PASSPORT = 168;
    const PAN = 170;
    const DRIVING_LICENSE = 172;
    const AADHAR = 219;
    const VOTER_ID = 220;
    const RASHAN_CARD = 221;
    
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

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select('mst_list_type.*');
        }
        
        if (isset($params['parentId'])) {
            $modelAQ->andWhere('mst_list_type.parent_id =:parentId', [':parentId' => $params['parentId']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere('mst_list_type.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['name'])) {
            $modelAQ->andWhere('mst_list_type.name =:name', [':name' => $params['name']]);
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere('mst_list_type.id =:id', [':id' => $params['id']]);
        }
        
        if (isset($params['recordIsNull']) && !empty($params['recordIsNull']) && isset($params['recordIsNull']['column']) && !empty($params['recordIsNull']['column'])) {
            $modelAQ->andWhere("{$params['recordIsNull']['column']} IS NULL");
        }

        if (isset($params['recordIsNotNull']) && !empty($params['recordIsNotNull']) && isset($params['recordIsNotNull']['column']) && !empty($params['recordIsNotNull']['column'])) {
            $modelAQ->andWhere("{$params['recordIsNotNull']['column']} IS NOT NULL");
        }
        
        if (isset($params['isActive'])) {
            $modelAQ->andWhere('mst_list_type.is_active =:isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere('mst_list_type.is_deleted =:isDeleted', [':isDeleted' => $params['isDeleted']]);
        }

        return (new \common\models\caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

   
    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }
    
    public static function findMstListTypeModel($params = [])
    {
        return self::findByParams($params);
    }
    
    public static function getListTypeDropdownByParentId($parentId, $params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_list_type.id', 'mst_list_type.name'
            ],
            'parentId' => $parentId,
            'isActive' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['mst_list_type.display_order' => SORT_ASC],
        ];
        $listTypeModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        if (isset($params['isName']) && $params['isName']) {
            $list = \yii\helpers\ArrayHelper::map($listTypeModel, 'name', 'name');
        }
        $list = \yii\helpers\ArrayHelper::map($listTypeModel, 'id', 'name');
        return $list;
    }
    
    public static function selectTypeList($key = null)
    {
        $list = [
            \frontend\models\RegistrationForm::SELECT_TYPE_NO => 'No',
            \frontend\models\RegistrationForm::SELECT_TYPE_YES => 'Yes',
        ];

        return (isset($key)) ? (isset($list[$key]) ? $list[$key] : $key) : $list;
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
