<?php

namespace common\models;

use Yii;
use common\models\base\ApplicantPostExamCentre as BaseApplicantPostExamCentre;
/**
 * Description of ApplicantPostExamCentre
 *
 * @author Nitish
 */
class ApplicantPostExamCentre extends BaseApplicantPostExamCentre
{
    const PREFERENCE_1 = 1;
    const PREFERENCE_2 = 2;
    const PREFERENCE_3 = 3;
    
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

        if (isset($params['preference'])) {
            $modelAQ->andWhere($tableName . '.preference =:preference', [':preference' => $params['preference']]);
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
    
    
}
