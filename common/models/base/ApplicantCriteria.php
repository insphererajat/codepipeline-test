<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_criteria".
 *
 * @property int $id
 * @property int $applicant_id
 * @property int $applicant_post_id
 * @property int|null $applicant_post_detail_id
 * @property string|null $field1
 * @property string|null $field2
 * @property string|null $field3
 * @property string|null $field4
 * @property string|null $field5
 * @property string|null $field6
 * @property string|null $field7
 * @property string|null $field8
 * @property string|null $field9
 * @property string|null $field10
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantPostDetail $applicantPostDetail
 * @property Applicant $applicant
 * @property ApplicantPost $applicantPost
 */
class ApplicantCriteria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_criteria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_id', 'applicant_post_id'], 'required'],
            [['applicant_id', 'applicant_post_id', 'applicant_post_detail_id', 'created_on', 'modified_on'], 'integer'],
            [['field1', 'field3', 'field4', 'field5', 'field6', 'field7', 'field8', 'field9', 'field10'], 'string', 'max' => 255],
            [['field2'], 'string', 'max' => 1000],
            [['applicant_post_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPostDetail::className(), 'targetAttribute' => ['applicant_post_detail_id' => 'id']],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant_id' => Yii::t('app', 'Applicant ID'),
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'applicant_post_detail_id' => Yii::t('app', 'Applicant Post Detail ID'),
            'field1' => Yii::t('app', 'Field1'),
            'field2' => Yii::t('app', 'Field2'),
            'field3' => Yii::t('app', 'Field3'),
            'field4' => Yii::t('app', 'Field4'),
            'field5' => Yii::t('app', 'Field5'),
            'field6' => Yii::t('app', 'Field6'),
            'field7' => Yii::t('app', 'Field7'),
            'field8' => Yii::t('app', 'Field8'),
            'field9' => Yii::t('app', 'Field9'),
            'field10' => Yii::t('app', 'Field10'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
     * Gets query for [[ApplicantPostDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPostDetail()
    {
        return $this->hasOne(ApplicantPostDetail::className(), ['id' => 'applicant_post_detail_id']);
    }

    /**
     * Gets query for [[Applicant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
    }

    /**
     * Gets query for [[ApplicantPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicantPost()
    {
        return $this->hasOne(ApplicantPost::className(), ['id' => 'applicant_post_id']);
    }
}
