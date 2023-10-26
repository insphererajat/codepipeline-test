<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "applicant_address".
 *
 * @property int $id
 * @property int $applicant_post_id
 * @property int|null $address_type
 * @property string|null $house_no
 * @property string|null $premises_name
 * @property string|null $street
 * @property string|null $area
 * @property string|null $landmark
 * @property int $state_code
 * @property int $district_code
 * @property int|null $tehsil_code
 * @property string|null $tehsil_name
 * @property string|null $village_name
 * @property string|null $pincode
 * @property string|null $nearest_police_station
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property ApplicantPost $applicantPost
 * @property MstDistrict $stateCode
 * @property MstTehsil $tehsilCode
 */
class ApplicantAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'applicant_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['applicant_post_id', 'state_code', 'district_code'], 'required'],
            [['applicant_post_id', 'address_type', 'state_code', 'district_code', 'tehsil_code', 'created_on', 'modified_on'], 'integer'],
            [['house_no', 'premises_name', 'street', 'area', 'landmark', 'village_name'], 'string', 'max' => 255],
            [['tehsil_name'], 'string', 'max' => 64],
            [['pincode'], 'string', 'max' => 6],
            [['nearest_police_station'], 'string', 'max' => 100],
            [['applicant_post_id', 'address_type'], 'unique', 'targetAttribute' => ['applicant_post_id', 'address_type']],
            [['applicant_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicantPost::className(), 'targetAttribute' => ['applicant_post_id' => 'id']],
            [['state_code', 'district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['state_code' => 'state_code', 'district_code' => 'code']],
            [['tehsil_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstTehsil::className(), 'targetAttribute' => ['tehsil_code' => 'code']],
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
            'address_type' => Yii::t('app', 'Address Type'),
            'house_no' => Yii::t('app', 'House No'),
            'premises_name' => Yii::t('app', 'Premises Name'),
            'street' => Yii::t('app', 'Street'),
            'area' => Yii::t('app', 'Area'),
            'landmark' => Yii::t('app', 'Landmark'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'tehsil_code' => Yii::t('app', 'Tehsil Code'),
            'tehsil_name' => Yii::t('app', 'Tehsil Name'),
            'village_name' => Yii::t('app', 'Village Name'),
            'pincode' => Yii::t('app', 'Pincode'),
            'nearest_police_station' => Yii::t('app', 'Nearest Police Station'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
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

    /**
     * Gets query for [[TehsilCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTehsilCode()
    {
        return $this->hasOne(MstTehsil::className(), ['code' => 'tehsil_code']);
    }
}
