<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantFee as BaseApplicantFee;
/**
 * Description of ApplicantFee
 *
 * @author Amit Handa
 */
class ApplicantFee extends BaseApplicantFee
{
    const MODULE_APPLICATION = 'APPLICATION';
    const MODULE_ESERVICE = 'ESERVICE';
    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;
    const FREE_FEE = 0.00;
    
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
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => (int) $params['id']]);
        }

        if (isset($params['applicantId'])) {
            $modelAQ->andWhere($tableName . '.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
        }
        
        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
        }

        if (isset($params['module'])) {
            $modelAQ->andWhere($tableName . '.module =:module', [':module' => $params['module']]);
        }
        
        if (isset($params['payStatus'])) {
            $modelAQ->andWhere($tableName . '.status =:payStatus', [':payStatus' => $params['payStatus']]);
        }
        
        if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant_post.id = applicant_fee.applicant_post_id');
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByApplicantId($applicantId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantId' => $applicantId], $params));
    }
    
    public static function findByApplicantPostId($applicantPostId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId], $params));
    }
    
    public static function findByModule($module, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['module' => $module], $params));
    }
    
    public static function getClassifiedName($id, $params = [])
    {
        $qr = [
            'selectCols' => ['applicant_post.classified_id'],
            'joinWithApplicantPost' => 'innerJoin'
        ];
        $applicantPost = self::findById($id, \yii\helpers\ArrayHelper::merge($qr, $params));
        if ($applicantPost != null) {
            return MstClassified::getTitle($applicantPost['classified_id']);
        }
        return '';
    }
}
