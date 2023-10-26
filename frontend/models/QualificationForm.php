<?php

namespace frontend\models;

use Codeception\Lib\Connector\Yii2;
use common\models\Applicant;
use common\models\ApplicantAddress;
use common\models\ApplicantQualification;
use common\models\base\ApplicantAddress as CommonApplicantAddress;
use common\models\caching\ModelCache;
use common\models\ListType;
use common\models\MstListType;
use Yii;
use yii\base\Model;

class QualificationForm extends Model
{
    public $id;
    public $applicant_id;
    public $qualification_type;
    public $qualification_name;

    public $board_university;
    public $university_state;
    public $university_country;

    public $grade;
    public $subject;
    public $mark_type;
    public $total_marks;
    public $obtained_marks;
    public $date_of_marksheet;


    public function rules()
    {
        return [
            [[
                'applicant_id',
                'qualification_type',
                'qualification_name',

                'board_university',

                'grade',
                'subject',
                'mark_type',
                'total_marks',
                'obtained_marks',
                'date_of_marksheet'
            ], 'required'],
            [['total_marks', 'obtained_marks'], 'number'],
            // [
            //     ['university_state'], 'required', 'when' => function ($model) {
            //         return ( $model->application_type != '80' || $model->application_type != 81 );
            //     }, 'whenClient' => "function (attribute, value) {
            //     return ( $('#qualificationform-application_type').val() != '81 || $('#qualificationform-application_type').val() != '80   )';
            //     }"
            // ]
        ];
    }

    public function saveData()
    {
        $id = $this->id;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!empty($id)) {
                $model = ApplicantQualification::findById($id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The model you are trying to access doesn't exist.");
                }
            } else {
                $model = new ApplicantQualification();
            }

            $model->attributes = $this->attributes;
            $model->date_of_marksheet = date_format(date_create($this->date_of_marksheet), 'Y-m-d');
           

            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                return FALSE;
            }
            // $model->save();

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public static function loadData($models,$data, $formName)
    {
        $returningModel = [];
        foreach ($data[$formName] as $key => $dataArray) {
            if(!empty($models[$key]['id']) ){
                $qualification = $models[$key];
            }else{
                $qualification = new QualificationForm();
            }
            // print_r($dataArray);
            $qualification->setAttributes($dataArray);
            if(!empty($dataArray['university_state'])){
                $stateModel = \common\models\MstState::findByCode($dataArray['university_state'], ["resultFormat" => ModelCache::RETURN_TYPE_OBJECT]);
                if ($stateModel === NULL) {
                    throw new \components\exceptions\AppException("Oops! The model you are trying to access doesn't exist.");
                } else {
                    $qualification->university_state = $dataArray['university_state'];
                    $qualification->university_country = $stateModel->country_code;
                }
            }
            // $qualification->load($dataArray);
            $returningModel [] = $qualification;  
        }
        return $returningModel;
    }

    public function getData($id, $newModel = false)
    {
        $returningModel = [];
        $this->applicant_id = $id;

        $applicantModel = Applicant::findById($id, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if (!empty($applicantModel)) {
            $applicantQualification = ApplicantQualification::findByApplicantId($id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT, 'returnAll' => true]);
            // var_dump([$applicantQualification]);
            // print_r(count([$applicantQualification][0]));
            // die();
            if ($newModel) {
                return $this;
            }
            if ($applicantQualification == null) {
                return [$this];
            } else {
                foreach ($applicantQualification as $qualification) {
                    $model = new QualificationForm();
                    $model->university_state = $qualification->university_state;
                    $model->id = $qualification->id;
                    $model->attributes = $qualification->attributes;
                    $model->date_of_marksheet = date_format(date_create($qualification->date_of_marksheet), 'd-m-Y');

                    $returningModel[] = $model;
                }
            }
            return $returningModel;
        }
    }
}
