<?php


namespace backend\controllers;

use Yii;
use common\models\LogOtp;
use common\models\search\LogApplicantSearch;
use common\models\caching\ModelCache;

/**
 * Description of LogApplicantController
 *
 * @author HP
 */
class LogApplicantController extends AdminController
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
        $searchModel = new LogApplicantSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
}
