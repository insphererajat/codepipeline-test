<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use common\models\ApplicantDetail;
use common\models\caching\ModelCache;

/**
 * Log Activity Form
 */
class LogActivityForm extends Model
{
    public $name;
    public $mother_name;
    public $birth_state_code;
    public $birth_district_code;
    public $date_of_birth;
    public $reCaptcha;
    
    public $_applicant;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mother_name', 'name', 'birth_state_code', 'birth_district_code', 'date_of_birth'], 'required'],
            [['mother_name', 'name'], 'trim'],
            [['date_of_birth'], 'safe'],
            [['birth_state_code', 'birth_district_code'], 'integer'],
            [['mother_name', 'name'], 'string', 'max' => 255],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Candidate Name',
            'mother_name' => 'Mother`s/Orphanage Name',
            'birth_state_code' => 'Birth/Orphanage State',
            'birth_district_code' => 'Birth/Orphanage District',
            'date_of_birth' => 'Date of Birth',
            'captcha' => 'Security Code'
        ];
    }
    
    public function applicant()
    {
        if ($this->_applicant == null) {

            $applicantDetail = ApplicantDetail::findByDateOfBirth($this->date_of_birth, [
                        'selectCols' => ['applicant_post.id as post_id', 'applicant_post.applicant_id', 'applicant_detail.id', 'applicant_detail.mother_name', 'applicant_detail.birth_state_code', 'applicant_detail.birth_district_code', 'applicant_detail.date_of_birth'],
                        'joinWithApplicantPost' => 'innerJoin',
                        'postId' => \common\models\MstPost::MASTER_POST,
                        'joinWithApplicant' => 'innerJoin',
                        'applicantName' => $this->name,
                        'birthStateCode' => $this->birth_state_code,
                        'birthDistrictCode' => $this->birth_district_code,
            ]);
            
            if ($applicantDetail == null) {
                return false;
            }
            
            if (empty($applicantDetail['is_orphan']) && strtolower(trim($applicantDetail['mother_name'])) != strtolower(trim($this->mother_name))) {
                return false;
            }

            $this->_applicant = Applicant::findById($applicantDetail['applicant_id'], ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            return true;
        }
    }
}
