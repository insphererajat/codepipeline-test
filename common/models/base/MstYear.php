<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_year".
 *
 * @property int $code
 * @property string $guid
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $created_on
 * @property int|null $created_by
 * @property int|null $modified_on
 * @property int|null $modified_by
 *
 * @property ExamCentre[] $examCentres
 * @property MstClassified[] $mstClassifieds
 * @property User $createdBy
 * @property User $modifiedBy
 */
class MstYear extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_year';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'guid', 'name', 'from_date', 'to_date'], 'required'],
            [['code', 'is_active', 'is_deleted', 'created_on', 'created_by', 'modified_on', 'modified_by'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 10],
            [['guid'], 'unique'],
            [['name'], 'unique'],
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
            'code' => Yii::t('app', 'Code'),
            'guid' => Yii::t('app', 'Guid'),
            'name' => Yii::t('app', 'Name'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }

    /**
     * Gets query for [[ExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentres()
    {
        return $this->hasMany(ExamCentre::className(), ['recruitment_year' => 'code']);
    }

    /**
     * Gets query for [[MstClassifieds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstClassifieds()
    {
        return $this->hasMany(MstClassified::className(), ['recruitment_year' => 'code']);
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
}
