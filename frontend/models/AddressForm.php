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

class AddressForm extends Model
{
    // address check
    public $same_as_present_address;
    // for current address
    public $id;
    public $address_type;
    public $applicant_id;
    public $house;
    public $area;
    public $landmark;
    public $state_code;
    public $district_code;
    public $pincode;

    // for permanent address
    public $permanent_id;
    public $permanent_address_type;
    public $permanent_applicant_id;
    public $permanent_house;
    public $permanent_area;
    public $permanent_landmark;
    public $permanent_state_code;
    public $permanent_district_code;
    public $permanent_pincode;



    public function rules()
    {
        return [
            [['house', 'area', 'landmark', 'state_code', 'district_code', 'pincode',], 'required'],
            [
                [
                    'permanent_house', 'permanent_area', 'permanent_landmark', 'permanent_state_code', 'permanent_district_code', 'permanent_pincode'
                ], 'required', 'when' => function ($model) {
                    return $model->same_as_present_address != '1'; 
                }, 'whenClient' => "function (attribute, value) {
                    return $('#addressform-same_as_present_address:checked').length != '1';
                    }"
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'house' => 'House/Door/Flat No. is required',
            'area' => 'Street/Locality/Area Name is required',
            'landmark' => 'Village/Ward No/Landmark is required',
            'state_code' => 'State/UT is required',
            'district_code' => 'District is required',
            'pincode' => 'Pincode is required',

            'permanent_house' => 'House/Door/Flat No. is required',
            'permanent_area' => 'Street/Locality/Area Name is required',
            'permanent_landmark' => 'Village/Ward No/Landmark is required',
            'permanent_state_code' => 'State/UT is required',
            'permanent_district_code' => 'District is required',
            'permanent_pincode' => 'Pincode is required',
        ];
    }

    public function saveData()
    {
        $same_as_present_address = (int)$this->same_as_present_address;
        // return ['oooooo' => $same_as_present_address];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->applicant_id = Yii::$app->applicant->id;
            $this->permanent_applicant_id = Yii::$app->applicant->id;
            if (!empty($this->id)) {
                $model = ApplicantAddress::findById($this->id, ['resultFormat' =>  ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The model you are trying to access doesn't exist.");
                }
            } else {
                $model = new ApplicantAddress();
                $model->loadDefaultValues(TRUE);
            }

            $model->id = $this->id;
            $model->applicant_id =  Yii::$app->applicant->id;
            $model->address_type = ApplicantAddress::CURRENT_ADDRESS;
            $model->house = $this->house;
            $model->area = $this->area;
            $model->landmark = $this->landmark;
            $model->state_code = $this->state_code;
            $model->district_code = $this->district_code;
            $model->pincode = $this->pincode;

            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                if($same_as_present_address == 1){
                    return FALSE;
                }
            }

            if ($same_as_present_address == 0 ) {
                if (!empty($this->permanent_id)) {
                    $model = ApplicantAddress::findById($this->permanent_id, ['resultFormat' =>  ModelCache::RETURN_TYPE_OBJECT]);
                    if ($model === NULL) {
                        throw new \components\exceptions\AppException("Oops! The model you are trying to access doesn't exist.");
                    }
                } else {
                    $model = new ApplicantAddress();
                    $model->loadDefaultValues(TRUE);
                }

                $model->id =  $this->permanent_id;
                $model->address_type =  ApplicantAddress::PERMANENT_ADDRESS;;
                $model->applicant_id = Yii::$app->applicant->id;
                $model->house =  $this->permanent_house;
                $model->area =  $this->permanent_area;
                $model->landmark =  $this->permanent_landmark;
                $model->state_code =  $this->permanent_state_code;
                $model->district_code =  $this->permanent_district_code;
                $model->pincode =  $this->permanent_pincode;

                if (!$model->save()) {
                    $this->addErrors($model->getErrors());
                    return FALSE;
                }
            }

            $applicant = Applicant::findOne(Yii::$app->applicant->id);
            $applicant->same_as_present_address = (int)$same_as_present_address;
            if(!$applicant->save()){
                throw new \components\exceptions\AppException("Opps! there was a problem in updating applicant information.");
            }

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public function getData($id)
    {
        $applicant = Applicant::findById($id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        $this->same_as_present_address = (int) $applicant->same_as_present_address;
        // return $applicant;
        // return $this->same_as_present_address;

        $model = ApplicantAddress::getAddressByTypeAndApplicantId($id, ApplicantAddress::CURRENT_ADDRESS, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            $model = new ApplicantAddress();
            $model->applicant_id = $id;
        }

        if (!empty($this->same_as_present_address) && $this->same_as_present_address != 0) {
            $model_permanent_address = ApplicantAddress::getAddressByTypeAndApplicantId($id, ApplicantAddress::PERMANENT_ADDRESS, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
            if ($model_permanent_address == null) {
                $model_permanent_address = new ApplicantAddress();
                $model_permanent_address->applicant_id = $id;
            }
        }

        if (!empty($model) && !empty($model_permanent_address)) {
            $this->id = $model->id;
            $this->applicant_id = $model->applicant_id;
            $this->address_type = $model->address_type;
            $this->house = $model->house;
            $this->area = $model->area;
            $this->landmark = $model->landmark;
            $this->state_code = $model->state_code;
            $this->district_code = $model->district_code;
            $this->pincode = $model->pincode;

            if ($this->same_as_present_address) {
                $this->permanent_id = $model->id;
                $this->permanent_applicant_id = $model->applicant_id;
                $this->permanent_address_type = $model->address_type;
                $this->permanent_house = $model->house;
                $this->permanent_area = $model->area;
                $this->permanent_landmark = $model->landmark;
                $this->permanent_state_code = $model->state_code;
                $this->permanent_district_code = $model->district_code;
                $this->permanent_pincode = $model->pincode;
            } else {
                $this->permanent_id = $model_permanent_address->id;
                $this->permanent_applicant_id = $model_permanent_address->applicant_id;
                $this->permanent_address_type = $model_permanent_address->address_type;
                $this->permanent_house = $model_permanent_address->house;
                $this->permanent_area = $model_permanent_address->area;
                $this->permanent_landmark = $model_permanent_address->landmark;
                $this->permanent_state_code = $model_permanent_address->state_code;
                $this->permanent_district_code = $model_permanent_address->district_code;
                $this->permanent_pincode = $model_permanent_address->pincode;
            }
        }
        return $this;
    }
}
