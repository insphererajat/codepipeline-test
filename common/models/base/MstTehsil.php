<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_tehsil".
 *
 * @property int $code
 * @property string $guid
 * @property int $state_code
 * @property int $district_code
 * @property string $name
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantAddress[] $applicantAddresses
 * @property ApplicantDetail[] $applicantDetails
 * @property MstDistrict $stateCode
 * @property User $createdBy
 * @property User $modifiedBy
 */
class MstTehsil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_tehsil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'guid', 'state_code', 'district_code', 'name'], 'required'],
            [['code', 'state_code', 'district_code', 'is_active', 'is_deleted', 'created_by', 'modified_by', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 100],
            [['guid'], 'unique'],
            [['state_code', 'district_code', 'name'], 'unique', 'targetAttribute' => ['state_code', 'district_code', 'name']],
            [['code'], 'unique'],
            [['state_code', 'district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['state_code' => 'state_code', 'district_code' => 'code']],
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
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'name' => Yii::t('app', 'Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantAddresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantAddresses()
    {
        return $this->hasMany(ApplicantAddress::className(), ['tehsil_code' => 'code']);
    }

    /**
     * Gets query for [[ApplicantDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantDetails()
    {
        return $this->hasMany(ApplicantDetail::className(), ['birth_tehsil_code' => 'code']);
    }

    /**
     * Gets query for [[StateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode()
    {
        return $this->hasOne(MstDistrict::className(), ['state_code' => 'state_code', 'code' => 'district_code']);
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
