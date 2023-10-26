<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "list_type".
 *
 * @property int $id
 * @property string $guid
 * @property string $type
 * @property string $name
 * @property string $description
 * @property int $display_order
 * @property int $is_active
 * @property int $is_deleted
 * @property int $modified_by
 * @property int $created_by
 * @property int $modified_on
 * @property int $created_on
 *
 * @property User $createdBy
 * @property User $modifiedBy
 */
class ListType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'type'], 'required'],
            [['display_order', 'is_active', 'is_deleted', 'modified_by', 'created_by', 'modified_on', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['type'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['guid'], 'unique'],
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
            'id' => 'ID',
            'guid' => 'Guid',
            'type' => 'Type',
            'name' => 'Name',
            'description' => 'Description',
            'display_order' => 'Display Order',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'modified_by' => 'Modified By',
            'created_by' => 'Created By',
            'modified_on' => 'Modified On',
            'created_on' => 'Created On',
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
}
