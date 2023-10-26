<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\Applicant;
use common\models\ApplicantPost;
use common\models\MstClassified;
use common\models\MstPost;

/**
 * Description of HomeController
 *
 * @author Amit Handa
 */
class HomeController extends AdminController
{
    public function actionIndex()
    {        
        return $this->render('index', [
            'data' => [
                'registration' => Applicant::findByKeys(['selectCols' => ['count(id) as count'], 'forceCache' => true, 'cacheTime' => 14400]),
                'applications' => ApplicantPost::findByKeys(['selectCols' => ['count(id) as count'], 'inApplicationStatus' => [ApplicantPost::APPLICATION_STATUS_PENDING, ApplicantPost::APPLICATION_STATUS_SUBMITTED, ApplicantPost::APPLICATION_STATUS_CANCELED, ApplicantPost::APPLICATION_STATUS_REAPPLIED], 'notPostId' => MstPost::MASTER_POST, 'forceCache' => true, 'cacheTime' => 14400]),
                'advt' => MstClassified::findByKeys([
                    'selectCols' => [new \yii\db\Expression("SUM(CASE WHEN is_active = " . MstClassified::IS_ACTIVE_YES . " AND mst_classified.end_date > '".date('Y-m-d')."' THEN 1 ELSE 0 END) as active, SUM(CASE WHEN is_active = " . MstClassified::IS_ACTIVE_COMPLETE . "  THEN 1 ELSE 0 END) as completed")],
                    'notInIds' => [MstClassified::MASTER_CLASSIFIED],
                    'forceCache' => true, 
                    'cacheTime' => 14400
                ]),
                'paidApplications' => ApplicantPost::findByKeys(['selectCols' => ['count(id) as count'], 'inApplicationStatus' => [ApplicantPost::APPLICATION_STATUS_SUBMITTED, ApplicantPost::APPLICATION_STATUS_CANCELED, ApplicantPost::APPLICATION_STATUS_REAPPLIED], 'notPostId' => MstPost::MASTER_POST, 'forceCache' => true, 'cacheTime' => 14400])
            ]
        ]);
    }
}
