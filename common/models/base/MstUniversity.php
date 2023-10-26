<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_university".
 *
 * @property int $id
 * @property string|null $guid
 * @property string $name
 * @property int|null $state_code
 * @property int|null $parent_id
 * @property int|null $is_active
 * @property int|null $is_deleted
 * @property int|null $created_by
 * @property int|null $created_on
 * @property int|null $modified_by
 * @property int|null $modified_on
 *
 * @property ApplicantCriteria[] $applicantCriterias
 * @property ApplicantCriteria[] $applicantCriterias0
 * @property ApplicantQualification[] $applicantQualifications
 * @property MstPostQualification[] $mstPostQualifications
 * @property MstPostQualification[] $mstPostQualifications0
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstUniversity $parent
 * @property MstUniversity[] $mstUniversities
 * @property MstState $stateCode
 */
class MstUniversity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_university';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['state_code', 'parent_id', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['guid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstUniversity::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstState::className(), 'targetAttribute' => ['state_code' => 'code']],
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
            'name' => Yii::t('app', 'Name'),
            'state_code' => Yii::t('app', 'State Code'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantCriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriterias()
    {
        return $this->hasMany(ApplicantCriteria::className(), ['additional_university_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantCriterias0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantCriterias0()
    {
        return $this->hasMany(ApplicantCriteria::className(), ['university_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualifications()
    {
        return $this->hasMany(ApplicantQualification::className(), ['board_university' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['additional_university_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications0()
    {
        return $this->hasMany(MstPostQualification::className(), ['university_id' => 'id']);
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
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MstUniversity::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[MstUniversities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstUniversities()
    {
        return $this->hasMany(MstUniversity::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[StateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode()
    {
        return $this->hasOne(MstState::className(), ['code' => 'state_code']);
    }
}
