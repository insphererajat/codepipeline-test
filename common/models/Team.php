<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\Team as BaseTeam;
use yii\helpers\ArrayHelper;

/**
 * Description of Team
 *
 * @author Amit Handa
 */
class Team extends BaseTeam
{


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


    private static function findByParams($params = [])
    {
        $modelAQ = self::find();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select('team.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere('team.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere('team.guid =:guid', [':guid' => $params['guid']]);
        }
        
      
        if (isset($params['isActive'])) {
            $modelAQ->andWhere('team.is_active = :isActive', [':isActive' => $params['isActive']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere('team.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
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

}
