<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ListType as BaseListType;

class ListType extends BaseListType
{

    const CATEGORY = 'CATEGORY';
    const RELIGION = 'RELIGION';
    const NATIONALITY = 'NATIONALITY';
    const DISADVANTAGED_GROUP = 'DISADVANTAGED_GROUP';
    const DISABILITY = 'DISABILITY';
    const RESIDENCE_PLACE = 'RESIDENCE_PLACE';
    const GEOGRAPHICAL_AREA = 'GEOGRAPHICAL_AREA';
    const MOTHER_TONUGE = 'MOTHER_TONUGE';
    const CURRICULAR = 'CURRICULAR';
    const EMPLOYMENT = 'EMPLOYMENT';
    const ANNUAL_FAMILY_INCOME = 'ANNUAL_FAMILY_INCOME';
    const EDUCATIONAL_QUAL = 'EDUCATIONAL_QUAL';
    const ORPHAN = 'ORPHAN';
    const ECONOMICALLY_BACK = 'ECONOMICALLY_BACK';
    const PREVIOUS_QUAL = 'PREVIOUS_QUAL';
    const EX_SERVICEMAN = 'EX_SERVICEMAN';
    const PART_ADMISSION = 'PART_ADMISSION';
    const SUB_PASS = 'SUB_PASS';
    const SUB_FAIL = 'SUB_FAIL';
    const RES_PREV_PASSED = 'RES_PREV_PASSED';
    const RES_PENALTY_TAG = 'RES_PENALTY_TAG';
    const RES_PASS = 'RES_PASS';
    const RES_FAIL = 'RES_FAIL';
    const RES_PASSED_APPEARED = 'RES_PASSED_APPEARED';
    const RES_RW_RL = 'RES_RW_RL';
    const RES_ADDITIONAL_EXAM = 'RES_ADDITIONAL_EXAM';
    const SUB_ABSENTconst = 'SUB_ABSENT';

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
            $modelAQ->select('list_type.*');
        }

        if (isset($params['type'])) {
            $modelAQ->andWhere('list_type.type = :type', [':type' => $params['type']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere('list_type.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['name'])) {
            $modelAQ->andWhere('list_type.name =:name', [':name' => $params['name']]);
        }

        return (new \common\models\caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }

    public static function findByType($type, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['type' => $type], $params));
    }

    public static function getListTypeDropdownByType($type, $params = [])
    {
        $queryParams = [
            'selectCols' => [
                'list_type.id', 'list_type.name'
            ],
            'type' => $type,
            'status' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['list_type.name' => SORT_ASC],
        ];
        $listTypeModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($listTypeModel, 'id', 'name');
        return $list;
    }

}
