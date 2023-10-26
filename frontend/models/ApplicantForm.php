<?php

namespace frontend\models;

use common\models\Applicant;
use common\models\ApplicantClassified;
use common\models\caching\ModelCache;
use common\models\ListType;
use common\models\MstListType;
use Yii;
use yii\base\Model;

class ApplicantForm extends Model
{
    public $id;
    public $guid;
    public $name;
    public $email;
    public $mobile;
    public $classified_id;
    public $father_name;
    public $mother_name;
    public $date_of_birth;
    public $gender;
    public $marital_status;
    public $religion_id;
    public $nationality_id;
    public $mother_tongue_id;
    public $is_domiciled;
    public $auth_key;
    public $password_hash;


    public function rules()
    {
        return [
            [['father_name', 'mother_name', 'date_of_birth', 'gender', 'marital_status', 'religion_id', 'nationality_id','mother_tongue_id','is_domiciled'], 'required'],
            [['mobile'], 'string', 'max' =>10 ,'min' => 10 ,'tooLong' => 'Please enter a valid phone number.','tooShort' => 'Please enter a valid phone number.' ],
            [['mother_tongue_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['mother_tongue_id' => 'id']],
            [['nationality_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstListType::className(), 'targetAttribute' => ['nationality_id' => 'id']],
            [['religion_id'], 'exist', 'skipOnError' => true, 'targetClass' => ListType::className(), 'targetAttribute' => ['religion_id' => 'id']],
            [['auth_key','password_hash','name','mobile'],'safe'],
            [['email'],'required', 'except'=> 'update']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'mobile' => 'Mobile number',
            'email' => 'Email',
            'father_name' => 'Father Name ',
            'mother_name' => 'Mother Name',
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'marital_status' => 'Martial Status',
            'religion_id' => 'Religion',
            'nationality_id' => 'Nationality',
            'mother_tongue_id' => 'Mother Tongue',
            'is_domiciled' => 'Domiciled',
        ];
    }

    public function saveData()
    {
        $guid = $this->guid;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!empty($guid)) {
                $model = Applicant::findByGuid($guid, ['resultFormat' => 'object']);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The block model you are trying to access doesn't exist.");
                }
            } else {
                $model = new Applicant();
                $model->loadDefaultValues(TRUE);
            }
            $model->attributes = $this->attributes;
            $model->date_of_birth = date_format(date_create($this->date_of_birth),'Y-m-d');
            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                return FALSE;
            }

            $model->save();

            $applicantClassified = ApplicantClassified::findByApplicantIdAndClassifiedId($model->id,$model->classified_id,[
                'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);

            if($applicantClassified == null){
                $applicantClassified = new ApplicantClassified();
            }
            $applicantClassified->setAttribute('applicant_id',  $model->id);
            $applicantClassified->setAttribute('classified_id', $model->classified_id);

            if(!$applicantClassified->save()){
                $this->addErrors($model->getErrors());
                throw new \components\exceptions\AppException("Oops! Some error occured #applicantForm-Line:93.=> ".json_encode($applicantClassified->errors));
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
        $model = Applicant::findById($id, ['resultFormat' => 'object']);
        
        if(!empty($model)) {
            $this->attributes = $model->attributes;
            $this->id = $model->id;
            $this->guid = $model->guid;
            $this->classified_id = $model->classified_id;
            $this->date_of_birth = date_format(date_create($model->date_of_birth),'d-m-Y');
        }

        return $this;
    }
}
