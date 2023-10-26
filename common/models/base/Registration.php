<?php

namespace common\base\models;

use Yii;

/**
 * This is the model class for table "registration".
 *
 * @property int $id
 * @property int $step
 * @property int $user
 * @property string $key
 * @property string $value
 *
 * @property User $user0
 */
class Registration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['step'], 'required'],
            [['step', 'user'], 'integer'],
            [['key', 'value'], 'string', 'max' => 225],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'step' => 'Step',
            'user' => 'User',
            'key' => 'Key',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
