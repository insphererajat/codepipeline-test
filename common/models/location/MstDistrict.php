<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\location;

use Yii;
use common\models\base\MstDistrict as BaseMstDistrict;

/**
 * Description of MstDistrict
 *
 * @author  Amit Handa
 */
class MstDistrict extends BaseMstDistrict
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
            \components\behaviors\GuidBehavior::className()
        ];
    }
    
    public function rules()
    {
        $parentRules = parent::rules();

        $parentRules = [
            [['name', 'code', 'state_code','is_active'], 'required'],
            ['name', 'string', 'max' => 100],
            ['code', 'string', 'max' => 6],
            ['state_code', 'string', 'max' => 4],
            [['state_code'], 'match', 'pattern' => '/^(91)\d{2}$/'],
            ['is_deleted', 'default', 'value' => \common\models\caching\ModelCache::IS_DELETED_NO]
        ];

        return $parentRules;
    }

    public function attributeLabels()
    {
        $baseAttr = parent::attributeLabels();
        $myArrs = [

            'name' => 'District Name',
            'state_code' => 'State Code',
            'code' => 'District Code',
            'is_active' => 'Status'
        ];
        return \yii\helpers\ArrayHelper::merge($baseAttr, $myArrs);
    }

    public function beforeSave($insert)
    {
        
        if ($insert) {
            if (!empty($this->state_code)) {
               $stateModel = MstState::findByCode($this->state_code, ['selectCols' => 'code', 'resultFormat' => 'array']);
                if (!empty($stateModel)) {
                    $this->addError('state_code', 'State code not found.');
                }
                $this->code = $this->state_code . '' . $this->code;
//                if ($this->code > 2147483647) {
//                    $exceedLen = strlen($this->code) - strlen($this->code);
//                    if ($exceedLen > 9) {
//                        $this->addError('code', 'District code length has been exceeded.');
//                    } else {
//                        $this->addError('code', 'District code should be support int or less then "2147483647".');
//                        return false;
//                    }
//                }
            }
        }
        return parent::beforeSave($insert);
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select('mst_district.*');
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere('mst_district.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere('mst_district.is_active = :isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['stateCode'])) {
            $modelAQ->andWhere('mst_district.state_code = :stateCode', [':stateCode' => $params['stateCode']]);
        }
        if (isset($params['code'])) {
            $modelAQ->andWhere('mst_district.code = :code', [':code' => $params['code']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere('mst_district.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
        }
        
        if (isset($params['regionalCentreCode'])) {
            $modelAQ->andWhere('mst_district.regional_centre_code = :regionalCentreCode', [':regionalCentreCode' => $params['regionalCentreCode']]);
        }

        return (new \common\models\caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function findByCode($code, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['code' => $code], $params));
    }

    public static function getDitsrictList($params = [])
    {
        return self::findByParams($params);
    }

    public static function getDistrictDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_district.code', 'mst_district.name'
            ],
            // 'forceCache' => TRUE,
            'is_active' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            'orderBy' => ['mst_district.name' => SORT_ASC]
        ];

        if (isset($params['code']) && !empty($params['code'])) {
            $queryParams['code'] = $params['code'];
        }
        $districtModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($districtModel, 'code', 'name');
        return $list;
    }

    public static function getName($code, $params = [])
    {
        if($code == NULL){
            return '';
        }
        $data = self::findByCode($code, $params);
        if(!empty($data)){
            return $data['name'];
        }
    }

}
