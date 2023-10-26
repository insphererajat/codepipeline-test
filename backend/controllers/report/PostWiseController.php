<?php

namespace backend\controllers\report;

use Yii;
use common\models\search\report\PostWiseForm;

/**
 * Description of PostWiseController
 *
 * @author Nitish
 */
class PostWiseController extends \backend\controllers\AdminController
{
    public function actionIndex()
    {
        $searchModel = new PostWiseForm;
        $records = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $searchModel,
            'records' => $records
        ]);
    }
}
