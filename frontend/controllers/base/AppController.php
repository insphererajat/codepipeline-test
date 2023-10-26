<?php

namespace frontend\controllers\base;

use Yii;
use common\models\Applicant;
/**
 * Description of AppController
 *
 * @author Amit Handa
 */
class AppController extends \yii\web\Controller
{
    public $queryParams = [];
    public $_connectData = [];
    
    public function beforeAction($action)
    {

        $queryParams = \Yii::$app->request->queryParams;
        foreach ($queryParams as $key => $value) {
            $this->queryParams[$key] = $value;
        }
        if (\components\Helper::checkCscConnect()) {
            $this->_connectData = \Yii::$app->session->get('_connectData');
        }
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        \Yii::$app->session->set('_connectData', $this->_connectData);
        $result = parent::afterAction($action, $result);
        // your custom code here
        return $result;
    }
   
    public function setSuccessMessage($message)
    {
        Yii::$app->session->setFlash('success', $message);
    }

    public function setErrorMessage($message)
    {
        Yii::$app->session->setFlash('error', $message);
    }
    
    protected function setSessionData($key, $value)
    {
        Yii::$app->session->set($key, $value);
    }

    protected function getSessionData($key)
    {
        if ($this->hasSessionData($key)) {
            return Yii::$app->session->get($key);
        }
        return null;
    }
    
    protected function removeSessionData($key)
    {
        Yii::$app->session->remove($key);
    }

    protected function hasSessionData($key)
    {
        return Yii::$app->session->has($key) ? TRUE : FALSE;
    }
    
    protected function setMultipleSessionData($params)
    {
        if (empty($params)) {
            return false;
        }
        foreach ($params as $key => $data) {
            $this->setSessionData($key, $data);
        }
    }


}
