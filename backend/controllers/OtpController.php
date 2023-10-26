<?php


namespace backend\controllers;

use Yii;
use common\models\LogOtp;
use common\models\search\LogOtpSearch;
use common\models\caching\ModelCache;

/**
 * Description of OtpController
 *
 * @author HP
 */
class OtpController extends AdminController
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->hasAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        $searchModel = new LogOtpSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
}
