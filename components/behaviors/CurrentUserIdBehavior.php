<?php
namespace components\behaviors;

use yii\db\ActiveRecord;

/**
 * Description of CurrentUserIdBehavior
 *
 * @author Pawan Kumar
 */
class CurrentUserIdBehavior extends \yii\base\Behavior
{
    public $attributes = [];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave'                
        ];
    }
    
    //Assign Current User ID to the fields if not set
    public function beforeSave($event)
    {
        foreach($this->attributes as $field) {
            $userId = (int)($this->owner->{$field});
            if((empty($userId) || $userId <= 0) && !\Yii::$app->user->isGuest) {
                $this->owner->{$field} = \Yii::$app->user->id;
            }
        }
        
        return true;
    }
}
