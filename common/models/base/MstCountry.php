<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_country".
 *
 * @property int $code
 * @property string $guid
 * @property string $name
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $modified_by
 * @property int $created_on
 * @property int $modified_on
 *
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstState[] $mstStates
 */
class MstCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'guid', 'name'], 'required'],
            [['code', 'is_active', 'is_deleted', 'created_by', 'modified_by', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['guid'], 'unique'],
            [['code'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'guid' => 'Guid',
            'name' => 'Name',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_on' => 'Created On',
            'modified_on' => 'Modified On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMstStates()
    {
        return $this->hasMany(MstState::className(), ['country_code' => 'code']);
    }
}
