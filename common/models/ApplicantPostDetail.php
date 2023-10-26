<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantPostDetail as BaseApplicantPostDetail;

/**
 * Description of ApplicantPostDetail
 *
 * @author Amit Handa <insphere.amit@gmail.com.com>
 */
class ApplicantPostDetail extends BaseApplicantPostDetail
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on']
                ],
            ],
            \components\behaviors\GuidBehavior::className(),
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

        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
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
    
    public static function findByApplicantPostId($applicantPostId, $params = [])
    {
        $queryParams = [
            'applicantPostId' => $applicantPostId
        ];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public function create($data)
    {
        try {
            $model = new ApplicantPostDetail;
            $model->loadDefaultValues(TRUE);
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if (!$model->save()) {
                return FALSE;
            }
        }
        catch (\Exception $ex) {
            return FALSE;
        }
        return $model->id;
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
