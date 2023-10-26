<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstPostQualification as BaseMstPostQualification;

/**
 * Description of MstPostQualification
 *
 * @author Amit Handa
 */
class MstPostQualification extends BaseMstPostQualification
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
        
        if (isset($params['qualificationId'])) {
            $modelAQ->andWhere($tableName . '.qualification_id =:qualificationId', [':qualificationId' => $params['qualificationId']]);
        }
        
        if (isset($params['subjectId'])) {
            $modelAQ->andWhere($tableName . '.subject_id =:subjectId', [':subjectId' => $params['subjectId']]);
        }
        
        if (isset($params['subjectIds'])) {
            $modelAQ->andWhere(['IN', $tableName . '.subject_id', $params['subjectIds']]);
        }
        
        if (isset($params['postIds'])) {
            $modelAQ->andWhere(['IN', $tableName . '.post_id', $params['postIds']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }
        
        if (isset($params['joinWithQualification']) && in_array($params['joinWithQualification'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithQualification']}('mst_qualification', 'mst_qualification.id = mst_post_qualification.qualification_id');

            if (isset($params['isActive'])) {
                $modelAQ->andWhere('mst_qualification.is_active = :isActive', [':isActive' => $params['isActive']]);
            }

            if (isset($params['isDeleted'])) {
                $modelAQ->andWhere('mst_qualification.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
            }
        }
        
        if (isset($params['joinWithUniversity']) && in_array($params['joinWithUniversity'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithUniversity']}('mst_university', 'mst_university.id = mst_post_qualification.university_id');
            
            if (isset($params['isActive'])) {
                $modelAQ->andWhere('mst_university.is_active = :isActive', [':isActive' => $params['isActive']]);
            }

            if (isset($params['isDeleted'])) {
                $modelAQ->andWhere('mst_university.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
            }
        }
        
        if (isset($params['joinWithSubject']) && in_array($params['joinWithSubject'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithSubject']}('mst_subject', 'mst_subject.id = mst_post_qualification.subject_id');

            if (isset($params['isActive'])) {
                $modelAQ->andWhere('mst_subject.is_active = :isActive', [':isActive' => $params['isActive']]);
            }

            if (isset($params['isDeleted'])) {
                $modelAQ->andWhere('mst_subject.is_deleted = :isDeleted', [':isDeleted' => $params['isDeleted']]);
            }
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
    
    public static function findByQualificationId($qualificationId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['qualificationId' => $qualificationId], $params));
    }
    
    public static function findBySubjectId($subjectId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['subjectId' => $subjectId], $params));
    }

}
