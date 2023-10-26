<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_post_exam_centre".
 *
 * @property int $id
 * @property int $applicant_post_id
 * @property int|null $state_code
 * @property int|null $district_code
 * @property int|null $preference
 * @property int|null $allocation_state_code
 * @property int|null $allocation_district_code
 * @property int|null $allocation_preference
 * @property int|null $created_on
 * @property int|null $created_by
 *
 * @property MstDistrict $allocationStateCode
 * @property ApplicantPost $applicantPost
 * @property MstDistrict $stateCode
 */
class ApplicantPostExamCentre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_post_exam_centre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id'], 'required'],
            [['applicant_post_id', 'state_code', 'district_code', 'preference', 'allocation_state_code', 'allocation_district_code', 'allocation_preference', 'created_on', 'created_by'], 'integer'],
            [['applicant_post_id', 'state_code', 'district_code'], 'unique', 'targetAttribute' => ['applicant_post_id', 'state_code', 'district_code']],
            [['allocation_state_code', 'allocation_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['allocation_state_code' => 'state_code', 'allocation_district_code' => 'code']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['state_code', 'district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['state_code' => 'state_code', 'district_code' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant_post_id' => Yii::t('app', 'Applicant Post ID'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'preference' => Yii::t('app', 'Preference'),
            'allocation_state_code' => Yii::t('app', 'Allocation State Code'),
            'allocation_district_code' => Yii::t('app', 'Allocation District Code'),
            'allocation_preference' => Yii::t('app', 'Allocation Preference'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[AllocationStateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAllocationStateCode()
    {
        return $this->hasOne(MstDistrict::className(), ['state_code' => 'allocation_state_code', 'code' => 'allocation_district_code']);
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

    /**
     * Gets query for [[StateCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode()
    {
        return $this->hasOne(MstDistrict::className(), ['state_code' => 'state_code', 'code' => 'district_code']);
    }
}
