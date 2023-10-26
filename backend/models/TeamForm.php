<?php
namespace backend\models;

use Yii;
use common\models\Team;
use common\models\caching\ModelCache;

/**
 * Description of TeamForm
 *
 * @author Pawan Kumar
 */
class TeamForm extends \yii\base\Model
{

    public $id;
    public $guid;
    public $name;
    public $is_active;
    public $users;
    public $taxonomy;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id', 'is_active'], 'integer'],
            [['guid'], 'string', 'max' => 39],
            [['name'], 'string', 'max' => 255],
            [['users', 'taxonomy'], 'safe']
        ];
    }

    public function save()
    {
        $teamId = (int) $this->id;
        $teamGuid = $this->guid;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($teamId > 0) {
                $model = Team::findByGuid($teamGuid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! You trying to access group model doesn't exist.");
                }
            }
            else {
                $model = new Team();
                $model->loadDefaultValues(TRUE);
            }
            $model->attributes = $this->attributes;
            if (!$model->save()) {
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

}
