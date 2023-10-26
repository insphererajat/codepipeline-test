<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\Page as BasePage;

/**
 * Description of Page
 *
 * @author Amit Handa
 */
class Page extends BasePage
{
    public $page_media_id;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ],
            [
                'class' => \yii\behaviors\SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
            \components\behaviors\GuidBehavior::className(),
            [
                'class' => \components\behaviors\PurifyStringBehavior::className(),
                'attributes' => ['title', 'content', 'slug', 'meta_title', 'meta_keyword', 'meta_description']
            ],
            [
                'class' => \components\behaviors\PurifyValidateBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'title' => 'cleanEncodeUTF8',
                        'content' => 'cleanEncodeUTF8',
                        'slug' => 'cleanEncodeUTF8',
                        'meta_title' => 'cleanEncodeUTF8',
                        'meta_keyword' => 'cleanEncodeUTF8',
                        'meta_description' => 'cleanEncodeUTF8'
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'title' => 'cleanEncodeUTF8',
                        'content' => 'cleanEncodeUTF8',
                        'slug' => 'cleanEncodeUTF8',
                        'meta_title' => 'cleanEncodeUTF8',
                        'meta_keyword' => 'cleanEncodeUTF8',
                        'meta_description' => 'cleanEncodeUTF8'
                    ]
                ]
            ]
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }
        else {
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }

        if (isset($params['guid'])) {
            $modelAQ->andWhere($tableName . '.guid =:guid', [':guid' => $params['guid']]);
        }
        
        if (isset($params['slug'])) {
            $modelAQ->andWhere($tableName . '.slug =:slug', [':slug' => $params['slug']]);
        }
        
        if (isset($params['parentId'])) {
            $modelAQ->andWhere($tableName . '.parent_id =:parentId ', [':parentId' => $params['parentId']]);
        }
        
        if (isset($params['nullParentId'])) {
            $modelAQ->andWhere($tableName . '.parent_id IS NULL');
        }
        
        if(isset($params['notId'])) {
            $modelAQ->andWhere('page.id!=:notId', [':notId' => (int)$params['notId']]);
        }
        
        if(isset($params['topLevelPagesOnly']) && $params['topLevelPagesOnly']) {
            $modelAQ->andWhere('page.parent_id IS NULL');
        }
        
        $modelAQ->orderBy('display_order');
       return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }

    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByGuid($guid, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['guid' => $guid], $params));
    }
    
    public static function findBySlug($slug, $params = []) {

        return self::findByParams(\yii\helpers\ArrayHelper::merge(['slug' => $slug], $params));
    }
    
    public static function findAllPages($params = []) {
        return self::findByParams($params);
    }
    
    public static function findByParentId($parentId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['parentId' => $parentId], $params));
    }

    public static function getPageDropdown($params = [])
    {
        $queryParams = [
            'selectCols' => [
                'page.id', 'page.title'
            ],
            'nullParentId' => TRUE,
            'status' => caching\ModelCache::IS_ACTIVE_YES,
            'resultCount' => 'all',
            'orderBy' => ['page.title' => SORT_ASC],
        ];
        $model = self::findByParams(\yii\helpers\ArrayHelper::merge($queryParams, $params));
        $list = \yii\helpers\ArrayHelper::map($model, 'id', 'title');
        return $list;
    }
    
    public static function buildPagesList($page, $pageId, $title = NULL, $retArr = [], $level = 1, $maxLevels = 100)
    {
        $level++;
        $pageTitle = (!empty($title) ? $title." | " : '') . $page['title'];
        $retArr[$page['id']] = $pageTitle;
        $pages = self::findByParentId($page['id'], [
            'resultCount' => 'all',
            'notId' => $pageId,
            'orderBy' => 'page.title'
        ]);
        
        if($level >= $maxLevels) {
            return $retArr;
        }
        
        if(count($pages) > 0) {
            foreach($pages as $pageModel) {                
                $retArr = self::buildPagesList($pageModel, $pageId, $pageTitle, $retArr, $level, $maxLevels);                
            }
        }
        return $retArr;
    }
    
    public static function getPagesListArr($pageId = NULL, $maxLevels = 100)
    {
        $rootPages = self::findByParams([
            'notId' => $pageId,
            'topLevelPagesOnly' => true,
            'orderBy' => 'page.title',
            'resultCount' => caching\ModelCache::RETURN_ALL
        ]);
        
        $level = 1;
        $pagesArr = [];
        foreach($rootPages as $page) {
            $pagesArr = $pagesArr + self::buildPagesList($page, $pageId, NULL, [], $level, $maxLevels);
        }
        
        return $pagesArr;
    }

}
