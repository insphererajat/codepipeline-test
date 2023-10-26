<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_classified_qualification_detail".
 *
 * @property int $id
 * @property string $guid
 * @property int $classified_id
 * @property int $type
 * @property string $type_head
 * @property string $name
 * @property int $display_order
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $created_on
 * @property int $modified_by
 * @property int $modified_on
 *
 * @property MstClassified $classified
 * @property User $createdBy
 * @property User $modifiedBy
 */
class MstClassifiedQualificationDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_classified_qualification_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'classified_id', 'type', 'name', 'display_order'], 'required'],
            [['classified_id', 'type', 'display_order', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['type_head', 'name'], 'string'],
            [['guid'], 'string', 'max' => 36],
            [['guid'], 'unique'],
            [['classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstClassified::className(), 'targetAttribute' => ['classified_id' => 'id']],
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
            'classified_id' => 'Classified ID',
            'type' => 'Type',
            'type_head' => 'Type Head',
            'name' => 'Name',
            'display_order' => 'Display Order',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
            'modified_by' => 'Modified By',
            'modified_on' => 'Modified On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassified()
    {
        return $this->hasOne(MstClassified::className(), ['id' => 'classified_id']);
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
