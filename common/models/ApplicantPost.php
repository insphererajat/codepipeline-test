<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantPost as BaseApplicantPost;

/**
 * Description of ApplicantPost
 *
 * @author Amit Handa
 */
class ApplicantPost extends BaseApplicantPost
{
    const APPLICATION_STATUS_PENDING = 0;
    const APPLICATION_STATUS_SUBMITTED = 1;
    const APPLICATION_STATUS_CANCELED = 2;
    const APPLICATION_STATUS_REAPPLIED = 3;
    const APPLICATION_STATUS_PENDING_ESERVICE = 4;
    const APPLICATION_STATUS_ARCHIVE = 5;    
    const APPLICATION_STATUS_ESERVICE = 6;
    const QUALIFICATION_ESERVICE_LIMIT = 1;
    // is_paid status
    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;
    const STATUS_ARCHIVE = 5;
    // eservice_tabs inital value
    const ESERVICE_TAB_INITAL_VALUE = '000000';
    const ESERVICE_TAB_QUALIFICATION_VALUE = '000100';
    
    public $name;
    public $email;
    public $mobile;
    public $reference_no;

    // quota
    const QUOTA_1 = 1; // for 60% not govt tech
    const QUOTA_2 = 2; // for 10% for govt tech
    const QUOTA_3 = 3; // for 10% and 60%
    
