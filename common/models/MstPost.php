<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstPost as BaseMstPost;

/**
 * Description of MstPost
 *
 * @author Amit Handa
 */
class MstPost extends BaseMstPost
{

    const IS_ACTIVE_YES = 1;
    const IS_ACTIVE_NO = 0;
    // master post
    const MASTER_POST = 0;
    const MASTER_POST_GUID = 'c02f888c-fef0-11e9-8967-fc017c9a1ba8';
    // classified post
    const CLASSIFIED_POST_ID = 1;
    const CLASSIFIED_POST_GUID = '41fbc941-f9c1-11ea-8b1a-00ff81b75f6g';
    
    // POST IDS
    const DRIVER = 17;
    
    
    
    const HINDI_INTERMEDIATE_LABEL = 'If Sanskrit Subject not studied at degree or graduate level than have you passed Intermediate / 12th with Sanskrit as a subject.';
    const SANSKRIT_INTERMEDIATE_LABEL = 'If Hindi Subject not studied at degree or graduate level than have you passed Intermediate / 12th with Hindi as a subject.';
    const ENGLISH_INTERMEDIATE_LABEL = 'English should be mandatory in intermediate.';
    const CCC_CERTIFICATE_LABEL = 'Computer should be mandatory in CCC.';

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
            \components\behaviors\GuidBehavior::className(),
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['code', 'title']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'code' => 'cleanEncodeUTF8',
                        'title' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'code' => 'cleanEncodeUTF8',
                        'title' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    public static function findByParams($params = [])
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
        
        if (isset($params['classifiedId'])) {
            $modelAQ->andWhere($tableName . '.classified_id =:classifiedId', [':classifiedId' => $params['classifiedId']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['inId'])) {
            $modelAQ->andWhere(['IN', $tableName . '.id', $params['inId']]);
        }
        
        if (isset($params['joinWithMstClassified']) && in_array($params['joinWithMstClassified'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithMstClassified']}('mst_classified', 'mst_classified.id = mst_post.classified_id');
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByClassifiedId($classifiedId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['classifiedId' => $classifiedId], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function classifiedList()
    {

        $model = self::findByParams([
                    'returnAll' => caching\ModelCache::RETURN_ALL,
                    'isActive' => caching\ModelCache::IS_ACTIVE_YES
        ]);
        return $model;
    }
    
    public static function getPostDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_post.id', 'mst_post.title', 'mst_post.code'
            ],
            // 'forceCache' => TRUE,
            'isActive' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
            'resultCount' => \common\models\caching\ModelCache::RETURN_ALL,
            'orderBy' => ['mst_post.display_order' => SORT_ASC]
        ];

        if (isset($params['id']) && !empty($params['id'])) {
            $queryParams['id'] = $params['id'];
        }
        $model = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = [];
        foreach ($model as $key => $value) {
            $code = !empty($value['code']) ? ' Code - ' . $value['code'] : '';
            $list[$value['id']] = $value['title'] . $code;
        }
        return $list;
    }
    
    public static function getTitle($id, $params = [])
    {
        if($id == NULL){
            return '';
        }
        $data = self::findById($id, $params);
        if(!empty($data)){
            $text = $data['title'];
            $text .= !empty($data['code']) ? " Code - " . $data['code'] : '';
            return $text;
        }
    }
    
    public static function getTitleForPdf($id, $params = [])
    {
        if($id == NULL){
            return '';
        }
        $data = self::findById($id, $params);
        if(!empty($data)){
            return $data['title']." Code - ".$data['code'];
        }
    }
    
    public static function isPaymentDateEnable($id, $params = [])
    {
        $qp = [
            'joinWithMstClassified' => 'innerJoin',
            'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ];
        $model = self::findById($id, \yii\helpers\ArrayHelper::merge($qp, $params));
        if ($model === NULL) {
            return false;
        }
        
        $date = strtotime(date('d-m-Y'));
        if (!empty($model->classified->payment_end_date) && $date <= strtotime($model->classified->payment_end_date)) {
            return true;
        }

        return false;
    }

}
