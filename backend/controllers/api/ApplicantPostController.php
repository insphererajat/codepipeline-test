<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers\api;

use Yii;
use common\models\ApplicantPost;
use common\models\caching\ModelCache;
use components\exceptions\AppException;
use common\models\LogOtp;

/**
 * Description of ApplicantPost
 *
 * @author Amit Handa
 */
class ApplicantPostController extends ApiController
{
    public function behaviors()
    {
        return [
            'ajax' => [
                'class' => \common\components\filters\AjaxFilter::className()
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // check session hijacking prevention
                            if (!\common\models\User::checkSessionHijackingPreventions(\common\models\User::BACKEND_LOGIN_KEY, \common\models\User::BACKEND_FIXATION_COOKIE, \common\models\User::BACKEND_SESSION_VALUE)) {
                                Yii::$app->user->logout();
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }
    
    public function actionCancelPost()
    {
        $post = Yii::$app->request->post();
        if (!isset($post['id']) || !isset($post['guid'])) {
            throw new AppException(Yii::t('app', 'invalid.request'));
        }

        $model = ApplicantPost::findById($post['id'], [
                    'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ]);
        if ($model === null) {
            throw new AppException(Yii::t('app', 'model.notfound'));
        }
        if (ApplicantPost::checkStatusForCancel($post['id'], ['applicantId' => $model['applicant_id']]) !== ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
            throw new AppException(Yii::t('app', 'forbidden.cancel.post'));
        }

        $model->application_status = ApplicantPost::APPLICATION_STATUS_CANCELED;
        $model->modified_on = time();
        $model->save(TRUE, ['application_status', 'modified_on']);

        return \components\Helper::outputJsonResponse(['success' => 1]);
    }
    
    /**
     * update-media
     * @return type
     */
    public function actionLogProfileUpdateStatus()
    {
        $post = Yii::$app->request->post();
        $logProfile = \common\models\LogProfile::findByGuid($post['guid'], ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
        if($logProfile === null) {
            throw new AppException(Yii::t('app', 'model.notfound', ['title' => 'Record']));
        }
        $logProfile->status = $post['status'];
        $logProfile->save(TRUE, ['status']);
        
        $data = [
            'applicant_id' => $logProfile->applicant_id,
            'log_profile_id' => $logProfile->id,
            'status' => $post['status'],
            'remarks' => $post['remarks'],
            'created_by' => Yii::$app->user->id,
        ];
        $logProfileActivityModel = new \common\models\LogProfileActivity();
        $logProfileActivityModel->createLogProfileActivity($data);
        
        if ($post['status'] == \common\models\LogProfile::STATUS_APPROVED) {
            \common\models\Applicant::updateOtr($logProfile->id);
        }

        return \components\Helper::outputJsonResponse(['success' => 1]);
    }

}