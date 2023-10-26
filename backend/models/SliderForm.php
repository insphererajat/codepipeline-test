<?php
namespace backend\models;

/**
 * Description of SliderForm
 *
 * @author Azam
 */
class SliderForm extends \yii\base\Model
{

    public $id;
    public $guid;
    public $title;
    public $type;
    public $status;
    public $media;
    
   
    
    public function rules()
    {
        return [
            [['title', 'media'],'required'],
            [['id', 'status'], 'integer'],
            [['guid','title', 'type'],'string'],
            [['status'],'default','value' => \common\models\Slider::STATUS_ACTIVE]
        ];
    }

    public function save()
    {
        $id = $this->id;
        $guid = $this->guid;
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {
            if (isset($guid) && !empty($guid)) {
                $model = \common\models\Slider::findOne(['id' => $id ,'guid' => $guid]);
            }
            else {
                $model = new \common\models\Slider;
                $model->loadDefaultValues(TRUE);
            }

            $model->attributes = $this->attributes;
            if (!$model->save()) {
                $this->addErrors($model->errors);
                $transaction->rollBack();
                return FALSE;
            }
            
            if(isset($this->media) && count($this->media) > 0) {
                $cmsMediaModel = new \common\models\SliderMedia;
                $cmsMediaModel->saveMultipleMedia($model->id, $this->media);
            }

            $transaction->commit();
            return TRUE;
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
           throw $ex;
        }
        return FALSE;
    }
}
