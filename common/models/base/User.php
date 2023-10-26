<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $guid
 * @property int $role_id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $firstname
 * @property string|null $lastname
 * @property string $email
 * @property int $status
 * @property int $is_deleted
 * @property int|null $last_login
 * @property string|null $password_hash1
 * @property string|null $password_hash2
 * @property string|null $password_hash3
 * @property int|null $failed_attempt
 * @property int|null $failed_timestamp
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property int|null $modified_on
 * @property int|null $created_on
 * @property string|null $verification_token
 *
 * @property ExamCentre[] $examCentres
 * @property ExamCentre[] $examCentres0
 * @property LogUserActivity[] $logUserActivities
 * @property MstClassified[] $mstClassifieds
 * @property MstClassified[] $mstClassifieds0
 * @property MstDepartment[] $mstDepartments
 * @property MstDepartment[] $mstDepartments0
 * @property MstDistrict[] $mstDistricts
 * @property MstDistrict[] $mstDistricts0
 * @property MstListType[] $mstListTypes
 * @property MstListType[] $mstListTypes0
 * @property MstPost[] $mstPosts
 * @property MstPost[] $mstPosts0
 * @property MstPostCriteria[] $mstPostCriterias
 * @property MstPostCriteria[] $mstPostCriterias0
 * @property MstPostFee[] $mstPostFees
 * @property MstPostFee[] $mstPostFees0
 * @property MstPostQualification[] $mstPostQualifications
 * @property MstPostQualification[] $mstPostQualifications0
 * @property MstQualification[] $mstQualifications
 * @property MstQualification[] $mstQualifications0
 * @property MstQualificationSubject[] $mstQualificationSubjects
 * @property MstState[] $mstStates
 * @property MstState[] $mstStates0
 * @property MstSubject[] $mstSubjects
 * @property MstSubject[] $mstSubjects0
 * @property MstTehsil[] $mstTehsils
 * @property MstTehsil[] $mstTehsils0
 * @property MstUniversity[] $mstUniversities
 * @property MstUniversity[] $mstUniversities0
 * @property MstYear[] $mstYears
 * @property MstYear[] $mstYears0
 * @property Page[] $pages
 * @property Page[] $pages0
 * @property TeamUser[] $teamUsers
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'role_id', 'username', 'auth_key', 'password_hash', 'firstname', 'email'], 'required'],
            [['role_id', 'status', 'is_deleted', 'last_login', 'failed_attempt', 'failed_timestamp', 'created_by', 'modified_by', 'modified_on', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['username', 'password_hash', 'password_reset_token', 'firstname', 'lastname', 'email', 'password_hash1', 'password_hash2', 'password_hash3', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['guid'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'role_id' => Yii::t('app', 'Role ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'last_login' => Yii::t('app', 'Last Login'),
            'password_hash1' => Yii::t('app', 'Password Hash1'),
            'password_hash2' => Yii::t('app', 'Password Hash2'),
            'password_hash3' => Yii::t('app', 'Password Hash3'),
            'failed_attempt' => Yii::t('app', 'Failed Attempt'),
            'failed_timestamp' => Yii::t('app', 'Failed Timestamp'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
            'created_on' => Yii::t('app', 'Created On'),
            'verification_token' => Yii::t('app', 'Verification Token'),
        ];
    }

    /**
     * Gets query for [[ExamCentres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentres()
    {
        return $this->hasMany(ExamCentre::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ExamCentres0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamCentres0()
    {
        return $this->hasMany(ExamCentre::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[LogUserActivities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogUserActivities()
    {
        return $this->hasMany(LogUserActivity::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[MstClassifieds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstClassifieds()
    {
        return $this->hasMany(MstClassified::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstClassifieds0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstClassifieds0()
    {
        return $this->hasMany(MstClassified::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstDepartments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstDepartments()
    {
        return $this->hasMany(MstDepartment::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstDepartments0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstDepartments0()
    {
        return $this->hasMany(MstDepartment::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstDistricts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstDistricts()
    {
        return $this->hasMany(MstDistrict::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstDistricts0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstDistricts0()
    {
        return $this->hasMany(MstDistrict::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstListTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstListTypes()
    {
        return $this->hasMany(MstListType::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstListTypes0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstListTypes0()
    {
        return $this->hasMany(MstListType::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPosts()
    {
        return $this->hasMany(MstPost::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstPosts0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPosts0()
    {
        return $this->hasMany(MstPost::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstPostCriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostCriterias()
    {
        return $this->hasMany(MstPostCriteria::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstPostCriterias0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostCriterias0()
    {
        return $this->hasMany(MstPostCriteria::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstPostFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostFees()
    {
        return $this->hasMany(MstPostFee::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstPostFees0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostFees0()
    {
        return $this->hasMany(MstPostFee::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications()
    {
        return $this->hasMany(MstPostQualification::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstPostQualifications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstPostQualifications0()
    {
        return $this->hasMany(MstPostQualification::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstQualifications()
    {
        return $this->hasMany(MstQualification::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstQualifications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstQualifications0()
    {
        return $this->hasMany(MstQualification::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstQualificationSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstQualificationSubjects()
    {
        return $this->hasMany(MstQualificationSubject::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstStates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstStates()
    {
        return $this->hasMany(MstState::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstStates0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstStates0()
    {
        return $this->hasMany(MstState::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstSubjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstSubjects()
    {
        return $this->hasMany(MstSubject::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstSubjects0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstSubjects0()
    {
        return $this->hasMany(MstSubject::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstTehsils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstTehsils()
    {
        return $this->hasMany(MstTehsil::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstTehsils0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstTehsils0()
    {
        return $this->hasMany(MstTehsil::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstUniversities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstUniversities()
    {
        return $this->hasMany(MstUniversity::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstUniversities0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstUniversities0()
    {
        return $this->hasMany(MstUniversity::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[MstYears]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstYears()
    {
        return $this->hasMany(MstYear::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[MstYears0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMstYears0()
    {
        return $this->hasMany(MstYear::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[Pages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Pages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPages0()
    {
        return $this->hasMany(Page::className(), ['modified_by' => 'id']);
    }

    /**
     * Gets query for [[TeamUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamUsers()
    {
        return $this->hasMany(TeamUser::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
}
