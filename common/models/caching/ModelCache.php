<?php

namespace common\models\caching;
use common\components\caching\base\Cache as BaseCache;
use Yii;

/**
 * Description of ModelCache
 *
 * @author Azam
 */
class ModelCache extends BaseCache
{
    const RETURN_TYPE_OBJECT = 'object';
    const RETURN_ALL = 'all';
    const RETURN_ONE = 'one';
    
    const IS_DELETED_NO = 0;
    const IS_DELETED_YES = 1;
    
    const IS_ACTIVE_NO = 0;
    const IS_ACTIVE_YES = 1;
    
    const ENABLE = 1;
    const DISABLED = 2;

    
    public function __construct( $className, $params, $enableCaching = false)
    {
        //generating a new key for the cache
        $this->key = $className . '-' . md5(base64_encode(serialize($params)));
        
        //only enable caching for frontend sites
        if (Yii::$app->id == 'app-frontend' ) {
        // if (Yii::$app->id == 'app-frontend' && !Yii::$app->params['isProductAdmin']) {
            $this->enableCaching = Yii::$app->request->isGet;
            
            //force enable cache
            $this->enableCaching = $enableCaching === true ? $enableCaching : $this->enableCaching;
        }
        
        if (isset($params['forceCache']) && $params['forceCache']) {
            $this->enableCaching = true;
        } 
        
        if ($this->enableCaching && !empty($params['cacheTime']) && $params['cacheTime'] > 0) {
            $this->cachePeriod = (int)$params['cacheTime'];
        }
        
        parent::__construct();
    }
    
    public function getOrSet($modelAQ, $returnAllOrOne = 'all')
    {
        if ($this->enableCaching) {
            if(!$this->exists()) {
                $data = $modelAQ->{$returnAllOrOne}();
                $this->set(base64_encode(serialize($data)));
            }
            else {
                $data = unserialize(base64_decode($this->get()));
            }
        }
        else {
            $data = $modelAQ->{$returnAllOrOne}();
        }
        
        return $data;
    }
    
    public function getOrSetByParams($modelAQ, $params = [])
    {
        if(isset($params['offset']) && $params['offset'] >= 0) {
            $modelAQ->offset($params['offset']);
        }
        
        if(isset($params['limit']) && $params['limit'] > 0) {
            $modelAQ->limit($params['limit']);
        }
        
        // sort order
        if(!empty($params['orderBy'])) {
            if(is_array($params['orderBy'])) {
                $modelAQ->orderBy($params['orderBy']);
            }
            else {
                if(isset($params['orderDirection'])) {
                    $modelAQ->orderBy([$params['orderBy'] => $params['orderDirection']]);
                }
                else {
                    $modelAQ->orderBy([$params['orderBy'] => SORT_ASC]);
                }
            }
        }
        
        // having clause
        if(isset($params['having'])) {
            $modelAQ->having($params['having']);
        }
        
        if(isset($params['groupBy'])) {
            $modelAQ->groupBy($params['groupBy']);
        }
        
        // result format : array or object
        if(!isset($params['resultFormat']) || $params['resultFormat'] != self::RETURN_TYPE_OBJECT) {
            $modelAQ->asArray();
        }
        

        if (isset($params['returnAll']) && $params['returnAll']) {
            $returnAllOrOne = self::RETURN_ALL;
        }
        else {
            $returnAllOrOne = (isset($params['resultCount']) && $params['resultCount'] == self::RETURN_ALL) ? self::RETURN_ALL : self::RETURN_ONE;
        }

        if((isset($params['checkExists']) && $params['checkExists'])
                    || (isset($params['existOnly']) && $params['existOnly'])
                    || (isset($params['exists']) && $params['exists']) 
                    || (isset($params['existsOnly']) && $params['existsOnly'])
          ) {
            $returnAllOrOne = 'exists';
        }
        
        if((isset($params['countOnly']) && $params['countOnly'])
           || (isset($params['count']) && $params['count'])
          ) {
            $returnAllOrOne = 'count';
        }

        if(isset($params['forceCache']) && $params['forceCache'] === FALSE) {
            $this->enableCaching = false;
        }
        //echo $modelAQ->createCommand()->rawSql; die;
        return $this->getOrSet($modelAQ, $returnAllOrOne);
    }
    
    //Using IN-MEMORY CACHING
    public function set($data)
    {
        Yii::$app->params['caching'][$this->key] = $data;
    }
    
    public function exists()
    {
        return (!empty(Yii::$app->params['caching'][$this->key])) ? TRUE : FALSE;
    }
    
    public function get()
    {
        if($this->exists()) {
            return Yii::$app->params['caching'][$this->key];
        }
        
        return FALSE;
    }
    
    public static function selectTypeList($key = null)
    {
        $list = [
            self::ENABLE => 'Yes',
            self::DISABLED => 'No',
        ];

        return (isset($key)) ? (isset($list[$key]) ? $list[$key] : $key) : $list;
    }
    
    public static function testEmail($email)
    {
        if (\yii\helpers\ArrayHelper::isIn($email, ['upendra.rajpoot0786@gmail.com', 'upendrarajpoot31@gmail.com'])) {
            return true;
        }
        return false;
    }
}
