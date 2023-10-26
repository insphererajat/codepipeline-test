<?php

namespace components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Component;

/**
 * Description of Criteria
 *
 * @author Amit Handa
 */
class Criteria extends Component
{
    
    const UNRESERVED = 11;
    const SC = 12;
    const ST = 14;
    const EWS = 13;
    const OBC = 15;
    const DISABILITY = 111;
    const UNRESERVED_EX_SERVICEMAN = 222;
    const DOB_MAX_DATE = '2022-01-01';
    
    const SELECT_TYPE_YES = 1;
    const SELECT_TYPE_NO = 0;

    private $_categoriesWiseAge = [
        self::UNRESERVED => [
            'min' => 21,
            'max' => 42,
            'amt' => 300
        ],
        self::SC => [
            'min' => 21,
            'max' => 47,
            'amt' => 150
        ],
        self::ST => [
            'min' => 21,
            'max' => 47,
            'amt' => 150
        ],
        self::EWS => [
            'min' => 21,
            'max' => 42,
            'amt' => 150
        ],
        self::OBC => [
            'min' => 21,
            'max' => 47,
            'amt' => 300
        ],
        self::DISABILITY => [
            'min' => 21,
            'max' => 52,
            'amt' => 150
        ],
        self::UNRESERVED_EX_SERVICEMAN => [
            'min' => 21,
            'max' => 58,
            'amt' => 300
        ]
    ];

    public function validateAge($params)
    {
        $applicantId = Yii::$app->applicant->id;
        $applicantPost = \common\models\ApplicantPost::findByApplicantId($applicantId, ['postId' => \common\models\MstPost::MASTER_POST]);
        $applicantDetail = \common\models\ApplicantDetail::findByApplicantPostId($applicantPost['id'], ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($applicantDetail === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }

        $age = $this->calculateAge($applicantDetail->date_of_birth);
        $extendAge = 0;
        if (isset($params['is_govt_teacher']) && $params['is_govt_teacher'] == self::SELECT_TYPE_YES) {
            $extendAge = $this->calculateAge($params['date_of_joining']);
            $age += $extendAge;

            if ($applicantDetail->social_category_id == self::UNRESERVED) {
                if ($age >= $this->_categoriesWiseAge[self::UNRESERVED_EX_SERVICEMAN]['min'] && $age <= $this->_categoriesWiseAge[self::UNRESERVED_EX_SERVICEMAN]['max']) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }

        if ($applicantDetail->disability_id != \common\models\MstListType::NOT_APPLICABLE) {
            if ($age >= $this->_categoriesWiseAge[self::DISABILITY]['min'] && $age <= $this->_categoriesWiseAge[self::DISABILITY]['max']) {
                return 1;
            } else {
                return 0;
            }
        } else if ($age >= $this->_categoriesWiseAge[$applicantDetail->social_category_id]['min'] && $age <= $this->_categoriesWiseAge[$applicantDetail->social_category_id]['max']) {
            return 1;
        }
        return 0;
    }
    
    public function calculateAge($dob)
    {
        return date_diff(date_create($dob), date_create(self::DOB_MAX_DATE))->y;
    }
    
    public function calculatePayment($params)
    {
        $applicantId = Yii::$app->applicant->id;
        $applicantPost = \common\models\ApplicantPost::findByApplicantId($applicantId, ['postId' => \common\models\MstPost::MASTER_POST]);
        $applicantDetail = \common\models\ApplicantDetail::findByApplicantPostId($applicantPost['id'], ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($applicantDetail === null) {
            throw new \components\exceptions\AppException(Yii::t('app', 'invalid.request'));
        }
        
        if ($applicantDetail->disability_id != \common\models\MstListType::NOT_APPLICABLE) {
            return $this->_categoriesWiseAge[self::DISABILITY]['amt'];
        }
        
        if (isset($params['is_govt_teacher']) && $params['is_govt_teacher'] == self::SELECT_TYPE_YES) {

            if ($applicantDetail->social_category_id == self::UNRESERVED) {
                return $this->_categoriesWiseAge[self::UNRESERVED_EX_SERVICEMAN]['amt'];
            }
        }

        $this->_categoriesWiseAge[$applicantDetail->social_category_id]['amt'];
    }

}
