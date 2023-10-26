<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;

/**
 * Description of VerificationForm
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class VerificationForm extends \yii\base\Model
{

    public $email;
    public $mobile;
    public $email_otp;
    public $mobile_otp;
    public $is_email_verified;
    public $is_mobile_verified;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'mobile', 'email_otp', 'mobile_otp'], 'string'],
            [['is_email_verified', 'is_mobile_verified'], 'integer'],
        ];
    }

}
