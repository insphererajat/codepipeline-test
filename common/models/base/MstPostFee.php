<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_post_fee".
 *
 * @property int $id
 * @property string|null $guid
 * @property int $classified_id
 * @property int|null $post_id
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property float $amount
 * @property int|null $display_order
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $created_on
 * @property int|null $created_by
 * @property int|null $modified_on
 * @property int|null $modified_by
 *
 * @property MstListType $category
 * @property MstClassified $classified
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstPost $post
 * @property MstListType $subCategory
 */
class MstPostFee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_post_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['classified_id'], 'required'],
            [['classified_id', 'post_id', 'category_id', 'sub_category_id', 'display_order', 'is_active', 'is_deleted', 'created_on', 'created_by', 'modified_on', 'modified_by'], 'integer'],
            [['amount'], 'number'],
            [['guid'], 'string', 'max' => 36],
            [['guid'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['classified_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstClassified::className(), 'targetAttribute' => ['classified_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPost::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['sub_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['sub_category_id' => 'id']],
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
            'classified_id' => Yii::t('app', 'Classified ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'sub_category_id' => Yii::t('app', 'Sub Category ID'),
            'amount' => Yii::t('app', 'Amount'),
            'display_order' => Yii::t('app', 'Display Order'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Classified]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassified()
    {
        return $this->hasOne(MstClassified::className(), ['id' => 'classified_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[ModifiedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(MstPost::className(), ['id' => 'post_id']);
    }

    /**
     * Gets query for [[SubCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubCategory()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'sub_category_id']);
    }
}
