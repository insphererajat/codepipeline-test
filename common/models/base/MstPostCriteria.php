<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_post_criteria".
 *
 * @property int $id
 * @property string|null $guid
 * @property int $post_id
 * @property int|null $reservation_category_id
 * @property string|null $criteria_type
 * @property string|null $criteria_vlue
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_by
 * @property int $created_on
 * @property int $modified_by
 * @property int $modified_on
 *
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstPost $post
 * @property MstListType $reservationCategory
 * @property MstPostQualification[] $mstPostQualifications
 */
class MstPostCriteria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_post_criteria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'required'],
            [['post_id', 'reservation_category_id', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['criteria_type'], 'string', 'max' => 255],
            [['criteria_vlue'], 'string', 'max' => 100],
            [['guid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPost::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['reservation_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['reservation_category_id' => 'id']],
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
            'post_id' => Yii::t('app', 'Post ID'),
            'reservation_category_id' => Yii::t('app', 'Reservation Category ID'),
            'criteria_type' => Yii::t('app', 'Criteria Type'),
            'criteria_vlue' => Yii::t('app', 'Criteria Vlue'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
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
     * Gets query for [[ReservationCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservationCategory()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'reservation_category_id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['post_criteria_id' => 'id']);
    }
}
