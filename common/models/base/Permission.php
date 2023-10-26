<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "permission".
 *
 * @property string $permission_name
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permission_name'], 'required'],
            [['permission_name'], 'string', 'max' => 255],
            [['permission_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'permission_name' => Yii::t('app', 'Permission Name'),
        ];
    }
}
