<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\Page;
use common\models\Permission;
use backend\models\PageForm;
use common\models\search\PageSearch;
use common\models\caching\ModelCache;

/**
 * Description of PageController
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class PageController extends AdminController
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
        $searchModel = new PageSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new PageForm;
        if (Yii::$app->request->isPost) {
            $model->created_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveData()) {
                $this->setSuccessMessage("Page has been created successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['page/index']));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($guid)
    {
        $pageModel = $this->findModel($guid);
        $model = new PageForm;

        $model->attributes = $pageModel->attributes;

        if (Yii::$app->request->isPost) {
            $model->modified_by = $this->userId;
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveData()) {
                $this->setSuccessMessage("Page has been update successfully.");
                return $this->redirect(\yii\helpers\Url::toRoute(['page/index']));
            }
            else {
                throw new \components\exceptions\AppException(\components\Helper::convertModelErrorsToString($model->errors));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($guid)
    {
        $model = $this->findModel($guid);
        try {
            $model->delete();
            $this->setSuccessMessage("Page has been deleted successfully.");
            return $this->redirect(\yii\helpers\Url::toRoute(['page/index']));
        }
        catch (Exception $ex) {
            throw new \components\exceptions\AppException("Oops! You trying to delete page doesn't exist or deleted.");
        }
    }

    public function actionDeleteMedia()
    {
        $postParams = Yii::$app->request->post();
        if (empty($postParams['mediaId']) || empty($postParams['pageGuid'])) {
            throw new \components\exceptions\AppException("Invalid request.");
        }
        $media = \common\models\Media::findById($postParams['mediaId']);
        if ($media == null) {
            throw new \components\exceptions\AppException("Oops! You trying to delete this page media doesn't exist or deleted.");
        }
        $model = $this->findModel($postParams['pageGuid']);
        try {
            $mediaConnection = \common\models\MediaConnection::findByMediaId($media['id'], [
                        'page_id' => $model->id,
                        'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
            ]);
            if (empty($mediaConnection)) {
                throw new \components\exceptions\AppException("Oops! You trying to delete this page media doesn't exist or deleted.");
            }
            $mediaConnection->delete();
        }
        catch (Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
        return \components\Helper::outputJsonResponse(['success' => 1]);
    }

    protected function findModel($guid)
    {
        $model = Page::findByGuid($guid, ['resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
        if ($model == null) {
            throw new \components\exceptions\AppException("Oops! You trying to access page doesn't exist or deleted.");
        }
        return $model;
    }

}
