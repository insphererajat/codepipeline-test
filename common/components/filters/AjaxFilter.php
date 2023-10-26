<?php

namespace common\components\filters;

use Yii;
use yii\base\ActionFilter;
use yii\web\BadRequestHttpException;

/**
 * Description of AjaxFilter
 *
 * @author Amit Handa
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
        
        throw new BadRequestHttpException();
    }

}
