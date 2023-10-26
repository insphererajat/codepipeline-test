<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "mst_post_qualification".
 *
 * @property int $id
 * @property string $guid
 * @property int $post_criteria_id
 * @property int|null $option_seq
 * @property int $qualification_id
 * @property int|null $subject_id
 * @property int|null $university_id
 * @property int|null $eligible_year
 * @property int|null $additional_qualification_id
 * @property int|null $additional_subject_id
 * @property int|null $additional_university_id
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $created_by
 * @property int|null $created_on
 * @property int|null $modified_by
 * @property int|null $modified_on
 *
 * @property MstQualification $additionalQualification
 * @property MstSubject $additionalSubject
 * @property MstUniversity $additionalUniversity
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstPostCriteria $postCriteria
 * @property MstQualification $qualification
 * @property MstSubject $subject
 * @property MstUniversity $university
 */
class MstPostQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_post_qualification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'post_criteria_id', 'qualification_id'], 'required'],
            [['post_criteria_id', 'option_seq', 'qualification_id', 'subject_id', 'university_id', 'eligible_year', 'additional_qualification_id', 'additional_subject_id', 'additional_university_id', 'is_active', 'is_deleted', 'created_by', 'created_on', 'modified_by', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['guid'], 'unique'],
            [['additional_qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstQualification::className(), 'targetAttribute' => ['additional_qualification_id' => 'id']],
            [['additional_subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstSubject::className(), 'targetAttribute' => ['additional_subject_id' => 'id']],
            [['additional_university_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstUniversity::className(), 'targetAttribute' => ['additional_university_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['post_criteria_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPostCriteria::className(), 'targetAttribute' => ['post_criteria_id' => 'id']],
            [['qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstQualification::className(), 'targetAttribute' => ['qualification_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstSubject::className(), 'targetAttribute' => ['subject_id' => 'id']],
            [['university_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstUniversity::className(), 'targetAttribute' => ['university_id' => 'id']],
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
            'post_criteria_id' => Yii::t('app', 'Post Criteria ID'),
            'option_seq' => Yii::t('app', 'Option Seq'),
            'qualification_id' => Yii::t('app', 'Qualification ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'university_id' => Yii::t('app', 'University ID'),
            'eligible_year' => Yii::t('app', 'Eligible Year'),
            'additional_qualification_id' => Yii::t('app', 'Additional Qualification ID'),
            'additional_subject_id' => Yii::t('app', 'Additional Subject ID'),
            'additional_university_id' => Yii::t('app', 'Additional University ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[AdditionalQualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalQualification()
    {
        return $this->hasOne(MstQualification::className(), ['id' => 'additional_qualification_id']);
    }

    /**
     * Gets query for [[AdditionalSubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalSubject()
    {
        return $this->hasOne(MstSubject::className(), ['id' => 'additional_subject_id']);
    }

    /**
     * Gets query for [[AdditionalUniversity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalUniversity()
    {
        return $this->hasOne(MstUniversity::className(), ['id' => 'additional_university_id']);
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
     * Gets query for [[PostCriteria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostCriteria()
    {
        return $this->hasOne(MstPostCriteria::className(), ['id' => 'post_criteria_id']);
    }

    /**
     * Gets query for [[Qualification]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualification()
    {
        return $this->hasOne(MstQualification::className(), ['id' => 'qualification_id']);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(MstSubject::className(), ['id' => 'subject_id']);
    }

    /**
     * Gets query for [[University]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUniversity()
    {
        return $this->hasOne(MstUniversity::className(), ['id' => 'university_id']);
    }
}
