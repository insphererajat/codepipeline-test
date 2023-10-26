<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "log_user_activity".
 *
 * @property int $id
 * @property string $guid
 * @property int|null $user_id
 * @property int|null $applicant_id
 * @property int|null $type
 * @property string|null $device_type
 * @property string|null $ip_address
 * @property string|null $useragent
 * @property int|null $status
 * @property int|null $created_on
 *
 * @property Applicant $applicant
 * @property User $user
 */
class LogUserActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_user_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid'], 'required'],
            [['user_id', 'applicant_id', 'type', 'status', 'created_on'], 'integer'],
            [['guid'], 'string', 'max' => 36],
            [['device_type'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 15],
            [['useragent'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['applicant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applicant::className(), 'targetAttribute' => ['applicant_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User ID'),
            'applicant_id' => Yii::t('app', 'Applicant ID'),
            'type' => Yii::t('app', 'Type'),
            'device_type' => Yii::t('app', 'Device Type'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'useragent' => Yii::t('app', 'Useragent'),
            'status' => Yii::t('app', 'Status'),
            'created_on' => Yii::t('app', 'Created On'),
        ];
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
