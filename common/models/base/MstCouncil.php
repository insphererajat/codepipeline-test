<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_council".
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property int $display_order
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $created_on
 * @property int $modified_by
 * @property int $modified_on
 *
 * @property ApplicantQualification[] $applicantQualifications
 * @property User $createdBy
 * @property User $modifiedBy
 */
class MstCouncil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_council';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'display_order', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'required'],
            [['display_order', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
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
    public function getApplicantQualifications()
    {
        return $this->hasMany(ApplicantQualification::className(), ['council_id' => 'id']);
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
