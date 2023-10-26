<?php

namespace frontend\controllers\payment;

use Yii;
use components\exceptions\AppException;
use yii\helpers\ArrayHelper;
use common\models\Transaction;

/**
 * Description of CscController
 *
 * @author Amit Handa
 */
class CscController extends BasePaymentController
{
    protected $integration = 'csc';    
    public function beforeAction($action)
    {
        $this->paymentMethod = Transaction::TYPE_CSC;
        $this->postParams = Yii::$app->request->post();

        if(!empty($this->postParams) && !empty($this->postParams['appModule'])){
            $this->appModule = $this->postParams['appModule'];
        }
        
        return parent::beforeAction($action);
    }
    
    public function actionRequest($csc = NULL)
    {
        if (!\components\Helper::checkCscConnect()) {
            Yii::$app->session->setFlash('error', 'Oops! Please connect with csc.');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->postParams = ArrayHelper::merge($this->postParams, $this->_getConnectData());
        if(isset($this->postParams['user_resp_data']['csc_id'])) {
            $csc = $this->postParams['user_resp_data']['csc_id'];
        }
        return parent::actionRequest($csc);
    }
    
    private function _getConnectData()
    {
        return \yii\helpers\Json::decode(Yii::$app->session->get('_connectData'));
    }


    /**
     * Login to CSC Client
     * @return type
     */
    public function actionConnect()
    {
        $this->postParams['paymentMethod'] = $this->paymentMethod;       
        Yii::$app->session->set('_referrer', Yii::$app->request->referrer);
        return parent::actionConnect();
    }
    
    /**
     * csc connect response
     * @param type $code
     * @param type $state
     * @return type
     * @throws AppException
     */
    public function actionConnectResponse($code, $state)
    {
        return parent::actionConnectResponse($code, $state);
    }
    
    /**
     * Handles csc Success Response
     * @return type
     * @throws \components\exceptions\AppException
     */
    public function actionSuccess($postParams = null)
    {
        $appModule = Yii::$app->session->get($this->applicantId.'appModule');
        $this->__initPaymentClient(Transaction::TYPE_CSC, [
            'appModule' => $appModule
        ]);
        $bridge_message = $this->obj->get_bridge_message();
        $finalParams = explode('|', $bridge_message);
        
        //breack with pipe operators
        $params = array();
        foreach ($finalParams as $param) {
            $param = explode('=', $param);
            if (isset($param[0]) && !empty($param[0])) {
                $params[$param[0]] = (isset($param[1])) ? $param[1] : "";
            }
        }
        
        if (empty($params)) {
            throw new AppException("Oops! We are unable to process this payment due to invalid response from payment gateway.");
        }
        
        return parent::actionSuccess($params);
    }
    
    
    /**
     * Handles csc Failed Response
     * @return type
     * @throws \components\exceptions\AppException
     */
    public function actionFailed($postParams = null)
    {
        $postParams = Yii::$app->request->post();
        
        if (empty($postParams)) {
            throw new AppException("Oops! We are unable to process this payment due to get invalid response from payment gateway.");
        }
        return parent::actionFailed($postParams);
    }
}