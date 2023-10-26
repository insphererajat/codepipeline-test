<?php

namespace frontend\models;

use common\models\Applicant;
use Yii;
use yii\base\Model;

class VerifyApplicantForm extends Applicant
{
    public $name;
    public $email;
    public $mobile;

    public function rules()
    {
        return [
            [['name', 'email', 'mobile'],'required'],
            ['email', 'email'],
            ['name', 'string', 'min' => 3],
            [['mobile'], 'string', 'max' =>10 ,'min' => 10 ,
            'tooLong' => 'Please enter a valid phone number.',
            'tooShort' => 'Please enter a valid phone number.' ],
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
        ];
    }
}
