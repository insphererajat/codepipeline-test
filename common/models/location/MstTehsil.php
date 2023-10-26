<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\location;

use Yii;
use common\models\base\MstTehsil as BaseMstTehsil;

/**
 * Description of MstTehsil
 *
 * @author  Amit Handa
 */
class MstTehsil extends BaseMstTehsil
{
    
    const OTHER = 0;
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

        $parentRules[] = [['code', 'is_active'], 'required'];
        $parentRules[] = ['code', 'string', 'max' => 11];

        return $parentRules;
    }

    public function attributeLabels()
    {
        $baseAttr = parent::attributeLabels();
        $myArrs = [
            'state_code' => 'State',
            'district_code' => 'District',
            'name' => 'Tehsil Name',
            'code' => 'Tehsil Code',
            'is_active' => 'Status',
        ];
        return \yii\helpers\ArrayHelper::merge($baseAttr, $myArrs);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (!empty($this->district_code)) {
                $districtModel = MstDistrict::findByCode($this->district_code, ['stateCode' => $this->state_code, 'exists' => TRUE]);
                if (!$districtModel) {
                    $this->addError('code', 'State district not exists.');
                }
               
                $this->code = $this->district_code . '' . $this->code;
                
//                if ($this->code > 2147483647) {
//                    $exceedLen = strlen($this->code) - strlen($this->code);
//                    if ($exceedLen > 9) {
//                        $this->addError('code', 'District code length has been exceeded.');
//                    }
//                    else {
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
            $modelAQ->select('mst_tehsil.*');
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere('mst_tehsil.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere('mst_tehsil.is_active = :isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['stateCode'])) {
            $modelAQ->andWhere('mst_tehsil.state_code = :stateCode', [':stateCode' => $params['stateCode']]);
        }
        
        if (isset($params['districtCode'])) {
            $modelAQ->andWhere('mst_tehsil.district_code = :districtCode', [':districtCode' => $params['districtCode']]);
        }
        
        if (isset($params['code'])) {
            $modelAQ->andWhere('mst_tehsil.code = :code', [':code' => $params['code']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere('mst_tehsil.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
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

    public static function getTehsilList($params = [])
    {
        return self::findByParams($params);
    }

    public static function getTehsilDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_tehsil.code', 'mst_tehsil.name'
            ],
            // 'forceCache' => TRUE,
            'is_active' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            'orderBy' => ['mst_tehsil.name' => SORT_ASC]
        ];

        $tehsilModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($tehsilModel, 'code', 'name');
        $list = \yii\helpers\ArrayHelper::merge($list, [self::OTHER => 'Other']);
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
