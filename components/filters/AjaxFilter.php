<?php

namespace components\filters;

use Yii;
use yii\base\ActionFilter;

/**
 * Description of AjaxFilter
 *
 * @author Pawan Kumar
 */
class AjaxFilter extends ActionFilter
{
    /**
     * @param Action $action
     * @return boolean
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {
            return parent::beforeAction($action);
        }
        
        throw new \components\exceptions\AppException('Oops! Your request is not valid.', 404);
    }

}
