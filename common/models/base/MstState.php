<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_state".
 *
 * @property int $code
 * @property string $guid
 * @property string $name
 * @property int|null $pincode
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property MstDistrict[] $mstDistricts
 * @property User $modifiedBy
 * @property User $createdBy
 * @property MstUniversity[] $mstUniversities
 */
class MstState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'name'], 'required'],
            [['pincode', 'is_active', 'is_deleted', 'created_by', 'modified_by', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['guid'], 'unique'],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'guid' => Yii::t('app', 'Guid'),
            'name' => Yii::t('app', 'Name'),
            'pincode' => Yii::t('app', 'Pincode'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[MstDistricts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstDistricts()
    {
        return $this->hasMany(MstDistrict::className(), ['state_code' => 'code']);
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
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[MstUniversities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstUniversities()
    {
        return $this->hasMany(MstUniversity::className(), ['state_code' => 'code']);
    }
}
