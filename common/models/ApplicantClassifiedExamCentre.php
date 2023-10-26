<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "applicant_classified_exam_centre".
 *
 * @property int $applicant_classified_id
 * @property string $exam_centre_id
 * @property int $preference
 * @property int $exam_level
 * @property int $created_on
 * @property int $modified_on
 * @property int $created_by
 * @property int $modified_by
 *
 * @property ApplicantClassified $applicantClassified
 */
class ApplicantClassifiedExamCentre extends \common\models\base\ApplicantClassifiedExamCentre
{

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

        if (isset($params['applicantClassifiedId'])) {
            $modelAQ->andWhere($tableName . '.applicant_classified_id =:applicantClassifiedId', [':applicantClassifiedId' => $params['applicantClassifiedId']]);
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByApplicantId($applicantId, $params = [])
    {
        $queryParams = ['applicantId' => $applicantId, 'returnAll' => true];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }

    public static function findByApplicantClassifiedId($classifiedId, $params = [])
    {
        $queryParams = [
            'applicantClassifiedId' => $classifiedId
        ];
        return self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
    }

}
