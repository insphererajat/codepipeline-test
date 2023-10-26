<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "exam_centre".
 *
 * @property int $id
 * @property string $guid
 * @property string|null $auth_key
 * @property string|null $api_auth_token
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property string|null $temp_password
 * @property int $recruitment_year
 * @property int $post_id
 * @property int $type 1-Prelim
 * @property string $name
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $address3
 * @property int $district_code
 * @property int $state_code
 * @property int $country_code
 * @property int|null $pincode
 * @property int|null $std_code
 * @property int|null $phone
 * @property int|null $fax
 * @property string|null $email
 * @property int|null $mobile
 * @property string|null $principal_name
 * @property int|null $capacity
 * @property int $total_room
 * @property int|null $room_size
 * @property int $hall
 * @property int|null $hall_size
 * @property int $is_cctv_available
 * @property int $status
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $is_email_verified
 * @property int|null $is_mobile_verified
 * @property int $is_active
 * @property int $is_deleted
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property int|null $created_on
 * @property int|null $modified_on
 *
 * @property MstDistrict $stateCode
 * @property User $createdBy
 * @property User $modifiedBy
 * @property MstPost $post
 * @property MstYear $recruitmentYear
 */
class ExamCentre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_centre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'recruitment_year', 'post_id', 'name', 'district_code', 'state_code', 'country_code', 'total_room', 'hall'], 'required'],
            [['recruitment_year', 'post_id', 'type', 'district_code', 'state_code', 'country_code', 'pincode', 'std_code', 'phone', 'fax', 'mobile', 'capacity', 'total_room', 'room_size', 'hall', 'hall_size', 'is_cctv_available', 'status', 'is_email_verified', 'is_mobile_verified', 'is_active', 'is_deleted', 'created_by', 'modified_by', 'created_on', 'modified_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['auth_key'], 'string', 'max' => 32],
            [['api_auth_token', 'password_hash', 'password_reset_token', 'address1', 'address2', 'address3'], 'string', 'max' => 255],
            [['temp_password', 'latitude', 'longitude'], 'string', 'max' => 50],
            [['name', 'principal_name'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['guid'], 'unique'],
            [['state_code', 'district_code'], 'exist', 'skipOnError' => true, 'targetClass' => MstDistrict::className(), 'targetAttribute' => ['state_code' => 'state_code', 'district_code' => 'code']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstPost::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['recruitment_year'], 'exist', 'skipOnError' => true, 'targetClass' => MstYear::className(), 'targetAttribute' => ['recruitment_year' => 'code']],
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
            'auth_key' => Yii::t('app', 'Auth Key'),
            'api_auth_token' => Yii::t('app', 'Api Auth Token'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'temp_password' => Yii::t('app', 'Temp Password'),
            'recruitment_year' => Yii::t('app', 'Recruitment Year'),
            'post_id' => Yii::t('app', 'Post ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'address1' => Yii::t('app', 'Address1'),
            'address2' => Yii::t('app', 'Address2'),
            'address3' => Yii::t('app', 'Address3'),
            'district_code' => Yii::t('app', 'District Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'country_code' => Yii::t('app', 'Country Code'),
            'pincode' => Yii::t('app', 'Pincode'),
            'std_code' => Yii::t('app', 'Std Code'),
            'phone' => Yii::t('app', 'Phone'),
            'fax' => Yii::t('app', 'Fax'),
            'email' => Yii::t('app', 'Email'),
            'mobile' => Yii::t('app', 'Mobile'),
            'principal_name' => Yii::t('app', 'Principal Name'),
            'capacity' => Yii::t('app', 'Capacity'),
            'total_room' => Yii::t('app', 'Total Room'),
            'room_size' => Yii::t('app', 'Room Size'),
            'hall' => Yii::t('app', 'Hall'),
            'hall_size' => Yii::t('app', 'Hall Size'),
            'is_cctv_available' => Yii::t('app', 'Is Cctv Available'),
            'status' => Yii::t('app', 'Status'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'is_email_verified' => Yii::t('app', 'Is Email Verified'),
            'is_mobile_verified' => Yii::t('app', 'Is Mobile Verified'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
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

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(MstPost::className(), ['id' => 'post_id']);
    }

    /**
     * Gets query for [[RecruitmentYear]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecruitmentYear()
    {
        return $this->hasOne(MstYear::className(), ['code' => 'recruitment_year']);
    }
}
