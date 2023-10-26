<?php

namespace frontend\modules\admin\controllers;

use Yii;
use common\models\Role;
use common\models\search\RoleSearch;
use yii\helpers\Url;

/**
 * Description of RoleController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class RoleController extends AdminController
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
        $this->redirect(Url::toRoute(['/admin/home/index']));

        $searchModel = new RoleSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
