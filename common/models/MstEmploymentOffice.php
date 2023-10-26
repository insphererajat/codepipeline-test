<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstEmploymentOffice as BaseMstEmploymentOffice;

/**
 * Description of MstEmploymentOffice
 *
 * @author Ravi Sikarwar
 */
class MstEmploymentOffice extends BaseMstEmploymentOffice
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
    
    public static function getEmploymentOfficeDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_employment_office.id', 'mst_employment_office.name'
            ],
            'status' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['mst_employment_office.id' => SORT_ASC],
        ];
        $model = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($model, 'id', 'name');
        return $list;
    }

}
