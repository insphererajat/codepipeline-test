<?php

/*
 Usage:-
 [
    'class' => \components\behaviors\PurifyValidateBehavior::className(),
    'attributes' => [
        ActiveRecord::EVENT_BEFORE_VALIDATE => [
            'name' => 'purifyString',
            'keywords' => 'purifyString'
        ],
        ActiveRecord::EVENT_BEFORE_INSERT => [
            'username' => 'username',
        ]
    ]
],
 * NOTE:-
 * EVENT_BEFORE_VALIDATE will return error
 * EVENT_BEFORE_SAVE will only purify before saving 
 * In single instance single function can be executed per event (multiple attributes)
 * MORE use cases at last
 */

namespace components\behaviors;

/**
 * Description of PurifyValidateBehavior
 *
 * @author Pawan Kumar
 */
class PurifyValidateBehavior extends \yii\base\Behavior
{
    public $attributes = [];
    
     /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [];
        foreach ($this->attributes as $event => $attributesArr) {
            foreach($attributesArr as $attribute => $applyFunction) {
                $events[$event] = $applyFunction;
            }
        }
        
        return $events;
    }    
    
    public function cleanEncodeUTF8($event)
    {
        $attributes = isset($this->attributes[$event->name]) ? (array)$this->attributes[$event->name] : [];
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $applyFunction) {
                
                $str = trim($this->owner->{$attribute});
                if(empty($str)) {
                    continue;
                }

                $this->owner->{$attribute} = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
            }
        }
        
        return TRUE;   
    }
    
    public function purifyHtml($event)
    {
        $hasError = FALSE;
        $attributes = isset($this->attributes[$event->name]) ? (array)$this->attributes[$event->name] : [];
        if (!empty($attributes)) {
            
            foreach ($attributes as $attribute => $applyFunction) {
                
                $str = trim($this->owner->{$attribute});
                if(empty($str)) {
                    continue;
                }
                //allow iframe for youtube & vimeo videos
                $config = \HTMLPurifier_Config::createDefault();
                $config->set('AutoFormat.RemoveEmpty', true);
                $config->set('AutoFormat.RemoveEmpty.RemoveNbsp' , true);
                $config->set('AutoFormat.Linkify' , true);
                $config->set('HTML.Nofollow', true);
                $config->set('HTML.SafeIframe', true);
                $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); //allow YouTube and Vimeo
                //allow attributes for iframe tag
                $def = $config->getHTMLDefinition(true);
                $def->addAttribute('iframe', 'allowfullscreen', "Text");
                $def->addAttribute('iframe', 'webkitallowfullscreen', "Text");
                $def->addAttribute('iframe', 'mozallowfullscreen', "Text");
                $def->addAttribute('iframe', 'style', "Text");
                $def->addAttribute('iframe', 'allowtransparency', "Text");
                $def->addAttribute('iframe', 'data-tweet-id', "Text");
                
                $purifier = new \HTMLPurifier($config);

                $purifiedStr = $purifier->purify($str);
                
                if($event->name == \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE 
                        && (empty($purifiedStr) || $str != $purifiedStr)) {
                    $this->owner->addError($attribute, "{$attribute} contains invalid code.");
                    $event->isValid = false;
                    $hasError = TRUE;
                }
                else {
                    $this->owner->{$attribute} = $purifiedStr;
                }
            }
        }
        
        if($hasError) {
            return FALSE;
        }
        
        return TRUE;        
    }
    
    public function purifyString($event)
    {
        $hasError = FALSE;
        $attributes = isset($this->attributes[$event->name]) ? (array)$this->attributes[$event->name] : [];
        if (!empty($attributes)) {
            
            foreach ($attributes as $attribute => $applyFunction) {
                
                $str = trim($this->owner->{$attribute});
                if(empty($str)) {
                    continue;
                }

                $purifiedStr = \yii\helpers\HtmlPurifier::process($str); //Removes invalid tags, symbols & code
                $purifiedStr = filter_var($purifiedStr, FILTER_SANITIZE_STRING);    //Removes all tags & other remaining invalid chars 
                if($event->name == \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE 
                        && (empty($purifiedStr) || $str != $purifiedStr)) {
                    $this->owner->addError($attribute, "{$attribute} contains invalid characters.");
                    $event->isValid = false;
                    $hasError = TRUE;
                }
                else {
                    $this->owner->{$attribute} = $purifiedStr;
                }
            }
        }
        
        if($hasError) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function username($event)
    {
        $hasError = FALSE;
        $attributes = isset($this->attributes[$event->name]) ? (array)$this->attributes[$event->name] : [];
        if (!empty($attributes)) {
            
            foreach ($attributes as $attribute => $applyFunction) {
                
                $str = trim($this->owner->{$attribute});
                if(empty($str)) {
                    continue;
                }

                $username = filter_var($str, FILTER_SANITIZE_STRING);       //remove tags
                $username = preg_replace('/[^a-zA-Z0-9_-]/', '', $username); //only alpha-numeric dash underscore allowed
                if($event->name == \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE 
                        && (empty($username) || $str != $username)) {
                    $this->owner->addError($attribute, "{$attribute} contains invalid characters.");
                    $event->isValid = false;
                    $hasError = TRUE;
                }
                else {
                    $this->owner->{$attribute} = $username;
                }
            }
        }
        
        if($hasError) {
            return FALSE;
        }
        
        return TRUE;
    }    
    
    
}

/****
 *MORE USE CASES - Want to run multiple function on same attribute on same event
*             'purifyOnly' => 
                [
                    'class' => \components\behaviors\PurifyValidateBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_VALIDATE => [
                            'name' => 'purifyString',
                            'keywords' => 'purifyString'
                        ]
                    ]
                ],
            'validateOnly' => 
                [
                    'class' => \components\behaviors\PurifyValidateBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_VALIDATE => [
                            'name' => 'validateString'
                        ]
                    ]
                ],
 */