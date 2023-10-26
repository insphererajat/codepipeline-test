<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\location;

use Yii;
use common\models\base\MstState as BaseMstState;
/**
 * Description of MstState
 *
 * @author Amit Handa
 */
class MstState extends BaseMstState
{
    const STATE_CODE_UK = 9105;
    
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
        $parentRules = parent::rules();

        $myRules = [
            [['name', 'code', 'is_active'], 'required'],
            ['name', 'string', 'max' => 100],
            [['code'], 'unique'],
            ['is_deleted', 'default', 'value' => \common\models\caching\ModelCache::IS_DELETED_NO],
        ];
        return \yii\helpers\ArrayHelper::merge($parentRules, $myRules);
    }

    public function attributeLabels()
    {
        $baseAttr = parent::attributeLabels();
        $myArrs = [
            'country_code' => 'Country',
            'name' => 'State Name',
            'code' => 'State Code',
            'is_active' => 'Status'
        ];
        return \yii\helpers\ArrayHelper::merge($baseAttr, $myArrs);
    }

    public function beforeSave($insert)
    {

        if ($insert) {
            if (!empty($this->code)) {
                $this->code = 91 . '' . $this->code;
                $codeLen = strlen($this->code);
                if ($codeLen > 4) {
                    $this->addError('code', 'State code must contain only four digits');
                    return false;
                }
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
            $modelAQ->select('mst_state.*');
        }

         if (isset($params['code'])) {
            $modelAQ->andWhere('mst_state.code = :code', [':code' => $params['code']]);
        }
        
        if (isset($params['guid'])) {
            $modelAQ->andWhere('mst_state.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['countryCode'])) {
            $modelAQ->andWhere('mst_state.country_code =:countryCode', [':countryCode' => $params['countryCode']]);
        }

        if (isset($params['isAadhaarState'])) {
            $modelAQ->andWhere('mst_state.is_aadhaar_state = :isAadhaarState', [':isAadhaarState' => $params['isAadhaarState']]);
        }

        if (isset($params['joinWithApplicantAddress']) && in_array($params['joinWithApplicantAddress'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantAddress']}('applicant_address', 'mst_state.code = applicant_address.state_code');

            if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
                $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant_post.id = applicant_address.applicant_post_id');

                if (isset($params['postId'])) {
                    $modelAQ->andWhere('applicant_post.post_id =:postId', [':postId' => $params['postId']]);
                }
                
                if (isset($params['notPostId'])) {
                    $modelAQ->andWhere('applicant_post.post_id !=:notPostId', [':notPostId' => $params['notPostId']]);
                }

                if (isset($params['inApplicationStatus'])) {
                    $modelAQ->andWhere(['IN', 'applicant_post.application_status', $params['inApplicationStatus']]);
                }
            }
            if (isset($params['addressType'])) {
                $modelAQ->andWhere('applicant_address.address_type =:addressType', [':addressType' => $params['addressType']]);
            }
        }
        
        if (isset($params['isActive'])) {
            $modelAQ->andWhere('mst_state.is_active =:isActive', [':isActive' => $params['isActive']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere('mst_state.is_deleted =:isDeleted', [':isDeleted' => $params['isDeleted']]);
        }
        
        return (new \common\models\caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByKeys($params = [])
    {
        return self::findByParams($params);
    }
   
    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }
    
     public static function findByCode($code, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['code' => $code], $params));
    }
    
    public static function getStateDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_state.code', 'mst_state.name'
            ],
            'status' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['mst_state.name' => SORT_ASC],
        ];
        $stateModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($stateModel, 'code', 'name');
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
    
    public static function getStateCodeByName($name)
    {
        $data = self::find()->where('name LIKE :query')
                ->addParams([':query'=> '%'.$name.'%'])->one();
        if(!empty($data)){
            return $data['code'];
        }
        return '';
    }

}
