<?php

/*
 * Example Use - 
 * [
 *  'class' => \components\behaviors\PurifyStringBehavior::className(),
 *  'attributes' => ['name', 'title']
 *  ]
 */

namespace components\behaviors;

/**
 * Description of PurifyStringBehavior
 *
 * @author Pawan Kumar
 */
class PurifyStringBehavior extends \yii\base\Behavior
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
            \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }
    
    public function beforeValidate($event)
    {
        $hasError = FALSE;

        foreach($this->attributes as $field) {
            
            $whiteListing = FALSE;

            $str = trim($this->owner->{$field});
            if(empty($str)) {
                continue;
            }
            
            //replace whitelisted terms with an uncommon but valid string
            if (property_exists($this->owner, "stringPurifyWhitelist") && array_key_exists($field, $this->owner->stringPurifyWhitelist)) {
                $whiteListing = TRUE;
                foreach($this->owner->stringPurifyWhitelist[$field] as $i => $good_string) {
                    $str = str_ireplace($good_string, '¡.¡'.$i.'!.!', $str);
                }
            }

            //Note: htmlpurifer commented because it was changing '&' to '&amp;' in titles
            //$purifiedStr = \yii\helpers\HtmlPurifier::process($str); //Removes invalid tags, symbols & code
            
            // This flag taken out to allow Maori macron characters FILTER_FLAG_STRIP_HIGH |

            $purifiedStr = filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_NO_ENCODE_QUOTES);    //Removes all tags & other remaining invalid chars 
            
            if(empty($purifiedStr)) {
                $this->owner->addError($field, "{$field} contains invalid characters.");
                $event->isValid = false;
                $hasError = TRUE;
            }
            
            //Add whitelisted terms back into string
            if ($whiteListing) {
                foreach($this->owner->stringPurifyWhitelist[$field] as $i => $good_string) {
                    $purifiedStr = str_replace('¡.¡'.$i.'!.!', $good_string, $purifiedStr);
                }
            }


            $this->owner->{$field} = $purifiedStr;
        }
        
        if($hasError) {
            return FALSE;
        }
        
        return TRUE;
    }  

}
