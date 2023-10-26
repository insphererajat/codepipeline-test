<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantEmployment as BaseApplicantEmployment;

/**
 * Description of ApplicantEmployment
 *
 * @author Amit Handa
 */
class ApplicantEmployment extends BaseApplicantEmployment
{
    const EMPLOYMENT_TYPE_PAST = 174;
    const EMPLOYMENT_TYPE_PRESENT = 175;

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ]
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['employer', 'office_name', 'designation']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'employer' => 'cleanEncodeUTF8',
                        'office_name' => 'cleanEncodeUTF8',
                        'designation' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'employer' => 'cleanEncodeUTF8',
                        'office_name' => 'cleanEncodeUTF8',
                        'designation' => 'cleanEncodeUTF8'
                    ]
                ]
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
        
        if (isset($params['experienceTypeId'])) {
            $modelAQ->andWhere($tableName . '.experience_type_id =:experienceTypeId', [':experienceTypeId' => $params['experienceTypeId']]);
        }
        
        if (isset($params['inExperienceTypeId'])) {
            $modelAQ->andWhere(['IN', $tableName . '.experience_type_id', $params['inExperienceTypeId']]);
        }

        if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant_post.id = applicant_employment.applicant_post_id');

            if (isset($params['applicantId'])) {
                $modelAQ->andWhere('applicant_post.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
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

    public static function findByApplicantIdAndQualificationId($applicantPostId, $qualificationId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId, 'qualificationId' => $qualificationId], $params));
    }

    public static function findByApplicantPostId($applicantId, $params = [])
    {
        $queryParams = ['applicantPostId' => $applicantId];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public function beforeSave($insert) {
        
        $this->start_date = !empty($this->start_date) ? date('Y-m-d', strtotime($this->start_date)) : null;
        $this->end_date = !empty($this->end_date) ? date('Y-m-d', strtotime($this->end_date)) : null;

        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = strip_tags($this->{$key});
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        
        $this->start_date = !empty($this->start_date) ? date('d-m-Y', strtotime($this->start_date)) : null;
        $this->end_date = !empty($this->end_date) ? date('d-m-Y', strtotime($this->end_date)) : null;

        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = htmlentities($attribute);
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        parent::afterFind();
    }

}
