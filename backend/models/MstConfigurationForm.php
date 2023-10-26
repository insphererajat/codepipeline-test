<?php
namespace backend\models;

use common\models\caching\ModelCache;
use Yii;
use yii\base\Model;
    use common\models\MstConfiguration;

class MstConfigurationForm extends Model
{
    public $id;
    public $guid;
    public $type;
    public $config_val1;
    public $config_val2;
    public $config_val3;
    public $config_val4;
    public $config_val5;
    public $config_val6;
    public $config_val7;
    public $config_val8;
    public $config_val9;
    public $config_val10;
    public $config_val11;
    public $config_val12;
    public $config_val13;
    public $config_val14;
    public $config_val15;
    public $is_payment_mode;
    public $configuration_rule;
    public $amount_type;
    public $card;
    public $netbanking;
    public $upi;
    public $wallet;
    public $is_active;

    public function rules() {
        return [
            [['type'], 'required'],
            [['is_payment_mode', 'is_active', 'amount_type', 'id'], 'integer'],
            [['card', 'netbanking', 'upi', 'wallet'], 'number'],
            [['guid'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 50],
            [['config_val1', 'config_val2', 'config_val3', 'config_val4', 'config_val5', 'config_val6', 'config_val7'], 'string', 'max' => 1000],
            [['config_val8', 'config_val9', 'config_val10', 'config_val11', 'config_val12', 'config_val13'], 'string', 'max' => 10000],
            [['config_val11', 'config_val12', 'config_val13', 'config_val14', 'config_val15'], 'string', 'max' => 10000],
            [['amount_type'], 'required', 'when' => function ($model) {
                    return ($model->is_payment_mode == ModelCache::IS_ACTIVE_YES) ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                  return ($('#mstconfigurationform-is_payment_mode').val() == '" . ModelCache::IS_ACTIVE_YES . "') ? true : false;
            }"],
        ];
    }
    
    public function save() 
    {       
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($this->id > 0 && !empty($this->guid)) {
                $model = MstConfiguration::findByGuid($this->guid, ['resultFormat' =>ModelCache::RETURN_TYPE_OBJECT]);
                if ($model == NULL) {
                     throw new \components\exceptions\AppException("Oops! You trying to access network doesn't exist or deleted.");
                }
            } else {
                $model = new MstConfiguration();
                $model->loadDefaultValues(TRUE);
            }

            if($this->is_payment_mode == ModelCache::IS_ACTIVE_YES) {
                $data = [
                    'amount_type' => $this->amount_type,
                    'rule' => [
                        'card' => $this->card,
                        'netbanking' => $this->netbanking,
                        'upi' => $this->upi,
                        'wallet' => $this->wallet,
                    ],
                ];
                $this->configuration_rule = \yii\helpers\Json::encode($data);
            }
            $model->attributes = $this->attributes;
            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();

        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }

        return true;
    }
    
    public function getData($guid)
    {
        $mstPostModel = MstConfiguration::findByGuid($guid);
        if ($mstPostModel['is_payment_mode'] == ModelCache::IS_ACTIVE_YES) {
            $data = \yii\helpers\Json::decode($mstPostModel['configuration_rule']);
            $this->amount_type = $data['amount_type'];
            if (isset($data['rule']) && !empty($data['rule'])) {
                foreach ($data['rule'] as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
        $this->setAttributes($mstPostModel);
        //echo '<pre>';print_r($this);die;
    }

}

?>