<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\components;

use Yii;
use common\models\caching\ModelCache;
use components\exceptions\AppException;
use components\Helper;

/**
 * Description of ApplicantPostComponent
 *
 * @author Amit Handa
 */
class ApplicantPostComponent extends \yii\base\Component
{
    public $applicantId;
    public $classifiedId;
    
    public function checkApplicantPost($guid)
    {
        if(empty($this->applicantId)) {
            return false;
        }
        
        $mstClassified = \common\models\MstClassified::findByGuid($guid);
        if($mstClassified === null) {
            throw new AppException(Yii::t('app', 'model.notfound'));
        }
        
        $lastDate = $mstClassified['end_date'];
        if (!empty($mstClassified['extended_date'])) {
            $lastDate = $mstClassified['extended_date'];
        }
        $this->classifiedId = $mstClassified['id'];
        $lastDateTime = strtotime($lastDate.' 23:59:59');
        if ($mstClassified['is_post_specific'] == \common\models\MstClassified::IS_POST_CLASSIFIED) {
            if (time() > $lastDateTime) {
                throw new AppException(Yii::t('app', 'classified.closed', ['advertisement' => $mstClassified['title']]));
            }
            $applicantPost = \common\models\ApplicantPost::findByApplicantId($this->applicantId, [
                        'notPostId' => \common\models\MstPost::MASTER_POST,
                        'applicationStatus' => \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED,
                        'classifiedId' => $this->classifiedId
            ]);

            if ($applicantPost !== null) {
                throw new AppException(Yii::t('app', 'classified.post.msg'));
            }
        } else if ($mstClassified['is_post_specific'] == \common\models\MstClassified::IS_POST_SPECIFIC) {
            
        }

        return true;
    }
    
    public function checkReApplyPost($id, $params = [])
    {
        $status = false;
        $qp = [
            'resultFormat' => ModelCache::RETURN_TYPE_OBJECT
        ];
        $model = \common\models\ApplicantPost::findById($id, \yii\helpers\ArrayHelper::merge($qp, $params));
        if ($model === NULL) {
            return false;
        }
        
        if($model->classified->cancellation_status !== \common\models\MstClassified::CANCELLED_REAPPLY) {
            return false;
        }
        
        if ($model->application_status !== \common\models\ApplicantPost::APPLICATION_STATUS_CANCELED) {
            return false;
        }
        
        $lastDate = $model->classified->end_date;
        if (isset($model->classified->extended_date) && !empty($model->classified->extended_date)) {
            $lastDate = $model->classified->extended_date;
        }
        $lastDateTime = strtotime($lastDate.' 23:59:59');
        if ($lastDateTime > time()):
            $status = true;
        endif;

        $applicantPost = \common\models\ApplicantPost::findByApplicantId($this->applicantId, [
                    'classifiedId' => $model->classified_id,
                    'notPostId' => \common\models\MstPost::MASTER_POST,
                    'inApplicationStatus' => [\common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED, \common\models\ApplicantPost::APPLICATION_STATUS_PENDING],
                    'countOnly' => true
        ]);

        if($applicantPost > 0) {
            return false;
        }

        return $status;
    }

}
