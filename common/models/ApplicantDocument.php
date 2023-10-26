<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantDocument as BaseApplicantDocument;

/**
 * Description of ApplicantDocument
 *
 * @author Amit Handa
 */
class ApplicantDocument extends BaseApplicantDocument
{

    const TYPE_USER_PHOTO = 1;
    const TYPE_USER_SIGNATURE = 2;
    const TYPE_USER_BIRTH_CERTIFICATE = 3;
    const TYPE_USER_CASTE_CERTIFICATE = 4;
    const TYPE_USER_EMPLOYMENT_CERTIFICATE = 5;

     public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on']
                ],
            ]
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

        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
        }
        
        if (isset($params['mediaId'])) {
            $modelAQ->andWhere($tableName . '.media_id =:mediaId', [':mediaId' => $params['mediaId']]);
        }
        
        if (isset($params['joinWithMedia']) && in_array($params['joinWithMedia'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithMedia']}('media', 'media.id = applicant_document.media_id');
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByApplicantPostId($applicantPostId, $params = [])
    {
        $queryParams = ['applicantPostId' => $applicantPostId];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }

}
