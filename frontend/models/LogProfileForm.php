<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\LogProfile;
use common\models\LogProfileMedia;
use common\models\LogProfileActivity;

class LogProfileForm extends Model
{
    // address check
    public $same_as_present_address;
    // for current address
    public $id;
    public $guid;
    public $applicant_id;
    public $log_profile_id;
    public $name;
    public $father_name;
    public $date_of_birth;
    public $media_id;
    public $reCaptcha;


    public function rules()
    {
        return [
            [['name', 'father_name', 'date_of_birth', 'media_id'], 'required'],
            [['media_id'], 'integer'],
            [['name', 'father_name'], 'string', 'max' => 255],
            [['date_of_birth'], 'safe'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => \Yii::$app->params['reCAPTCHA.secretKey'], 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'father_name' => 'Father Name',
            'date_of_birth' => 'Date of Birth',
            'media_id' => 'Document'
        ];
    }

    public function saveData()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->applicant_id = Yii::$app->applicant->id;
            if (!empty($this->id)) {
               $model = LogProfile::findById($this->id, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! The model you are trying to access doesn't exist.");
                }
            } else {
                $model = new LogProfile();
                $model->loadDefaultValues(TRUE);
            }

            $newData = [
                'name' => $this->name,
                'father_name' => $this->father_name,
                'date_of_birth' => date('Y-m-d', strtotime($this->date_of_birth)),
            ];
            
            $model->applicant_id = $this->applicant_id;
            $model->status = LogProfile::STATUS_PENDING;
            $model->new_value = \yii\helpers\Json::encode($newData);
            if (!$model->save()) {
                throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($model->getErrors()));
            }

            $logProfileId = $model->id;
            
            if (!empty($logProfileId)) {
                $logProfileMedia = LogProfileMedia::findByLogProfileId($logProfileId, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
                if ($logProfileMedia === NULL) {
                    $logProfileMedia = new LogProfileMedia();
                    $logProfileMedia->loadDefaultValues(TRUE);
                }
            }

            $logProfileMedia->applicant_id = $this->applicant_id;
            $logProfileMedia->log_profile_id = $logProfileId;
            $logProfileMedia->media_id = $this->media_id;
            $logProfileMedia->created_by = $this->applicant_id;
            if (!$logProfileMedia->save()) {
                throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($logProfileMedia->getErrors()));
            }

            $logProfileActivity = new LogProfileActivity();
            $logProfileActivity->applicant_id = $this->applicant_id;
            $logProfileActivity->log_profile_id = $logProfileId;
            $logProfileActivity->status = LogProfile::STATUS_PENDING;
            $logProfileActivity->created_by = $this->applicant_id;
            if (!$logProfileActivity->save()) {
                throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($logProfileActivity->getErrors()));
            }

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public function getData()
    {
        
        $logProfileModel = LogProfile::findByApplicantId($this->applicant_id, [
                    'selectCols' => ['log_profile.id', 'log_profile.guid', 'log_profile.new_value', 'log_profile.status', 'log_profile_media.media_id'],
                    'joinWithLogProfileMedia' => 'leftJoin',
        ]);

        if ($logProfileModel == null) {
            return false;
        }
        
        if ($logProfileModel['status'] == LogProfile::STATUS_PENDING) {
            throw new \components\exceptions\AppException("You already filled OTR. Request in under progress.");
        }
        
        /*$this->id = $logProfileModel['id'];
        $this->guid = $logProfileModel['guid'];
        $newValue = \yii\helpers\Json::decode($logProfileModel['new_value']);
        $this->name = isset($newValue['name']) ? $newValue['name'] : '';
        $this->father_name = isset($newValue['father_name']) ? $newValue['father_name'] : '';
        $this->date_of_birth = isset($newValue['date_of_birth']) ? date('d-m-Y', strtotime($newValue['date_of_birth'])) : '';
        $this->media_id = $logProfileModel['media_id'];*/
        
        return $this;
    }
}
