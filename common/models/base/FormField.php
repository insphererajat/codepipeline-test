<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "form_field".
 *
 * @property int $id
 * @property string $guid
 * @property string $type
 * @property string $title
 * @property string $data
 * @property string $slug
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property int $parent_id
 * @property int $form_id
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $created_on
 * @property int $modified_by
 * @property int $modified_on
 *
 * @property User $createdBy
 * @property Form $form
 * @property User $modifiedBy
 * @property FormField $parent
 * @property FormField[] $formFields
 */
class FormField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'type', 'title', 'data', 'form_id'], 'required'],
            [['data', 'meta_description'], 'string'],
            [['parent_id', 'form_id', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['type'], 'string', 'max' => 100],
            [['title', 'slug', 'meta_title', 'meta_keyword'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => Form::className(), 'targetAttribute' => ['form_id' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormField::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'data' => Yii::t('app', 'Data'),
            'slug' => Yii::t('app', 'Slug'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_keyword' => Yii::t('app', 'Meta Keyword'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
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
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'form_id']);
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
    public function getParent()
    {
        return $this->hasOne(FormField::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormFields()
    {
        return $this->hasMany(FormField::className(), ['parent_id' => 'id']);
    }
}
