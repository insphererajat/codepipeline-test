<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_fee".
 *
 * @property int $id
 * @property string $guid
 * @property int $post_id
 * @property int $reservation_category_id
 * @property double $amount
 * @property int $is_exservicemen
 * @property int $is_goverment_employee
 * @property int $is_physically_handicaped
 * @property int $is_dependent_freedom_fighter
 * @property int $is_uttrakhand_female
 * @property int $min_age
 * @property int $max_age
 * @property int $display_order
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_on
 * @property int $created_by
 * @property int $modified_on
 * @property int $modified_by
 *
 * @property User $createdBy
 * @property MstListType $reservationCategory
 * @property User $modifiedBy
 * @property MstPost $post
 */
class MstFee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'reservation_category_id', 'amount', 'created_by'], 'required'],
            [['post_id', 'reservation_category_id', 'is_exservicemen', 'is_goverment_employee', 'is_physically_handicaped', 'is_dependent_freedom_fighter', 'is_uttrakhand_female', 'min_age', 'max_age', 'display_order', 'is_active', 'is_deleted', 'created_on', 'created_by', 'modified_on', 'modified_by'], 'integer'],
            [['amount'], 'number'],
            [['guid'], 'string', 'max' => 36],
            [['post_id', 'reservation_category_id'], 'unique', 'targetAttribute' => ['post_id', 'reservation_category_id']],
            [['guid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['reservation_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['reservation_category_id' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPost::className(), 'targetAttribute' => ['post_id' => 'id']],
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
            'post_id' => 'Post ID',
            'reservation_category_id' => 'Reservation Category ID',
            'amount' => 'Amount',
            'is_exservicemen' => 'Is Exservicemen',
            'is_goverment_employee' => 'Is Goverment Employee',
            'is_physically_handicaped' => 'Is Physically Handicaped',
            'is_dependent_freedom_fighter' => 'Is Dependent Freedom Fighter',
            'is_uttrakhand_female' => 'Is Uttrakhand Female',
            'min_age' => 'Min Age',
            'max_age' => 'Max Age',
            'display_order' => 'Display Order',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_on' => 'Created On',
            'created_by' => 'Created By',
            'modified_on' => 'Modified On',
            'modified_by' => 'Modified By',
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
    public function getReservationCategory()
    {
        return $this->hasOne(MstListType::className(), ['id' => 'reservation_category_id']);
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
    public function getPost()
    {
        return $this->hasOne(MstPost::className(), ['id' => 'post_id']);
    }
}
