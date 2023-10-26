<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use common\models\base\ApplicantExam as BaseApplicantExam;

/**
 * Description of ApplicantExam
 * @author Amit Handa <insphere.amit@gmail.com>
 */
class ApplicantExam extends BaseApplicantExam
{
    const EXAM_TYPE_WRITTEN = 1;
    const EXAM_TYPE_SCREENING_TEST = 2;

    public static function getExamType($key = null)
    {
        $list = [self::EXAM_TYPE_WRITTEN => 'WRITTEN TEST', self::EXAM_TYPE_SCREENING_TEST => 'SCREENING TEST'];
        return isset($list[$key]) ? $list[$key] :$list;
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['modified_on', 'created_on'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['modified_on'],
                ],
            ]
        ];
    }

    private static function findByParams($params = [])
    {
        $modelAQ = self::find();
        $tableName = self::tableName();

        if (isset($params['selectCols']) && !empty($params['selectCols'])) {
            $modelAQ->select($params['selectCols']);
        }else{
            $modelAQ->select($tableName . '.*');
        }

        if (isset($params['id'])) {
            $modelAQ->andWhere($tableName . '.id =:id', [':id' => $params['id']]);
        }
        
        if (isset($params['applicantId'])) {
            $modelAQ->andWhere($tableName . '.applicant_id =:applicantId', [':applicantId' => $params['applicantId']]);
        }

        if (isset($params['applicantPostId'])) {
            $modelAQ->andWhere($tableName . '.applicant_post_id =:applicantPostId', [':applicantPostId' => $params['applicantPostId']]);
        }

        if (isset($params['name'])) {
            $modelAQ->andWhere($tableName . '.name =:name', [':name' => $params['name']]);
        }

        if (isset($params['examType'])) {
            $modelAQ->andWhere($tableName . '.type =:examType', [':examType' => $params['examType']]);
        }

        if (isset($params['examCentreId'])) {
            $modelAQ->andWhere($tableName . '.exam_centre_id =:examCentreId', [':examCentreId' => $params['examCentreId']]);
        }
        
        if (isset($params['examCentreDetailId'])) {
            $modelAQ->andWhere($tableName.'.exam_centre_detail_id = :examCentreDetailId', [':examCentreDetailId' => $params['examCentreDetailId']]);
        }

        if (isset($params['isNotNullRollno'])) {
            $modelAQ->andWhere($tableName . '.rollno IS NOT NULL');
        }

        if (isset($params['joinWithApplicant']) && in_array($params['joinWithApplicant'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicant']}('applicant', 'applicant.id = applicant_exam.applicant_id');
        }

        if (isset($params['joinWithApplicantPost']) && in_array($params['joinWithApplicantPost'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithApplicantPost']}('applicant_post', 'applicant_post.id = applicant_exam.applicant_post_id');

            if (isset($params['joinWithApplicantDetail']) && in_array($params['joinWithApplicantDetail'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
                $modelAQ->{$params['joinWithApplicantDetail']}('applicant_detail', 'applicant_post.id = applicant_detail.applicant_post_id');
            }

            if (isset($params['joinWithApplicantDocument']) && in_array($params['joinWithApplicantDocument'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
                $modelAQ->{$params['joinWithApplicantDocument']}('applicant_document', 'applicant_post.id = applicant_document.applicant_post_id and applicant_document.type = 1');
            }

            if (isset($params['joinWithMedia']) && in_array($params['joinWithMedia'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
                $modelAQ->{$params['joinWithMedia']}('media', 'media.id = applicant_document.media_id and applicant_document.type = 1');
            }
        }

        if (isset($params['joinWithExamCentreDetail']) && in_array($params['joinWithExamCentreDetail'], ['innerJoin', 'leftJoin', 'rightJoin'])) {
            $modelAQ->{$params['joinWithExamCentreDetail']}('exam_centre_detail', 'exam_centre_detail.id = applicant_exam.exam_centre_detail_id');
            $modelAQ->{$params['joinWithExamCentreDetail']}('exam_centre', 'exam_centre.id = applicant_exam.exam_centre_id');
        }

        return (new caching\ModelCache(self::className(), $params))->getOrSetByParams($modelAQ, $params);
    }
    
    public static function findById($id, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['id' => $id], $params));
    }

    public static function findByApplicantPostId($applicantPostId, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['applicantPostId' => $applicantPostId], $params));
    }

    public static function findByExamType($examType, $params = [])
    {
        return self::findByParams(\yii\helpers\ArrayHelper::merge(['examType' => $examType], $params));
    }

    /**
     * Get Hall ticket data
     */
    public static function getHallTicketDate($applicantPostId, $params = [])
    {
        return self::findByApplicantPostId($applicantPostId, [
            'selectCols' => ['applicant_exam.id', 'applicant_post.application_no', 'applicant_detail.father_name', 'applicant_detail.date_of_birth', 'applicant_post.classified_id', 'applicant.name', 'applicant_exam.type', 'applicant_exam.rollno', 'applicant_exam.is_downloaded', 'applicant_detail.social_category_id',
        'exam_centre.name as exam_centre_name', 'media.id media_id', 'media.cdn_path', 'exam_centre_detail.date as examdate', 'exam_centre_detail.examtime', 'applicant_exam.comments', 'exam_centre_detail.examination'],
            'examType' => isset($params['examType']) ? $params['examType'] : self::EXAM_TYPE_WRITTEN,
            'joinWithExamCentreDetail' => 'innerJoin',
            'joinWithApplicantPost' => 'innerJoin',
            'joinWithApplicant' => 'innerJoin',
            'joinWithApplicantDetail' => 'innerJoin',
            'joinWithApplicantDocument' => 'innerJoin',
            'joinWithMedia' => 'innerJoin'
        ]);
    }

    public function sendAdmitCardNotification()
    {
        $classifiedId = 5;
        $query = "select applicant_exam.applicant_id, applicant_exam.applicant_post_id, applicant_exam.id, applicant_exam.rollno, applicant.mobile, applicant.email from applicant_post inner join applicant on applicant.id = applicant_post.applicant_id INNER JOIN applicant_exam on applicant_post.id = applicant_exam.applicant_post_id where applicant_exam.type = 1 AND applicant_exam.is_notification = 0 AND applicant_post.application_status = " . \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED . " AND classified_id = " . $classifiedId. " limit 50";
        $applicantPosts = Yii::$app->db->createCommand($query)->queryAll();
        foreach ($applicantPosts as $key => $applicant) {
            ApplicantExam::updateAll(['is_notification' => 2], 'id=:id', [':id' => $applicant['id']]);
        }
        foreach ($applicantPosts as $key => $applicant) {
            $msg = "Dear Candidate, Kindly download your admit card for Junior Office Assistant (IT) Exam Date is 17th December, 2022 (Saturday) from https://rb.gy/nfniaa - HPSLSA. Regards CSC SPV";
            \Yii::$app->sms->sendMesssage($applicant['mobile'], $msg, ['service' => \common\models\MstMessageTemplate::SERVICE_ADMIT_CARD, 'subject' => 'Admit Card Notification', 'applicantId' => $applicant['applicant_id'], 'applicantPostId' => $applicant['applicant_post_id']]);
            $msg = "It is to inform you that the Written Test for the post of Junior
            Office Assistant (IT) (on contract basis) will be conducted on 17 th
            December, 2022 (Saturday) from 11:00 AM to 01:00 PM. at Digital Vision
            Online (P) Limited, Panthaghati, Near Shiv Mandir, Panthaghati, Shimla-
            171009. <br/><br/>". "Dear Candidate, Kindly download your admit card for Junior Office Assistant (IT) Exam Date is 17th December, 2022 (Saturday) from 11:00 AM to 01:00 PM from https://rb.gy/nfniaa - HPSLSA. <br/><br/> Regards CSC SPV";
            \Yii::$app->email->notificationEmail($applicant['email'], $msg, '', 'Admit Card Notification', ['applicantId' => $applicant['applicant_id'], 'applicantPostId' => $applicant['applicant_post_id']]);
            ApplicantExam::updateAll(['is_notification' => 1, 'notification_on' => time()], 'id=:id', [':id' => $applicant['id']]);
        }
        die('all done');
    }

    public function sendInterviewNotification()
    {
        $query = "SELECT applicant.email, applicant.mobile, applicant_post.applicant_id, applicant_exam.applicant_post_id FROM applicant_exam INNER JOIN applicant_post ON applicant_post.id = applicant_exam.applicant_post_id INNER JOIN applicant ON applicant.id = applicant_post.applicant_id WHERE applicant_exam.rollno IN(5014, 5028, 5029, 5044, 5055, 5073, 5110, 5142, 5181, 5198);";
        $applicantPosts = Yii::$app->db->createCommand($query)->queryAll();
        foreach ($applicantPosts as $key => $applicant) {
            $msg = "You are required to appear for practical test on 7th December, 2022 at 10.00 a.m. in the office of H.P. State Legal Services Authority, Block No.22, SDA Complex, Kasumpti, Shimla-171009. The list of eligible candidates are enclosed herewith. Regards , CSCSPV HPSLSA";
            \Yii::$app->sms->sendMesssage($applicant['mobile'], $msg, ['service' => \common\models\MstMessageTemplate::SERVICE_INTERVIEW, 'subject' => 'Admit Card Notification', 'applicantId' => $applicant['applicant_id'], 'applicantPostId' => $applicant['applicant_post_id']]);
            $msg = "You are required to appear for practical test on 7th December, 2022 at 10.00 a.m. in the office of H.P. State Legal Services Authority, Block No.22, SDA Complex, Kasumpti, Shimla-171009. The list of eligible candidates are enclosed herewith.<br/><br/> Regards,<br/> CSCSPV HPSLSA";
            \Yii::$app->email->notificationEmail($applicant['email'], $msg, '', 'PRACTICAL TEST/INTERVIEW/VIVA VOCE', ['applicantId' => $applicant['applicant_id'], 'applicantPostId' => $applicant['applicant_post_id']]);
        }
        die('all done');
    }

    public function sendTypingTestJoaNotification()
    {
        $classifiedId = 5;
        $query = "select applicant_exam.applicant_id, applicant_exam.applicant_post_id, applicant_exam.id, applicant_exam.rollno, applicant.name, applicant.mobile, applicant.email from applicant_post inner join applicant on applicant.id = applicant_post.applicant_id INNER JOIN applicant_exam on applicant_post.id = applicant_exam.applicant_post_id where applicant_exam.type = 1 AND applicant_post.application_status = " . \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED . " AND classified_id = " . $classifiedId. " AND applicant_exam.rollno in(1648,1573,1588,1457,1675,1332,1303,1669,1064) limit 50";
        $applicantPosts = Yii::$app->db->createCommand($query)->queryAll();
        //echo '<pre>';print_r($applicantPosts);die;
        foreach ($applicantPosts as $key => $applicant) {
            $msg = "It is to inform you that the Typing Test for the post of Junior Office Assistant (IT) (on contract basis) will be conducted on 9th January, 2023 (Monday) at 11:00 AM at Digital Vision Online (P) Limited, Panthaghati, Near Shiv Mandir, Panthaghati, Shimla-171009. You are hereby directed to report at the venue at least half an hour before the scheduled time of typing test on 9th January, 2023 (Monday). Regards , CSCSPV HPSLSA";
            \Yii::$app->sms->sendMesssage($applicant['mobile'], $msg, ['service' => \common\models\MstMessageTemplate::SERVICE_TYPING_TEST_JOA, 'subject' => 'Typing Test for the post of Junior Office Assistant', 'applicantId' => $applicant['applicant_id'], 'applicantPostId' => $applicant['applicant_post_id']]);
            \Yii::$app->email->notificationEmail($applicant['email'], $msg, '', 'Typing Test for the post of Junior Office Assistant', ['applicantId' => $applicant['applicant_id'], 'applicantPostId' => $applicant['applicant_post_id']]);
        }
        die('all done');
    }
}