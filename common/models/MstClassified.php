<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstClassified as BaseMstClassified;
use components\exceptions\AppException;
use common\models\caching\ModelCache;

/**
 * Description of MstClassified
 *
 * @author Amit Handa
 */
class MstClassified extends BaseMstClassified
{
    const IS_ACTIVE_YES = 1;
    const IS_ACTIVE_NO = 0;
    const IS_ACTIVE_COMPLETE = 2;
    const IS_POST_SPECIFIC = 1;
    const IS_POST_CLASSIFIED = 0;
    const IS_MULTIPLE_POST = 2;
    
    //Unreserved General/ EWS having the DOB before DOB_START_DATE
    const DOB_START_DATE = '1978-01-01';
    //Unreserved General with Ex-Army having the DOB before DOB_SECOND_CONDITION_DATE
    const DOB_EX_ARMY = '1962-01-01';
    const DOB_PH = '1968-01-01';
    const DOB_SC_ST_OBC_DFF = '1973-01-01';
    const AGE_CALCULATE_DATE = '2022-01-01';
    const MASTER_PROFILE_GUID = 'a18d8778-57c6-11ea-818e-00ff81b75f6f';
    const ACTIVE_CLASSIFIED_GUID = '23954196-1f55-11eb-8c69-02fa15237a6a';
    const ACTIVE_CLASSIFIED_ID = 10;
    const MASTER_CLASSIFIED = 2;
    const VIEW_AGE_CALCULATE_DATE = '01/01/2022';
    
    //cancellation-status
    const CANCELLED_DISABLED = 0;
    const CANCELLED_ENABLED = 1;
    const CANCELLED_REAPPLY = 2;
    
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
                'attributes' => ['code', 'title']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'code' => 'cleanEncodeUTF8',
                        'title' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'code' => 'cleanEncodeUTF8',
                        'title' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
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
        
        if (isset($params['notMsaterId'])) {
            $modelAQ->andWhere($tableName . '.id !=:id', [':id' => self::MASTER_CLASSIFIED]);
        }
        
        if (isset($params['notInIds'])) {
            $modelAQ->andWhere(['NOT IN', $tableName . '.id', $params['notInIds']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }

        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }

        if (isset($params['isAttendance'])) {
            $modelAQ->andWhere($tableName . '.is_attendance =:isAttendance', [':isAttendance' => $params['isAttendance']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere($tableName . '.is_deleted =:isDeleted', [':isDeleted' => $params['isDeleted']]);
        }

        if (isset($params['admitCardStartDate'])) {
            $modelAQ->andWhere($tableName . '.admit_card_start_date <= :admitCardStartDate', [':admitCardStartDate' => date('Y-m-d', strtotime($params['admitCardStartDate']))]);
        }

        if (isset($params['admitCardEndDate'])) {
            $modelAQ->andWhere($tableName . '.admit_card_end_date >= :admitCardEndDate', [':admitCardEndDate' => date('Y-m-d', strtotime($params['admitCardEndDate']))]);
        }

        return (new ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
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
    
    public static function classifiedList()
    {

        $model = self::findByParams([
                    'returnAll' => ModelCache::RETURN_ALL,
                    'isActive' => ModelCache::IS_ACTIVE_YES
        ]);
        return $model;
    }
    
    public static function getClassifiedDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_classified.id', "CONCAT(mst_classified.title, '-', mst_classified.code) as title"
            ],
            'status' => ModelCache::IS_ACTIVE_YES,
            'isDeleted' => ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['mst_classified.title' => SORT_ASC],
        ];
        $classifiedModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($classifiedModel, 'id', 'title');
        
        return $list;
    }
    
    public static function checkClassifiedActiveStatus($id, $params = [])
    {
        $model = self::findById($id, $params);
        if ($model === NULL) {
            return false;
        }
        
        $time = time();
        $lastDate = $model['end_date'];
        if (isset($model['extended_date']) && !empty($model['extended_date'])) {
            $lastDate = $model['extended_date'];
        }
        $lastDateTime = strtotime($lastDate . ' 23:59:59');
        if ($time < strtotime($model['start_date'].' 00:00:01') || ($time > $lastDateTime)) {
            return false;
        }

        return true;
    }
    
    public static function getReferenceDate($guid, $params = [])
    {
        $ageCalculateDate = self::AGE_CALCULATE_DATE;
        $model = self::findByGuid($guid, $params);
        if ($model !== NULL) {
            $ageCalculateDate = $model['reference_date'];
        }

        return $ageCalculateDate;
    }
    
    /**
     * Payment date enable
     * @param type $id
     * @param type $params
     * @return boolean
     */
    public static function isPaymentDateEnable($id, $params = [])
    {
        $qp = [
            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ];
        $model = self::findById($id, \yii\helpers\ArrayHelper::merge($qp, $params));
        if ($model === NULL) {
            return false;
        }
        
        $date = strtotime(date('d-m-Y'));
        if (!empty($model->payment_end_date) && $date <= strtotime($model->payment_end_date)) {
            return true;
        }

        return false;
    }
    
    public static function getTitle($id, $params = [])
    {
        if($id == NULL){
            return '';
        }
        $data = self::findById($id, $params);
        if(!empty($data)){
            $text = $data['title'];
            $text .= !empty($data['code']) ? " Code - " . $data['code'] : '';
            return $text;
        }
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

    public static function validateAdmitCardLink($id, $params = [])
    {
        $model = MstClassified::findById($id, [
                    'selectCols' => ['id', 'admit_card_start_date', 'admit_card_end_date', 'CONCAT(title,"-",code) as title']
        ]);

        if ($model === null) {
            return false;
        }

        if (empty($model['admit_card_start_date']) || empty($model['admit_card_end_date'])) {
            return false;
        }

        $startDT = strtotime($model['admit_card_start_date'] . ' 00:00:01');
        $lastDT = strtotime($model['admit_card_end_date'] . ' 23:59:59');
        if (time() < $startDT):
            return false;
        endif;
        if ($lastDT < time()):
            return false;
        endif;

        if(isset($params['applicantPostId']) && !empty($params['applicantPostId'])):        
            $applicantExam = \common\models\ApplicantExam::findByApplicantPostId($params['applicantPostId'], ['selectCols' => ['id'], 'examType' => \common\models\ApplicantExam::EXAM_TYPE_WRITTEN]);
            if($applicantExam == null):
                return false;
            endif;
        endif;

        return true;
    }

    public static function validateAdmitCard($id, $params = [])
    {
        $model = MstClassified::findById($id, [
                    'selectCols' => ['id', 'admit_card_start_date', 'admit_card_end_date', 'CONCAT(title,"-",code) as title']
        ]);

        if ($model === null) {
            throw new AppException(Yii::t('app', 'model.notfound', ['title' => 'Advertisement']));
        }

        if (empty($model['admit_card_start_date']) || empty($model['admit_card_end_date'])) {
            throw new AppException(Yii::t('app', 'Admit card not available for this advertisment.'));
        }

        $startDT = strtotime($model['admit_card_start_date'] . ' 00:00:01');
        $lastDT = strtotime($model['admit_card_end_date'] . ' 23:59:59');
        if (time() < $startDT):
            throw new AppException(Yii::t('app', 'Admit card will open on ' . date('d-m-Y', $startDT). ' for advertisement '. $model['title']));
        endif;
        if ($lastDT < time()):
            throw new AppException(Yii::t('app', 'Admit card closed now.'));
        endif;

        return true;
    }
}