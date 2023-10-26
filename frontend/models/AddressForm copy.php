<?php

namespace frontend\models;

use Codeception\Lib\Connector\Yii2;
use common\models\Applicant;
use common\models\ApplicantAddress;
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
 
    public function rules()
    {
        return [
            [['house','area','landmark','state_code','district_code','pincode',], 'required']
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
        ];
    }

    public function saveData()
    {
        $same_as_present_address = $this->same_as_present_address;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
        /*     if (!empty($guid)) {
                // $model = ApplicantAddress::findByGuid($guid, ['resultFormat' => 'object']);
                // if ($model === NULL) {
                //     throw new \components\exceptions\AppException("Oops! The block model you are trying to access doesn't exist.");
                // }
            } else {
                $model = new ApplicantAddress();
                $model->loadDefaultValues(TRUE);
            } */
            // $model->attributes = $this->attributes;
            // if (!$model->save()) {
            //     $this->addErrors($model->getErrors());
            //     return FALSE;
            // }

            // $model->save();

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public function getData($id, $address_type = ApplicantAddress::CURRENT_ADDRESS )
    {
        $model = ApplicantAddress::getAddressByTypeAndApplicantId($id, $address_type, ['resultFormat' => 'object']);

        if(!empty($model)){
            $this->id = $model->id;
            $this->applicant_id = $model->applicant_id;
            $this->address_type = $model->address_type;
            $this->house = $model->house;
            $this->area = $model->area;
            $this->landmark = $model->landmark;
            $this->state_code = $model->state_code;
            $this->district_code = $model->district_code;
            $this->pincode = $model->pincode;
        }
        return $this;
    }
}
