<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\LogProfileActivity as BaseLogProfileActivity;

/**
 * Description of LogProfileActivity
 *
 * @author Amit Hadna
 */
class LogProfileActivity extends BaseLogProfileActivity
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_on'],
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
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['applicantId'])) {
            $modelAQ->andWhere($tableName . '.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
        }

        if (isset($params['logProfileId'])) {
            $modelAQ->andWhere($tableName . '.log_profile_id =:logProfileId', [':logProfileId' => $params['logProfileId']]);
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
    
    public static function findByLogProfileId($logProfileId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['logProfileId' => $logProfileId], $params));
    }
    
    public function createLogProfileActivity($data)
    {
        try {
            $model = new LogProfileActivity;
            $model->isNewRecord = TRUE;
            $model->setAttributes($data);
            if (!$model->save()) {
                return FALSE;
            }
        }
        catch (\Exception $ex) {
            return FALSE;
        }
        return $model->id;
    }
}
