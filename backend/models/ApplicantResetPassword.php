<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Applicant;
use components\Helper;
/**
 * Description of StudentResetPassword
 *
 * @author Nitish
 */
class ApplicantResetPassword extends Model
{
    public $applicant_id;
    public $new_password;
    public $confirm_new_password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['new_password', 'confirm_new_password', 'applicant_id'], 'required'],
            ['confirm_new_password', 'compare', 'compareAttribute' => 'new_password'],
            ['new_password', 'match', 'pattern' => Helper::passwordRegex(), 'message' => 'Password must be 8 chanacter long, contain at least one lower and upper case chanacter, one special character and a digit.'],
        ];
    }
    
    public function save()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $appllicantModel = Applicant::findById($this->applicant_id, [
                'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
            ]);
               
            $appllicantModel->setPassword($this->new_password);
            $appllicantModel->save(true, ['password_hash']);
            $transaction->commit();
            
            return true;
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
        return TRUE;
    }
}

