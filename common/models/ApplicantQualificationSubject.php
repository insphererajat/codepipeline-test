<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantQualificationSubject as BaseApplicantQualificationSubject;

/**
 * Description of ApplicantQualificationSubject
 *
 * @author Amit Handa
 */
class ApplicantQualificationSubject extends BaseApplicantQualificationSubject
{
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

        if (isset($params['applicantQualificationId'])) {
            $modelAQ->andWhere($tableName . '.applicant_qualification_id =:applicantQualificationId', [':applicantQualificationId' => $params['applicantQualificationId']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByApplicantQualificationId($applicantQualificationId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantQualificationId' => $applicantQualificationId], $params));
    }
    
    public function saveMultipleMedia($applicantQualificationId, $dataArr)
    {
        if (!isset($dataArr) || empty($dataArr)) {
            return FALSE;
        }
        try {
            ApplicantQualificationSubject::deleteAll('applicant_qualification_id=:applicantQualificationId', [':applicantQualificationId' => $applicantQualificationId]);
            foreach ($dataArr as $data) {
                $findExistRecord = ApplicantQualificationSubject::findOne(['applicant_qualification_id' => $applicantQualificationId, 'subject_id' => $data]);
                if ($findExistRecord == NULL) {
                    $model = new ApplicantQualificationSubject;
                    $model->isNewRecord = TRUE;
                    $model->applicant_qualification_id = $applicantQualificationId;
                    $model->subject_id = $data['subject_id'];
                    $model->save();
                }
            }
            return TRUE;
        } catch (\Exception $ex) {
            throw $ex;
        }
        return FALSE;
    }
    
    public static function getAllSubjectsByApplicantQualificationId($applicantQualificationId, $params = [])
    {
        $applicantQualification = ApplicantQualification::findById($applicantQualificationId);
        $queryParams = [
            'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT,
            'returnAll' => caching\ModelCache::RETURN_ALL
        ];
        $qualificationSubjects = ApplicantQualificationSubject::findByApplicantQualificationId($applicantQualificationId, \yii\helpers\ArrayHelper::merge($queryParams, $params));
        
        $subjects = "";
        foreach ($qualificationSubjects as $key => $subjectModel) {
            if($applicantQualification['qualification_type_id'] != MstQualification::PARENT_12 && $key > 2) {
                continue;
            }
            if($applicantQualification['qualification_type_id'] == MstQualification::POST_GRADUATE && $key > 0) {
                continue;
            }
            $subjects .= $subjectModel->subject->name. ", ";
        }
        
        return !empty($subjects) ? rtrim($subjects, ", ") : "";
    }
}
