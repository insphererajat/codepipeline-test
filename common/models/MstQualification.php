<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\MstQualification as BaseMstQualification;

/**
 * Description of MstDepartment
 *
 * @author Amit Handa
 */
class MstQualification extends BaseMstQualification
{

    const RESULT_TYPE_MARKS = 'MARKS';
    const RESULT_TYPE_GRADE = 'GRADE';
    const RESULT_TYPE_CGPA = 'CGPA';
    const COURSE_DURATION_THREE_MONTHS = 1;
    const COURSE_DURATION_SIX_MONTHS = 2;
    const COURSE_DURATION_NINE_MONTHS = 3;
    const COURSE_DURATION_ONE_YEAR = 4;
    const COURSE_DURATION_TWO_YEAR = 5;
    const COURSE_DURATION_THREE_YEAR = 6;
    const COURSE_DURATION_FOUR_YEAR = 7;
    const COURSE_DURATION_FIVE_YEAR = 8;
    const COURSE_DURATION_SIX_YEAR = 9;
    
    const GRADUATE = 19;
    const PARENT_8TH = 677;
    const PARENT_10TH = 1;
    const PARENT_12 = 2;
    const PARENT_BED = 26;
    const BSCED = 611;
    const BACHELOR_OF_SCIENCE = 408;
    const BSC_HONOURS = 606;
    const MPHILL = 22;
    const PHD = 23;
    const NET_SLET_SET = 24;
    const CERTIFICATIONS = 25;
    const DIPLOMA = 18;
    const POST_GRADUATE = 21;
    const PG_DIPLOMA = 445;
    const BCOM = 428;
    const MCOM = 484;
    const BBA = 436;
    const OLEVEL = 493;
    const CCC = 494;
    const DIPLOMA_IN_COMPUTER_APPLICATION = 375;
    const INTERMEDIATE_COMMERCE = 619;
    const PARENT_OTHER = 685;
    const CHILD_OTHER = 686;

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
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => [ 'name']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'name' => 'cleanEncodeUTF8',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'name' => 'cleanEncodeUTF8',
                    ]
                ]
            ],
            \components\behaviors\GuidBehavior::className()
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
        
        if (isset($params['parentId'])) {
            $modelAQ->andWhere($tableName . '.parent_id =:parentId', [':parentId' => $params['parentId']]);
        }
        
        if (isset($params['recordIsNull']) && !empty($params['recordIsNull']) && isset($params['recordIsNull']['column']) && !empty($params['recordIsNull']['column'])) {
            $modelAQ->andWhere("{$params['recordIsNull']['column']} IS NULL");
        }

        if (isset($params['recordIsNotNull']) && !empty($params['recordIsNotNull']) && isset($params['recordIsNotNull']['column']) && !empty($params['recordIsNotNull']['column'])) {
            $modelAQ->andWhere("{$params['recordIsNotNull']['column']} IS NOT NULL");
        }
        
        if (isset($params['ids'])) {
            $modelAQ->andWhere(['IN', $tableName . '.id', $params['ids']]);
        }
        
        if (isset($params['isActive'])) {
            $modelAQ->andWhere($tableName . '.is_active =:isActive', [':isActive' => $params['isActive']]);
        }

        if (isset($params['isDeleted'])) {
            $modelAQ->andWhere($tableName . '.is_deleted =:isDeleted', [':isDeleted' => $params['isDeleted']]);
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

    public static function getQualificationDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'mst_qualification.id', 'mst_qualification.name'
            ],
            'isActive' => \common\models\caching\ModelCache::IS_ACTIVE_YES,
            'isDeleted' => \common\models\caching\ModelCache::IS_DELETED_NO,
            'resultCount' => 'all',
            'orderBy' => ['mst_qualification.display_order' => SORT_ASC],
        ];
        $qualificationModel = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($qualificationModel, 'id', 'name');
        return $list;
    }

    public static function getCourseDropDown()
    {
        return [
            self::COURSE_DURATION_THREE_MONTHS => '3 Months',
            self::COURSE_DURATION_SIX_MONTHS => '6 Months',
            self::COURSE_DURATION_NINE_MONTHS => '9 Months',
            self::COURSE_DURATION_ONE_YEAR => '1 Year',
            self::COURSE_DURATION_TWO_YEAR => '2 Years',
            self::COURSE_DURATION_THREE_YEAR => '3 Years',
            self::COURSE_DURATION_FOUR_YEAR => '4 Years',
            self::COURSE_DURATION_FIVE_YEAR => '5 Years',
            self::COURSE_DURATION_SIX_YEAR => '6 Years',
        ];
    }
    
    public static function findMstQualificationModel($params = [])
    {
        return self::findByParams($params);
    }
    
    public static function getName($id, $params = [])
    {
        if($id == NULL){
            return '';
        }
        $data = self::findById($id, $params);
        if(!empty($data)){
            return $data['name'];
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
}
