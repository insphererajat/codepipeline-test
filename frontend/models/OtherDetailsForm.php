<?php

namespace frontend\models;

use common\models\Applicant;
use common\models\ApplicantClassified;
use common\models\ApplicantClassifiedExamCentre;
use common\models\caching\ModelCache;
use common\models\ListType;
use common\models\MstListType;
use Yii;
use yii\base\Model;

class OtherDetailsForm extends Model
{
    public $id;

    public $exam_centre_id;
    public $exam_centre_id_preference_two;

    public $want_height_relaxation;
    public $is_employed;
    public $type_of_organization;
    public $noc_applying_date;
    public $is_criminal_case;
    public $is_debarred;
    public $candidate_id_proof;
    public $candidate_id_proof_number;



    public function rules()
    {
        return [
            [
                [
                    'exam_centre_id',
                    'exam_centre_id_preference_two',
                    'want_height_relaxation',
                    'is_employed',
                    'type_of_organization',
                    'noc_applying_date',
                    'is_criminal_case',
                    'is_debarred',
                    'candidate_id_proof',
                    'candidate_id_proof_number',
                ], 'required',
                // [
                //     ['type_of_organization'], 'required', 'when' => function ($model) {
                //         return ( $model->is_employed );
                //     }, 'whenClient' => "function (attribute, value) {
                // return ( $('#otherdetailsform-is_employed').val())';
                // }"
                // ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'exam_centre_id' => 'Exam Center required',
            'exam_center_id_preference_two' => 'Exam Center required',
        ];
    }

    public function saveData()
    {
        $id = $this->id;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!empty($id)) {
                $model = Applicant::findById($id, ['resultFormat' => 'object']);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The block model you are trying to access doesn't exist.");
                }
            }
            $model->attributes = $this->attributes;
            $model->id = $this->id;
            $model->want_height_relaxation = $this->want_height_relaxation;
            $model->is_employed = $this->is_employed;
            $model->type_of_organization = $this->type_of_organization;
            $model->noc_applying_date = date_format(date_create($this->noc_applying_date), 'Y-m-d');
            $model->is_criminal_case = $this->is_criminal_case;
            $model->is_debarred = $this->is_debarred;
            $model->candidate_id_proof = $this->candidate_id_proof;
            $model->candidate_id_proof_number = $this->candidate_id_proof_number;
            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                // return FALSE;
            }

            $classified = ApplicantClassified::findByApplicantIdAndClassifiedId($model->id, $model->classified_id, ['resultFormat' => 'object']);
            $modelClassified = ApplicantClassifiedExamCentre::findByApplicantClassifiedId($classified->id, ['resultFormat' => 'object']);
            if ($modelClassified == null) {
                $modelClassified =  new ApplicantClassifiedExamCentre();
                // throw new \components\exceptions\AppException("Oops! There was a problem in Applicant Classified Exam Centre data.");
            }
            $modelClassified->exam_centre_id = $this->exam_centre_id;
            $modelClassified->preference = $this->exam_centre_id_preference_two;
            $modelClassified->applicant_classified_id = $classified->id;

            // return $model->errors;

            if (!$modelClassified->save()) {
                $this->addErrors($modelClassified->getErrors());
                return $this->errors;
                return FALSE;
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
        $model = Applicant::findById($id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);

        $classified_id = $model->classified_id;
        // throw new \components\exceptions\AppException(print_r($model));
        if (!empty($model)) {
            $this->attributes = $model->attributes;
            $this->id = $model->id;
            $this->want_height_relaxation = $model->want_height_relaxation;
            $this->is_employed = $model->is_employed;
            $this->type_of_organization = $model->type_of_organization;
            $this->noc_applying_date = date_format(date_create($model->noc_applying_date), 'd-m-Y');
            $this->is_criminal_case = $model->is_criminal_case;
            $this->is_debarred = $model->is_debarred;
            $this->candidate_id_proof = $model->candidate_id_proof;
            $this->candidate_id_proof_number = $model->candidate_id_proof_number;
        }

        // return [$model->id,$classified_id];
        $classified = ApplicantClassified::findByApplicantIdAndClassifiedId($id, $classified_id, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        // $classified = ApplicantClassified::find()->select('applicant_classified.*')->where('applicant_id =:id', [':id' => $model->id])
        // ->andWhere('classified_id =:id', [':id' => $classified_id])->all();

        // return $classified; 
        // throw new \components\exceptions\AppException(json_encode($classified));

        if ($classified == null) {
            // $classified = new ApplicantClassified();
            throw new \components\exceptions\AppException("Oops! There was a problem in Applicant Classified data." . json_encode($model));
        } else {

            $modelClassified = ApplicantClassifiedExamCentre::findByApplicantClassifiedId($classified->id, [
                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT,
            ]);


            // throw new \components\exceptions\AppException(print_r($modelClassified));
            if (empty($modelClassified)) {
                $modelClassified = new ApplicantClassifiedExamCentre();
                // throw new \components\exceptions\AppException("Oops! There was a problem in ApplicantClassifiedExamCentre.");
            }

            // return $modelClassified;
            $this->exam_centre_id = $modelClassified->exam_centre_id;
            $this->exam_centre_id_preference_two = $modelClassified->preference;
        }

        return $this;
    }
}
