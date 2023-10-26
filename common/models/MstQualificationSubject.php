<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstQualificationSubject as BaseMstQualificationSubject;

/**
 * Description of MstQualificationSubject
 *
 * @author Amit Handa
 */
class MstQualificationSubject extends BaseMstQualificationSubject
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on'],
                ],
            ],
        ];
    }

    public function saveSubjects($qualificationId, $subjects)
    {
        try {

            self::deleteAll('qualification_id =:qualificationId', [':qualificationId' => $qualificationId]);

            foreach ($subjects as $subject) {
                $model = new MstQualificationSubject();
                $model->isNewRecord = TRUE;
                $model->qualification_id = $qualificationId;
                $model->subject_id = $subject;
                $model->save();
            }
            return TRUE;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
        return FALSE;
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

        if (isset($params['qualificationId'])) {
            $modelAQ->andWhere($tableName . '.qualification_id =:qualificationId', [':qualificationId' => $params['qualificationId']]);
        }

        if (isset($params['joinWithSubject']) && in_array($params['joinWithSubject'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithSubject']}('mst_subject', 'mst_subject.id = mst_qualification_subject.subject_id');

            if (isset($params['isActive'])) {
                $modelAQ->andWhere('mst_subject.is_active = :isActive', [':isActive' => $params['isActive']]);
            }

            if (isset($params['isDeleted'])) {
                $modelAQ->andWhere('mst_subject.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
            }
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
    
    public static function findByQualificationId($qualificationId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['qualificationId' => $qualificationId], $params));
    }
    
    public static function getQualificationSubjectDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_qualification_subject.subject_id', 'mst_subject.name'
            ],
            'joinWithSubject' => 'innerJoin',
            'status' => caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => caching\ModelCache::IS_DELETED_NO,
            'resultCount' => caching\ModelCache::RETURN_ALL,
            'orderBy' => ['mst_subject.name' => SORT_ASC],
        ];
        $qualificationModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($qualificationModel, 'subject_id', 'name');
        return $list;
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
