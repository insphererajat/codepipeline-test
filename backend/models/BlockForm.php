<?php

namespace backend\models;

use common\models\base\MstCountry;
use common\models\base\MstState;
use Yii;
use common\models\MstBlock;
use common\models\User;
use common\models\MstDistrict;
use common\models\caching\ModelCache;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BlockForm
 *
 * @author Ashish
 */
class BlockForm extends \yii\base\Model
{
    public $guid;
    public $code;
    public $country_code;
    public $state_code;
    public $district_code;
    public $name;
    public $is_active;
    public $content;
    public $created_by;
    public $modified_by;

    public function rules()
    {
        return [
            [['code', 'country_code', 'state_code', 'district_code', 'is_active', 'created_by', 'modified_by', ], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 100],
            [['code', 'district_code', 'state_code', 'country_code'], 'unique','targetClass' => MstBlock::className(), 
            'targetAttribute' => ['code', 'district_code', 'state_code', 'country_code'], 'message' => 'This combination of Block Code, Country, State and District already exists'],
            [['district_code','state_code','country_code'], 'exist', 'skipOnError' => true,'targetClass' => MstDistrict::className(), 
            'targetAttribute' => ['district_code' => 'code', 'state_code' => 'state_code', 'country_code' => 'country_code']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']]
        ];
    }

    public function saveData()
    {
        $guid = $this->guid;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!empty($guid)) {
                $model = MstBlock::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The block model you are trying to access doesn't exist.");
                }
            } else {
                $model = new MstBlock();
                $model->loadDefaultValues(TRUE);
            }
            
            $model->attributes = $this->attributes;

            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                return FALSE;
            }

            $model->save();

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

}
