<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\models;

/**
 * Description of ClassifiedForm
 *
 * @author Pawan Kumar
 */
class ClassifiedForm extends \yii\base\Model
{
    public $id;
    public $guid;
    public $media_id;
    public $title;
    public $parent_id;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $external_link;
    public $display_order;
    public $is_active;
    public $content;
    public $created_by;
    public $modified_by;
    public $page_media_id;

    const SCENARIO_USER_UPDATE = 'update';

    public function rules()
    {
        return [
            [['id', 'media_id', 'page_media_id'], 'integer'],
            [['content', 'meta_description'], 'string'],
            [['media_id', 'parent_id', 'display_order', 'is_active'], 'integer'],
            [['guid'], 'string', 'max' => 40],
            [['title', 'external_link', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modified_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['parent_id' => 'id']]
        ];
    }
    
    public function save()
    {
        
    }

}
