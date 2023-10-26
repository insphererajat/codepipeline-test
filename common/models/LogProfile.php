<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\LogProfile as BaseLogProfile;

/**
 * Description of LogProfile
 *
 * @author Amit Handa
 */
class LogProfile extends BaseLogProfile
{
    // status
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    // limit
    const LIMIT = 2;
    
    /**
     * status dropdown
     * @param type $key
     * @return type
     */
    public static function statusDropdown($key = null)
    {
        $list = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
        
        return (isset($list[$key])) ? $list[$key]: $list;
    }

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
                'attributes' => ['old_value', 'new_value']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'old_value' => 'cleanEncodeUTF8',
                        'new_value' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'old_value' => 'cleanEncodeUTF8',
                        'new_value' => 'cleanEncodeUTF8'
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
        
        if (isset($params['status'])) {
            $modelAQ->andWhere($tableName . '.status =:status', [':status' => $params['status']]);
        }
        
        if (isset($params['joinWithLogProfileMedia']) && in_array($params['joinWithLogProfileMedia'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithLogProfileMedia']}('log_profile_media', 'log_profile.id = log_profile_media.log_profile_id');
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
    
    public static function findByApplicantId($applicantId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantId' => $applicantId], $params));
    }
    
    /**
     * find and generate application_no
     * @return boolean
     */
    public function createSchedulerJob()
    {
        try {
            $params = [
                'status' => self::STATUS_PENDING,
                'limit' => self::LIMIT,
                'orderBy' => [
                    'id' => SORT_DESC
                ],
                'resultCount' => caching\ModelCache::RETURN_ALL,
                'resultFormat' => caching\ModelCache::RETURN_TYPE_OBJECT
            ];

            $logProfileModel = self::findByParams($params);
            if ($logProfileModel == NULL) {
                return FALSE;
            }

            foreach ($logProfileModel as $model) {
                $obj = \yii\helpers\Json::decode($model['new_value']);
                if(empty($obj)) {
                    continue;
                }
                
                $applicant = Applicant::findById($model['applicant_id'], ['name' => $obj['name']]);
                $applicantPost = ApplicantPost::findByApplicantId($applicant['id'], ['postId' => MstPost::MASTER_POST]);
                $applicantDetail = ApplicantDetail::findByApplicantPostId($applicantPost['id'], ['countOnly' => true, 'dateOfBirth' => $obj['date_of_birth'], 'fatherName' => $obj['father_name']]);
                if ($applicantDetail > 0) {
                    $model->status = self::STATUS_REJECTED;
                    $model->modified_by = 1;
                    $model->save(TRUE, ['status', 'modified_by']);
                    
                    $data = [
                        'applicant_id' => $model->applicant_id,
                        'log_profile_id' => $model->id,
                        'status' => self::STATUS_REJECTED,
                        'remarks' => 'Rejected due to same value',
                        'created_by' => 1,
                    ];
                    $logProfileActivityModel = new LogProfileActivity();
                    $logProfileActivityModel->createLogProfileActivity($data);
                }
            }
        }
        catch (\Exception $ex) {
            \Yii::error('Log profile Scheduler Job Error - ' . $ex->getMessage());
        }
        return TRUE;
    }
}
