<?php

namespace backend\models;

use Yii;
use common\models\User;
use common\models\caching\ModelCache;
use common\models\UserRole;
use yii\helpers\ArrayHelper;

/**
 * Description of UserForm
 *
 * @author Pawan
 */
class UserForm extends \yii\base\Model
{

    public $id;
    public $guid;
    public $username;
    public $email;
    public $password;
    public $verifypassword;
    public $firstname;
    public $lastname;
    public $status;
    public $profile_media_id;
    public $role_id;

    public $updateProfile = false;


    const SCENARIO_USER_UPDATE = 'update';
    
    public function behaviors()
    {
        return [
            \components\behaviors\PasswordLogBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'profile_media_id'], 'integer'],
            [['guid'], 'string', 'max' => 39],
            [['firstname', 'lastname', 'username', 'email', 'status', 'role_id'], 'required'],
            [['password', 'verifypassword'], 'required', 'except' => self::SCENARIO_USER_UPDATE],
            [['firstname', 'lastname'], 'string', 'max' => 255],
            ['email', 'email'],
            ['password', 'string', 'min' => 8],
            ['password', 'match', 'pattern' =>'/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/', 'message' => 'Password must contain at least one lower and upper case and special character and a digit.'],
            [['verifypassword'], 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => 'Verify Password should exactly match Password'],
        ];
    }


    public function save()
    {
        $userId = (int) $this->id;
        $userGuid = $this->guid;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($userId > 0) {
                $model = User::findByGuid($userGuid, ['id' => $userId, 'resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! You trying to access user model doesn't exist.");
                }
            }
            else {
                $model = new User;
                $model->loadDefaultValues(TRUE);
            }

            if (!$model->saveUser($this->attributes)) {
                $this->addErrors($model->getErrors());
                return FALSE;
            }

            $transaction->commit();
            return TRUE;
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

    public function loadUserData(User $model)
    {
        $this->attributes = $model->attributes;

    } 

}
