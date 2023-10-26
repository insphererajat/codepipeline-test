<?php
namespace frontend\models;
use yii\base\Model;

/**
 * Log Activity Form
 */
class AdmitCardForm extends Model
{
    public $reCaptcha;
    public $search;
    public $classified_id;
    
    public $_applicant;
    public $guid;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search'], 'string', 'min' => 10, 'max' => 10],
            ['classified_id', 'required'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reCaptcha' => 'Security Code'
        ];
    }
    
    public function applicant()
    {
        if ($this->_applicant == null) {
            $applicantDetail = \common\models\ApplicantPost::findByClassifiedId($this->classified_id, [
                        'selectCols' => ['applicant_post.guid as guid', 'applicant_post.applicant_id'],
                        'joinWithApplicant' => 'innerJoin',
                        'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED,
                        'mobile' => $this->search
            ]);
            
            if ($applicantDetail == null) {
                return false;
            }

            $this->guid = $applicantDetail['guid'];
            return true;
        }
    }
}