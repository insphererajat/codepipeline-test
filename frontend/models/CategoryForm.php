<?php

namespace frontend\models;

use Codeception\Lib\Connector\Yii2;
use common\models\Applicant;
use common\models\ApplicantAddress;
use common\models\base\ApplicantAddress as CommonApplicantAddress;
use common\models\caching\ModelCache;
use common\models\ListType;
use common\models\MstListType;
use Yii;
use yii\base\Model;

class CategoryForm extends Model
{

    public $id;
    public $guid;

    public $social_category_id;
    public $social_category_certificate_issue_authority;
    public $social_category_certificate_issue_date;
    public $social_category_certificate_number;
    public $social_category_certificate_district_code;
    public $social_category_certificate_country_code;
    public $social_category_certificate_state_code;

    // public $is_disabled;
    public $disability_id;
    public $is_exserviceman;
    public $is_dependent_on_freedom_fighter;
    public $have_served_territorial_army;
    public $claimed_category;


    public function rules()
    {
        return [
            [
                [
                    'social_category_id', 'disability_id', 'is_exserviceman',
                    'is_dependent_on_freedom_fighter',
                    'have_served_territorial_army',
                    'claimed_category', 'disability_id'
                ],  'required'
            ],
            [
                [
                    "social_category_certificate_issue_authority", "social_category_certificate_issue_date",
                    "social_category_certificate_number", "social_category_certificate_district_code"
                ], 'required', 'when' => function ($model) {
                    return $model->social_category_id != '1'; // 1 is General
                }, 'whenClient' => "function (attribute, value) {
                    return $('#categoryform-social_category_id').val() !== '1';
                    }"
            ]
        ];
    }

    // Array ( [social_category_certificate_district_code] => Array ( [0] => Social Category Certificate District Code is invalid. ) )

    public function saveData()
    {
        $id = $this->id;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!empty($id)) {
                $model = Applicant::findById($id, ['resultFormat' =>  ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The model you are trying to access doesn't exist.");
                }
            }
            $model->attributes = $this->attributes;
            $model->social_category_certificate_issue_date = date_format(date_create($this->social_category_certificate_issue_date), 'Y-m-d');
            if($this->social_category_certificate_district_code){
                $district = \common\models\MstDistrict::findByCode($this->social_category_certificate_district_code,['resultFormat' => 'object']);
                $model->social_category_certificate_district_code = $this->social_category_certificate_district_code;
                $model->social_category_certificate_state_code = $district->state_code;
                $model->social_category_certificate_country_code = $district->country_code;
            }
            // return $model;
            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                return FALSE;
            }
            $model->save();

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public function getData($id)
    {
        $applicantModel = Applicant::findById($id, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if (!empty($applicantModel)) {
            $this->attributes = $applicantModel->attributes;
            $this->id = $applicantModel->id;
            $this->guid = $applicantModel->guid;
        }
        return $this;
    }
}
