<?php

namespace frontend\models;

use common\models\Applicant;
use common\models\ListType;
use Yii;
use yii\base\Model;

class ApplicantRegForm extends Model
{
    public $name;
    public $email;
    public $mobile;

    public function rules()
    {
        return [
            [['name', 'email', 'mobile'], 'required'],
            [['email'], 'unique'],
            [['mobile'], 'unique'],
            [['mobile'], 'string', 'max' =>10 ,'min' => 10 ,'tooLong' => 'Please enter a valid phone number.','tooShort' => 'Please enter a valid phone number.' ],
            [['mother_tongue_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['mother_tongue_id' => 'id']],
            [['nationality_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['nationality_id' => 'id']],
            [['religion_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['religion_id' => 'id']],
            [['domicile_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'mobile' => 'Mobile number',
            'email' => 'Email',
            'father_name' => 'Father Name ',
            'mother_name' => 'Mother Name',
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'marital_status' => 'Martial Status',
            'religion_id' => 'Religion',
            'nationality_id' => 'Nationality',
            'mother_tongue_id' => 'Mother Tongue',
            'is_domiciled' => 'Domiciled',
        ];
    }
}
