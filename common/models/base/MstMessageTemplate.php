<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_message_template".
 *
 * @property int $id
 * @property string $guid
 * @property string $title
 * @property string|null $type
 * @property string|null $service
 * @property string|null $template_id
 * @property string|null $template
 * @property int|null $is_active
 * @property int|null $is_deleted
 * @property int $created_by
 * @property int|null $modified_by
 * @property int|null $created_on
 * @property int|null $modified_on
 */
class MstMessageTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_message_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'title', 'created_by'], 'required'],
            [['type', 'template'], 'string'],
            [['is_active', 'is_deleted', 'created_by', 'modified_by', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['title', 'service'], 'string', 'max' => 100],
            [['template_id'], 'string', 'max' => 30],
            [['title'], 'unique'],
            [['guid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'guid' => Yii::t('app', 'Guid'),
            'title' => Yii::t('app', 'Title'),
            'type' => Yii::t('app', 'Type'),
            'service' => Yii::t('app', 'Service'),
            'template_id' => Yii::t('app', 'Template ID'),
            'template' => Yii::t('app', 'Template'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }
}
