<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantAddress as BaseApplicantAddress;

/**
 * Description of ApplicantAddress
 *
 * @author ispl
 */
class ApplicantAddress extends BaseApplicantAddress
{

    const CURRENT_ADDRESS = 0;
    const PERMANENT_ADDRESS = 1;
    
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
                'attributes' => ['house_no', 'premises_name', 'street', 'area', 'landmark', 'tehsil_name', 'village_name', 'pincode', 'nearest_police_station']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'house_no' => 'cleanEncodeUTF8',
                        'premises_name' => 'cleanEncodeUTF8',
                        'street' => 'cleanEncodeUTF8',
                        'area' => 'cleanEncodeUTF8',
                        'landmark' => 'cleanEncodeUTF8',
                        'tehsil_name' => 'cleanEncodeUTF8',
                        'village_name' => 'cleanEncodeUTF8',
                        'pincode' => 'cleanEncodeUTF8',
                        'nearest_police_station' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'house_no' => 'cleanEncodeUTF8',
                        'premises_name' => 'cleanEncodeUTF8',
                        'street' => 'cleanEncodeUTF8',
                        'area' => 'cleanEncodeUTF8',
                        'landmark' => 'cleanEncodeUTF8',
                        'tehsil_name' => 'cleanEncodeUTF8',
                        'village_name' => 'cleanEncodeUTF8',
                        'pincode' => 'cleanEncodeUTF8',
                        'nearest_police_station' => 'cleanEncodeUTF8',
                    ]
                ]
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

        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
        }

        if (isset($params['addressType'])) {
            $modelAQ->andWhere($tableName . '.address_type =:address_type', [':address_type' => $params['addressType']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }
    
    public static function findByApplicantPostId($applicantPostId, $params = [])
    {

        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId], $params));
    }
    
    public static function getAddressByTypeAndApplicantId($applicantId, $addressType = self::CURRENT_ADDRESS, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantId' => $applicantId, 'addressType' => $addressType], $params));
    }

    public static function getAddressByApplicantId($applicantId, $params = [])
    {
        $queryParams = ['applicantId' => $applicantId, 'returnAll' => true];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }
    
    public function beforeSave($insert)
    {
        
        $this->tehsil_code = ($this->tehsil_code == location\MstTehsil::OTHER) ? null : $this->tehsil_code;
        $this->tehsil_name = empty($this->tehsil_name) ? null : $this->tehsil_name;

        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = strip_tags($this->{$key});
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        if ($insert) {

        }
        return parent::beforeSave($insert);
    }
    
    public function afterFind() {
        
        $this->tehsil_code = !empty($this->tehsil_name) ? location\MstTehsil::OTHER : $this->tehsil_code;

        foreach($this->attributes as $key => $attribute) {
            $this->{$key} = htmlentities($attribute);
            $this->{$key} = str_replace(['>', '<', '"', "'", ';'], '', $this->{$key});
        }
        parent::afterFind();
    }

}
