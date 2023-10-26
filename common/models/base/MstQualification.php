<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_qualification".
 *
 * @property int $id
 * @property string|null $guid
 * @property string $name
 * @property int $display_order
 * @property int|null $parent_id
 * @property int $is_active
 * @property int|null $is_deleted
 * @property int|null $created_by
 * @property int|null $created_on
 * @property int|null $modified_by
 * @property int|null $modified_on
 *
 * @property ApplicantQualification[] $applicantQualifications
 * @property ApplicantQualification[] $applicantQualifications0
 * @property MstPostQualification[] $mstPostQualifications
 * @property MstPostQualification[] $mstPostQualifications0
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstQualification $parent
 * @property MstQualification[] $mstQualifications
 * @property MstQualificationSubject[] $mstQualificationSubjects
 * @property MstSubject[] $subjects
 */
class MstQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_qualification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'display_order'], 'required'],
            [['display_order', 'parent_id', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['parent_id', 'name'], 'unique', 'targetAttribute' => ['parent_id', 'name']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstQualification::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'display_order' => Yii::t('app', 'Display Order'),
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
     * Gets query for [[ApplicantQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualifications()
    {
        return $this->hasMany(ApplicantQualification::className(), ['qualification_degree_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicantQualifications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantQualifications0()
    {
        return $this->hasMany(ApplicantQualification::className(), ['qualification_type_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['additional_qualification_id' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications0()
    {
        return $this->hasMany(MstPostQualification::className(), ['qualification_id' => 'id']);
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
        return $this->hasOne(MstQualification::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[MstQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstQualifications()
    {
        return $this->hasMany(MstQualification::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[MstQualificationSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstQualificationSubjects()
    {
        return $this->hasMany(MstQualificationSubject::className(), ['qualification_id' => 'id']);
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(MstSubject::className(), ['id' => 'subject_id'])->viaTable('mst_qualification_subject', ['qualification_id' => 'id']);
    }
}
