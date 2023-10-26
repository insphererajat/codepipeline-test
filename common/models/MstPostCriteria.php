<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstPostCriteria as BaseMstPostCriteria;

/**
 * Description of MstPostCriteria
 *
 * @author Amit Handa
 */
class MstPostCriteria extends BaseMstPostCriteria
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
            \components\behaviors\GuidBehavior::className(),
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['criteria_type', 'criteria_vlue']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'criteria_type' => 'cleanEncodeUTF8',
                        'criteria_vlue' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'criteria_type' => 'cleanEncodeUTF8',
                        'criteria_vlue' => 'cleanEncodeUTF8'
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
        
        if (isset($params['postId'])) {
            $modelAQ->andWhere($tableName . '.post_id =:postId', [':postId' => $params['postId']]);
        }
        
        if (isset($params['criteriaType'])) {
            $modelAQ->andWhere($tableName . '.criteria_type =:criteriaType', [':criteriaType' => $params['criteriaType']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['postIds'])) {
            $modelAQ->andWhere(['IN', $tableName . '.post_id', $params['postIds']]);
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
    
    public static function findByPostId($postId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['postId' => $postId], $params));
    }
    
    public static function findByCriteriaType($criteriaType, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['criteriaType' => $criteriaType], $params));
    }

}