    const LIMIT = 200;
    
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
            \components\behaviors\GuidBehavior::className(),
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['application_no', 'place']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'application_no' => 'cleanEncodeUTF8',
                        'place' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'application_no' => 'cleanEncodeUTF8',
                        'place' => 'cleanEncodeUTF8'
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
        
        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['applicantId'])) {
            $modelAQ->andWhere($tableName . '.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
        }
        
        if (isset($params['classifiedId'])) {
            $modelAQ->andWhere($tableName . '.classified_id =:classifiedId', [':classifiedId' => $params['classifiedId']]);
        }

        if (isset($params['postId'])) {
            $modelAQ->andWhere($tableName . '.post_id =:postId', [':postId' => $params['postId']]);
        }
        
        if (isset($params['notPostId'])) {
            $modelAQ->andWhere($tableName . '.post_id !=:notPostId', [':notPostId' => $params['notPostId']]);
        }
        
        if (isset($params['applicationNo'])) {
            $modelAQ->andWhere($tableName . '.application_no =:applicationNo', [':applicationNo' => $params['applicationNo']]);
        }
        
        if (isset($params['isNullApplicationNo'])) {
            $modelAQ->andWhere($tableName . '.application_no IS NULL');
        }
        
        if (isset($params['paymentStatus'])) {
            $modelAQ->andWhere($tableName . '.payment_status =:paymentStatus', [':paymentStatus' => $params['paymentStatus']]);
        }
        
        if (isset($params['applicationStatus'])) {
            $modelAQ->andWhere($tableName . '.application_status =:applicationStatus', [':applicationStatus' => $params['applicationStatus']]);
        }
        
        if (isset($params['parentApplicantPostId'])) {
            $modelAQ->andWhere($tableName . '.parent_applicant_post_id =:parentApplicantPostId', [':parentApplicantPostId' => $params['parentApplicantPostId']]);
        }
        
        if (isset($params['inApplicationStatus'])) {
            $modelAQ->andWhere(['IN', $tableName . '.application_status', $params['inApplicationStatus']]);
        }
        
        if (isset($params['joinWithPost']) && in_array($params['joinWithPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithPost']}('mst_post', 'mst_post.id = applicant_post.post_id');
            $modelAQ->{$params['joinWithPost']}('mst_classified', 'mst_classified.id = mst_post.classified_id');
            
            if (isset($params['classifiedId'])) {
                $modelAQ->andWhere('mst_classified.id =:classifiedId', [':classifiedId' => $params['classifiedId']]);
            }
            if (isset($params['pguid'])) {
                $modelAQ->andWhere('mst_post.guid =:pguid', [':pguid' => $params['pguid']]);
            }
        }
        
        if (isset($params['joinWithApplicantPostDetail']) && in_array($params['joinWithApplicantPostDetail'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPostDetail']}('applicant_post_detail', 'applicant_post.id = applicant_post_detail.applicant_post_id');
        }
        
        if (isset($params['joinWithApplicant']) && in_array($params['joinWithApplicant'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicant']}('applicant', 'applicant.id = applicant_post.applicant_id');

            if (isset($params['mobile'])) {
                $modelAQ->andWhere('applicant.mobile =:mobile', [':mobile' => $params['mobile']]);
            }
        }

        if (isset($params['joinWithApplicantDetail']) && in_array($params['joinWithApplicantDetail'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantDetail']}('applicant_detail', 'applicant_post.id = applicant_detail.applicant_post_id');
        }

        if (isset($params['joinWithMstClassified']) && in_array($params['joinWithMstClassified'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithMstClassified']}('mst_classified', 'mst_classified.id = applicant_post.classified_id');
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
    
    public static function findByParentApplicantPostId($parentApplicantPostId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['parentApplicantPostId' => $parentApplicantPostId], $params));
    }

    public static function findByApplicantIdAndPostId($applicantId, $postId, $params = [])
    {
        $queryParams = [
            'applicantId' => $applicantId,
            'postId' => $postId
        ];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public static function findByApplicantId($applicantId, $params = [])
    {
        $queryParams = [
            'applicantId' => $applicantId
        ];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public static function findByClassifiedId($classifiedId, $params = [])
    {
        $queryParams = [
            'classifiedId' => $classifiedId
        ];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public static function getApplicationStatus($key = null)
    {
        $list = [
            self::APPLICATION_STATUS_PENDING => 'Pending',
            self::APPLICATION_STATUS_SUBMITTED => 'Submitted',
            self::APPLICATION_STATUS_CANCELED => 'Cancelled',
            self::APPLICATION_STATUS_REAPPLIED => 'Cancelled',
            self::APPLICATION_STATUS_ARCHIVE => 'Archive',
            self::APPLICATION_STATUS_ESERVICE => 'Eservice',
        ];
        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public static function getPaymentStatus($key = null)
    {
        $list = [
            self::STATUS_PAID => 'Paid',
            self::STATUS_UNPAID => 'Unpaid',
            self::STATUS_ARCHIVE => 'Paid'
        ];

        if (isset($list[$key])) {
            return $list[$key];
        }

        return $list;
    }
    
    public function generateApplicationNo($id)
    {
        $applicantPostModel = ApplicantPost::findById($id, [
            'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if(empty($applicantPostModel)){
            return false;
        }
        
        if($applicantPostModel->application_status == ApplicantPost::APPLICATION_STATUS_PENDING 
                || $applicantPostModel->payment_status == ApplicantPost::STATUS_UNPAID){
            //return false;
        }
        
        if (!empty($applicantPostModel->application_no)) {
            return true;
        }
        
        $mstClassified = MstClassified::findById($applicantPostModel->classified_id);

        $prefix = $mstClassified['application_no_prefix'];
        //$postCode = '01';
        $time = time()+$applicantPostModel->id;
        
        /*if(empty($postCode) || empty($applicantPostModel->quota)){
            return false;
        }*/
        
        try {
            
            //$applicationNo = $prefix . $postCode . $applicantPostModel->quota. $time;
            $applicationNo = $prefix . $applicantPostModel->quota. $time;
            
            while (true) {
                $ifexists = self::findByParams([
                    'applicationNo' => $applicationNo,
                    'existOnly' => true
                ]);
                if(!$ifexists){
                    break;
                }
                $time += 1;
                
                //$applicationNo = $prefix . $postCode . $applicantPostModel->quota. $time;
                $applicationNo = $prefix . $applicantPostModel->quota. $time;
            }

            $applicantPostModel->application_no = $applicationNo;
            $applicantPostModel->save(true, ['application_no']);
        }
        catch (\Exception $ex) {
            Yii::error('Application Number Error :' . $ex->getMessage());
        }
    }
    
    public function cloneMasterProfile($id)
    {
        $applicantPostModel = ApplicantPost::findById($id, [
            'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if(empty($applicantPostModel)){
            return false;
        }
        
        if($applicantPostModel->application_status == ApplicantPost::APPLICATION_STATUS_PENDING 
                || $applicantPostModel->payment_status == ApplicantPost::STATUS_UNPAID){
            //return false;
        }
        
        try {
            
            ApplicantPost::updateAll(['application_status' => ApplicantPost::APPLICATION_STATUS_REAPPLIED], 'applicant_id=:applicantId AND application_status=:applicationStatus', [':applicantId' => $applicantPostModel->applicant_id, ':applicationStatus' => ApplicantPost::APPLICATION_STATUS_CANCELED]);
            $clone = new \frontend\components\CloneProfileComponent();
            $clone->applicantId = $applicantPostModel->applicant_id;
            $clone->applicantPostId = $applicantPostModel->id;
            $clone->profile();
        }
        catch (\Exception $ex) {
            Yii::error('Application Clone Error :' . $ex->getMessage());
        }
    }
    
    /**
     * check status for eligible for cancel or not
     * @param type $id
     * @param type $params
     * @return boolean
     */
    public static function checkStatusForCancel($id, $params = [])
    {
        $qp = [
            'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT,
            'paymentStatus' => self::STATUS_PAID
        ];
        $model = self::findById($id, \yii\helpers\ArrayHelper::merge($qp, $params));
        if ($model === NULL) {
            return false;
        }
        
        if($model->classified->cancellation_status == MstClassified::CANCELLED_DISABLED) {
            return false;
        }

        $lastDate = $model->classified->end_date;
        if (isset($model->classified->extended_date) && !empty($model->classified->extended_date)) {
            $lastDate = $model->classified->extended_date;
        }
        $lastDateTime = strtotime($lastDate.' 23:59:59');

        if ($lastDateTime > time()):
            return $model->application_status;
        endif;

        return false;
    }
    
    /**
     * check status for eligible for cancel or not
     * @param type $id
     * @param type $params
     * @return boolean
     */
    public static function checkStatusForEservice($id, $params = [])
    {
        $qp = [
            'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT,
            'paymentStatus' => self::STATUS_PAID
        ];
        $model = self::findById($id, \yii\helpers\ArrayHelper::merge($qp, $params));
        if ($model === NULL) {
            return false;
        }
        
        $totalEservice = ApplicantPost::findByApplicantId(Yii::$app->applicant->id, [
                    'applicationStatus' => ApplicantPost::APPLICATION_STATUS_ESERVICE,
                    'classifiedId' => $model->classified->id,
                    'countOnly' => true
        ]);
        if($totalEservice >= $model->classified->eservices_limit) {
            return false;
        }

        $startDateTime = strtotime($model->classified->eservice_start_date . ' 00:00:01');
        $lastDateTime = strtotime($model->classified->eservice_end_date . ' 23:59:59');

        if (time() >= $startDateTime && time() <= $lastDateTime):
            return true;
        endif;

        return false;
    }
    
    /**
     * find and generate application_no
     * @return boolean
     */
    public function createSchedulerJob()
    {
        try {
            $params = [
                'selectCols' => 'applicant_post.id',
                'isNullApplicationNo' => TRUE,
                'notPostId' => MstPost::MASTER_POST,
                'paymentStatus' => self::STATUS_PAID,
                'limit' => self::LIMIT,
                'orderBy' => [
                    'id' => SORT_DESC
                ],
                'resultCount' => caching\ModelCache::RETURN_ALL,
            ];

            $applicantPostModel = self::findByParams($params);
            if ($applicantPostModel == NULL) {
                return FALSE;
            }

            foreach ($applicantPostModel as $model) {
                ApplicantPost::generateApplicationNo($model['id']);
            }
        }
        catch (\Exception $ex) {
            \Yii::error('Applicant Scheduler Job Error - ' . $ex->getMessage());
        }
        return TRUE;
    }
}
