<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantQualification as BaseApplicantQualification;

/**
 * Description of ApplicantQualification
 *
 * @author Amit Handa
 */
class ApplicantQualification extends BaseApplicantQualification
{
    
    //DivisionList
    const DIVISION_FIRST = 1;
    const DIVISION_SECOND = 2;
    const DIVISION_THIRD = 3;
    const DIVISION_NOT_APPLICABLE = 4;
    // Result Type
    const MARKS = 'MARKS';
    const CGPA = 'CGPA';
    const GRADE = 'GRADE';
    
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
                ]
            ],
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['council_registration_no', 'grade', 'remarks', 'mphil_phd_registration_no', 'mphil_phd_project_title']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'council_registration_no' => 'cleanEncodeUTF8',
                        'grade' => 'cleanEncodeUTF8',
                        'remarks' => 'cleanEncodeUTF8',
                        'mphil_phd_registration_no' => 'cleanEncodeUTF8',
                        'mphil_phd_project_title' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'council_registration_no' => 'cleanEncodeUTF8',
                        'grade' => 'cleanEncodeUTF8',
                        'remarks' => 'cleanEncodeUTF8',
                        'mphil_phd_registration_no' => 'cleanEncodeUTF8',
                        'mphil_phd_project_title' => 'cleanEncodeUTF8',
                    ]
                ]
            ]
        ];
    }

    public static function getDivisionList($key = null)
    {
        $list = [self::DIVISION_FIRST => 'FIRST', self::DIVISION_SECOND => 'SECOND', self::DIVISION_THIRD => 'THIRD', self::DIVISION_NOT_APPLICABLE => 'Not Applicable'];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getResultType($key = null)
    {
        $list = [self::MARKS => 'MARKS', self::CGPA => 'CGPA', self::GRADE => 'GRADE'];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
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

        if (isset($params['qualificationTypeId'])) {
            $modelAQ->andWhere($tableName . '.qualification_type_id =:qualificationTypeId', [':qualificationTypeId' => $params['qualificationTypeId']]);
        }
        
        if (isset($params['qualificationDegreeId'])) {
            $modelAQ->andWhere($tableName . '.qualification_degree_id =:qualificationDegreeId', [':qualificationDegreeId' => $params['qualificationDegreeId']]);
        }
        
        if (isset($params['qualificationYear'])) {
            $modelAQ->andWhere($tableName . '.qualification_year =:qualificationYear', [':qualificationYear' => $params['qualificationYear']]);
        }
        
        if (isset($params['inQualificationTypeId'])) {
            $modelAQ->andWhere(['IN', $tableName . '.qualification_type_id', $params['inQualificationTypeId']]);
        }

        if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant_post.id = applicant_qualification.applicant_post_id');

            if (isset($params['applicantId'])) {
                $modelAQ->andWhere('applicant_post.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
            }
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByApplicantIdAndQualificationTypeId($applicantPostId, $qualificationId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId, 'qualificationTypeId' => $qualificationId], $params));
    }

    public static function findByApplicantPostId($applicantId, $params = [])
    {
        $queryParams = ['applicantPostId' => $applicantId];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public function beforeSave($insert) {
        
        if ($this->qualification_type_id != MstQualification::NET_SLET_SET) {
            $this->net_qualifying_date = null;
        }
        if (!\yii\helpers\ArrayHelper::isIn($this->qualification_type_id, [MstQualification::MPHILL, MstQualification::PHD])) {
            $this->mphil_phd_registration_no = null;
            $this->mphil_phd_registration_date = null;
            $this->mphil_phd_project_title = null;
        }
        if ($this->qualification_type_id != MstQualification::GRADUATE) {
            $this->council_id = null;
            $this->council_registration_date = null;
            $this->council_renewal_date = null;
            $this->council_registration_no = null;
        }

        $this->council_registration_date = !empty($this->council_registration_date) ? date('Y-m-d', strtotime($this->council_registration_date)) : null;
        $this->council_renewal_date = !empty($this->council_renewal_date) ? date('Y-m-d', strtotime($this->council_renewal_date)) : null;
        $this->date_of_marksheet = !empty($this->date_of_marksheet) ? date('Y-m-d', strtotime($this->date_of_marksheet)) : null;
        $this->net_qualifying_date = !empty($this->net_qualifying_date) ? date('Y-m-d', strtotime($this->net_qualifying_date)) : null;
        $this->mphil_phd_registration_date = !empty($this->mphil_phd_registration_date) ? date('Y-m-d', strtotime($this->mphil_phd_registration_date)) : null;
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        
        $this->council_registration_date = !empty($this->council_registration_date) ? date('d-m-Y', strtotime($this->council_registration_date)) : null;
        $this->council_renewal_date = !empty($this->council_renewal_date) ? date('d-m-Y', strtotime($this->council_renewal_date)) : null;
        $this->date_of_marksheet = !empty($this->date_of_marksheet) ? date('d-m-Y', strtotime($this->date_of_marksheet)) : null;
        $this->net_qualifying_date = !empty($this->net_qualifying_date) ? date('d-m-Y', strtotime($this->net_qualifying_date)) : null;
        $this->mphil_phd_registration_date = !empty($this->mphil_phd_registration_date) ? date('d-m-Y', strtotime($this->mphil_phd_registration_date)) : null;

        parent::afterFind();
    }
    
    public static function getYearDropdown()
    {
        $list = [];
        for ($i = date('Y'); $i >= (date('Y') - 58); $i--) {
            $list[$i] = $i;
        }
        
        return $list;
    }
    
    public static function qualificationValidation($applicantPostId)
    {
        $applicantDetail = ApplicantDetail::findByApplicantPostId($applicantPostId, [
                    'selectCols' => [new \yii\db\Expression("applicant_detail.is_exserviceman")],
        ]);

        if (isset($applicantDetail['is_exserviceman']) && $applicantDetail['is_exserviceman'] == caching\ModelCache::IS_ACTIVE_YES) {
            $model = ApplicantQualification::findByApplicantPostId($applicantPostId, [
                        'inQualificationTypeId' => [MstQualification::PARENT_10TH],
                        //'groupBy' => ['applicant_qualification.qualification_type_id'],
                        'countOnly' => true
            ]);

            if ($model >= 1) {
                return true;
            }
        } else {
            $model = ApplicantQualification::findByApplicantPostId($applicantPostId, [
                        'inQualificationTypeId' => [MstQualification::PARENT_10TH, MstQualification::PARENT_12, MstQualification::GRADUATE, MstQualification::PARENT_BED, MstQualification::DIPLOMA],
                        //'groupBy' => ['applicant_qualification.qualification_type_id'],
                        'countOnly' => true
            ]);

            // Matric and any one graduate is mandatory
            $secondCondition = ApplicantQualification::findByApplicantPostId($applicantPostId, [
                        'inQualificationTypeId' => [MstQualification::PARENT_10TH, MstQualification::GRADUATE, MstQualification::PARENT_BED],
                        //'groupBy' => ['applicant_qualification.qualification_type_id'],
                        'countOnly' => true
            ]);

            if ($model >= 3 && $secondCondition >= 2) {
                return true;
            }
        }
        return false;
    }
    
    public static function minimumQualificationValidation($applicantPostId)
    {
        $model = ApplicantQualification::findByApplicantPostId($applicantPostId, [
                    'inQualificationTypeId' => [MstQualification::PARENT_10TH],
                    //'groupBy' => ['applicant_qualification.qualification_type_id'],
                    'countOnly' => true
        ]);

        if ($model >= 1) {
            return true;
        }
        return false;
    }

}
