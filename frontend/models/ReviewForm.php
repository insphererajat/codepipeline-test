<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\ApplicantFee;
use common\models\ApplicantPost;
use common\models\ApplicantPostExamCentre;
use components\exceptions\AppException;
use common\models\location\MstDistrict;

/**
 * Description of ReviewForm
 *
 * @author Nitish
 */
class ReviewForm extends Model
{
    public $date;
    public $place;
    public $preference1;
    public $preference2;
    public $preference3;
    
    const SCENARIO_PREFERENCE = 'preference-required';
    
    public function rules()
    {
        return [
            [['place'], 'match', 'pattern' => \components\Helper::alphabetRegex(), 'message' => Yii::t('app', 'alphabet')],
            [['date', 'place'], 'required'],
            [['date', 'place'], 'string'],
            [['preference1', 'preference2', 'preference3'], 'integer'],
            [['preference1', 'preference2', 'preference3'], 'required', 'on' => self::SCENARIO_PREFERENCE],
        ];
    }
    
    public function loadDetails($applicantFeeId)
    {
        $applicantFeeId = Yii::$app->security->validateData($applicantFeeId, Yii::$app->params['hashKey']);
        $applicantFeeModel = ApplicantFee::findById($applicantFeeId);
        if(empty($applicantFeeModel)){
            return;
        }
        $applicantPostModel = ApplicantPost::findById($applicantFeeModel['applicant_post_id']);
        
        if(empty($applicantPostModel)){
            return;
        }
        $this->place = $applicantPostModel['place'];
        $this->date = $applicantPostModel['date'];
        
        $preference1 = ApplicantPostExamCentre::findByApplicantPostId($applicantFeeModel['applicant_post_id'], [
                'preference' => ApplicantPostExamCentre::PREFERENCE_1
        ]);
        
        $this->preference1 = $preference1['district_code'];
        
        $preference2 = ApplicantPostExamCentre::findByApplicantPostId($applicantFeeModel['applicant_post_id'], [
                'preference' => ApplicantPostExamCentre::PREFERENCE_2
        ]);
        
        $this->preference2 = $preference2['district_code'];
        
        $preference3 = ApplicantPostExamCentre::findByApplicantPostId($applicantFeeModel['applicant_post_id'], [
                'preference' => ApplicantPostExamCentre::PREFERENCE_3
        ]);
        
        $this->preference3 = $preference3['district_code'];
    }

    public function saveRecord($applicantId, $params = [])
    {
        $applicantPostModel = ApplicantPost::findById($params['applicantPostId'], [
            'applicantId' => $applicantId,
            'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
        ]);
        
        if(empty($applicantPostModel)){
            throw new AppException('Oops! applicant post model empty while saving review form');
        }
        
        $applicantPostModel->date = $this->date;
        $applicantPostModel->place = $this->place;
        if($applicantPostModel->save(true, ['date', 'place'])){
            if (false && \yii\helpers\ArrayHelper::isIn($applicantPostModel->classified_id, [RegistrationForm::SCENARIO_4])) {
                $districtModel = MstDistrict::findByCode($this->preference1);
                $preference1 = ApplicantPostExamCentre::findByApplicantPostId($applicantPostModel->id, [
                            'preference' => ApplicantPostExamCentre::PREFERENCE_1,
                            'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
                ]);

                if (empty($preference1)) {
                    $preference1 = new ApplicantPostExamCentre;
                    $preference1->applicant_post_id = $applicantPostModel->id;
                    $preference1->preference = ApplicantPostExamCentre::PREFERENCE_1;
                }
                $preference1->district_code = $this->preference1;
                $preference1->state_code = $districtModel['state_code'];
                $preference1->save();

                $districtModel = MstDistrict::findByCode($this->preference2);
                $preference2 = ApplicantPostExamCentre::findByApplicantPostId($applicantPostModel->id, [
                            'preference' => ApplicantPostExamCentre::PREFERENCE_2,
                            'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
                ]);
                if (empty($preference2)) {
                    $preference2 = new ApplicantPostExamCentre;
                    $preference2->applicant_post_id = $applicantPostModel->id;
                    $preference2->preference = ApplicantPostExamCentre::PREFERENCE_2;
                }
                $preference2->district_code = $this->preference2;
                $preference2->state_code = $districtModel['state_code'];
                $preference2->save();

                $districtModel = MstDistrict::findByCode($this->preference3);
                $preference3 = ApplicantPostExamCentre::findByApplicantPostId($applicantPostModel->id, [
                            'preference' => ApplicantPostExamCentre::PREFERENCE_3,
                            'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
                ]);
                if (empty($preference3)) {
                    $preference3 = new ApplicantPostExamCentre;
                    $preference3->applicant_post_id = $applicantPostModel->id;
                    $preference3->preference = ApplicantPostExamCentre::PREFERENCE_3;
                }
                $preference3->district_code = $this->preference3;
                $preference3->state_code = $districtModel['state_code'];
                $preference3->save();
            }

            return true;
        }
        
        return false;
    }
}
