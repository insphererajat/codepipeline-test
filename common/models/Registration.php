<?php

namespace common\models;

use Yii;
use common\models\base\Registration as BaseRegistration;

/**
 * This is the model class for table "registration".
 *
 * @property int $id
 * @property int $form_id
 * @property int $step
 * @property int $user
 * @property string $key
 * @property string $value
 *
 * @property User $user0
 */
class Registration extends BaseRegistration
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

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }
        
       return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }


}
