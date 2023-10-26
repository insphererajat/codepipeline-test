<?php

namespace backend\models;

use Yii;
/**
 * Description of ListTypeForm
 *
 * @author Pawan Kumar
 */
class QualificationForm extends \yii\base\Model
{

    public $id;
    public $guid;
    public $parent_id;
    public $name;
    public $is_active;
    public $display_order;
    public $modified_by;
    public $created_by;
    
    public $subjects;

    public function rules()
    {
        return [
            [['name', 'display_order', 'is_active'], 'required'],
            [['id', 'display_order', 'is_active', 'modified_by', 'created_by', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['guid'], 'string', 'max' => 36],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\base\User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\base\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            ['subjects', 'safe']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'is_active' => 'Status'
        ];
    }

    public function save()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($this->id > 0) {
                $model = \common\models\MstQualification::findById($this->id, ['guid' => $this->guid, 'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \yii\web\HttpException("Sorry, Invalid List Type.");
                }
            }
            else {
                $model = new \common\models\MstQualification();
                $model->loadDefaultValues(TRUE);
            }

            $model->setAttributes($this->attributes);
            if (empty($model->created_by)) {
                $model->setAttribute('created_by', Yii::$app->user->id);
            }

            if (!empty($this->name)) {
                $model->setAttribute('name', preg_replace("/\s+/", " ", $this->name));
            }

            if (!$model->save()) {

                $this->addErrors($model->getErrors());
                $transaction->rollBack();
                return FALSE;
            }
            
            //save subjects
            if (isset($this->subjects) && count($this->subjects) > 0) {
                $qualificationSubjectModel = new \common\models\MstQualificationSubject();
                $qualificationSubjectModel->saveSubjects($model->id, $this->subjects);
            }

            $transaction->commit();

            return TRUE;
        }
        catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

}
