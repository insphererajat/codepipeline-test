<?php

namespace backend\models;

use Yii;
use common\models\caching\ModelCache;
use common\models\Registration;
use common\models\User;

class RegistrationForm extends \yii\base\Model
{

    public $id;
    public $key;
    public $value;
    public $user;
    public $step;

    public function rules()
    {
        return [
            [['id', 'step', 'user'], 'integer'],
            [['key', 'value'], 'string'],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    public function saveData()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = new Registration;
            $model->loadDefaultValues(TRUE);
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
