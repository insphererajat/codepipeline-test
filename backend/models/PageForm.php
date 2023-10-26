<?php

namespace backend\models;

use Yii;
use common\models\Page;
use common\models\caching\ModelCache;
use common\models\User;
use common\models\Media;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageForm
 *
 * @author Pawan Kumar
 */
class PageForm extends \yii\base\Model
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

    public function saveData()
    {
        $id = $this->id;
        $guid = $this->guid;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($id > 0) {
                $model = Page::findByGuid($guid, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
                if ($model === NULL) {
                    throw new \components\exceptions\AppException("Oops! You trying to access page model doesn't exist.");
                }
            } else {
                $model = new Page;
                $model->loadDefaultValues(TRUE);
            }
            $model->attributes = $this->attributes;
            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                return FALSE;
            }
            if (!empty($this->page_media_id)) {
                $model->media_id = $this->page_media_id;
            }

            if (!empty($this->page_media_id)) {
                (new \common\models\MediaConnection)->saveConnection([
                    'media_id' => $this->page_media_id,
                    'page_id' => $model->id
                     
                ]);
            }
            $model->save();

            $transaction->commit();
            return TRUE;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }

}
