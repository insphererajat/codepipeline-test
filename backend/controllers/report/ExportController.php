<?php

namespace backend\controllers\report;

use common\models\MstClassified;
use components\exceptions\AppException;
use components\Helper;
use Yii;

/**
 * Description of ExportWiseController
 *
 * @author Amit Handa
 */
class ExportController extends \backend\controllers\AdminController
{
    private $_searchModel = [];
    
    public function actionMis()
    {
        if (!Yii::$app->user->hasAdminRole() && !Yii::$app->user->hasClientAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }

        $searchModel = new \common\models\search\ApplicantSearch();
        $params = \Yii::$app->request->queryParams;

        $searchModel->load($params);
        if(!$searchModel->validate()) {
            throw new AppException(Helper::convertModelErrorsToString($searchModel->errors));
        }

        if (isset($params['ApplicantSearch']['classified_id']) && !empty($params['ApplicantSearch']['classified_id'])) {
            $classified = $params['ApplicantSearch']['classified_id'];
            $sql = "SELECT applicant_exam.rollno, applicant_post.id, 
            applicant_post.application_no,
            mst_classified.title classified_name,
            transaction.`transaction_id`, 
            applicant.`name` applicant_name,
            applicant_detail.`father_name`,
            applicant_detail.`mother_name`,
            applicant.`mobile`,
            birth_state,
            CONCAT(permanent_house_no, 
            permanent_premises_name, ' ',
            permanent_street,  ' ',
            permanent_area,  ' ',
            permanent_landmark,  ' ',

            permanent_village_name, ' ',
            permanent_pincode,  ' ',
            permanent_district,  ' ',
            permanent_state) permanent_address,
            CONCAT(
            current_house_no,  ' ',
            current_premises_name,  ' ',
            current_street,  ' ',
            current_area,  ' ',
            current_landmark,  ' ',

            current_village_name,  ' ',
            current_pincode, ' ',
            current_district, ' ', 
            current_state) current_address,

            CASE WHEN applicant_detail.social_category_id = 11 THEN 'Unreserved/General' 
            WHEN applicant_detail.social_category_id = 14 THEN 'SC' 
            WHEN applicant_detail.social_category_id = 12 THEN 'ST' 
            WHEN applicant_detail.social_category_id = 15 THEN 'OBC' 
            WHEN applicant_detail.social_category_id = 13 THEN 'EWS' END Category_Name,
            applicant_detail.`date_of_birth`,
            applicant_detail.`gender` Gender,
            mst_qualification.`name` last_qualification ,
            ROUND(SUM(DATEDIFF(applicant_employment.`end_date`, applicant_employment.`start_date`))/30, 0) exp_in_month,
            CASE WHEN   `is_domiciled` = 1 THEN 'yes' ELSE 'No' END is_domicile,
            applicant.email,
            transaction.`amount`,
            DATE(FROM_UNIXTIME(applicant_fee.`created_on`)) transaction_date
            FROM  `applicant_post`
            JOIN applicant ON applicant_post.`applicant_id` = applicant.id
            LEFT JOIN applicant_exam on applicant_post.id = applicant_exam.applicant_post_id
            JOIN (SELECT applicant_post_id, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.house_no END) permanent_house_no, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.premises_name END) permanent_premises_name, 
            MAX(CASE WHEN applicant_address.address_type = 1  THEN applicant_address.street END) permanent_street, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.area END) permanent_area, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.landmark END) permanent_landmark, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.tehsil_name END) permanent_tehsil_name, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.village_name END) permanent_village_name, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN applicant_address.`pincode` END) permanent_pincode, 

            MAX(CASE WHEN applicant_address.address_type = 1 THEN mst_district.`name` END) permanent_district, 
            MAX(CASE WHEN applicant_address.address_type = 1 THEN mst_state.name END) permanent_state, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.house_no END)  current_house_no, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.premises_name END)  current_premises_name, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.street END)  current_street, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.area END)  current_area, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.landmark END)  current_landmark, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.tehsil_name END)  current_tehsil_name, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.village_name END)  current_village_name, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN applicant_address.pincode END)  current_pincode, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN mst_district.`name` END)  current_district, 
            MAX(CASE WHEN applicant_address.address_type = 0  THEN mst_state.name END)  current_state
            FROM applicant_address
            JOIN applicant_post ON applicant_address.`applicant_post_id` = applicant_post.`id`
            JOIN mst_district ON applicant_address.district_code = mst_district.`code`
            JOIN mst_state ON applicant_address.`state_code` = mst_state.code AND mst_district.`state_code` = applicant_address.state_code
            WHERE applicant_post.`post_id`> 0 AND application_status = 1
            GROUP BY applicant_post_id
            )applicant_address ON applicant_address.`applicant_post_id` = applicant_post.`id`
            JOIN applicant_fee ON applicant_fee.`applicant_post_id` = applicant_post.`id`
            JOIN `transaction` ON transaction.`applicant_fee_id` = applicant_fee.`id`
            JOIN applicant_detail ON applicant_post.id = applicant_detail.`applicant_post_id`
            LEFT JOIN (SELECT applicant_post_id, MAX(id) mq_id FROM applicant_qualification GROUP BY applicant_post_id) q ON applicant_post.id = q.applicant_post_id
            LEFT JOIN applicant_qualification ON applicant_qualification.`applicant_post_id` = q.applicant_post_id AND applicant_qualification.`id` = q.mq_id
            JOIN mst_qualification ON applicant_qualification.`qualification_degree_id` = mst_qualification.`id`
            LEFT JOIN 
            (SELECT applicant_detail.applicant_post_id, mst_state.name birth_state 
            FROM applicant_detail
            JOIN mst_state ON mst_state.code = applicant_detail.birth_state_code ) applicant_detail_birth ON applicant_detail_birth.applicant_post_id = applicant_post.id
            LEFT JOIN applicant_employment ON applicant_employment.applicant_post_id =applicant_post.`id`
            JOIN mst_post ON applicant_post.`post_id` = mst_post.`id`
            JOIN mst_classified ON applicant_post.`classified_id` = mst_classified.`id` 
            LEFT JOIN mst_list_type ON mst_list_type.id = applicant_detail.`disability_id`
            WHERE applicant_post.`classified_id` = {$classified}
            AND application_status = 1
            AND transaction.`status` = 'PAID'
            GROUP BY applicant_post.id;";
            set_time_limit(-1);
            $records = Yii::$app->db->createCommand($sql)->queryAll();
            $objPHPExcel = new \PHPExcel();
            
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:U1');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, ucwords(('Application Received Report')));
            $objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getFont()->setBold(true)->setName('Times New Roman')->setSize(15)->getColor()->setRGB('FFFFFF');
            $objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getFill()->applyFromArray([
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => [
                    'rgb' => '151B54'
                ]
            ]);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', 'Sr.No.')
                    ->setCellValue('B2', 'Advt.Ref.No')
                    ->setCellValue('C2', 'Advt. Name')
                    ->setCellValue('D2', 'TransactionID')
                    ->setCellValue('E2', 'Name')
                    ->setCellValue('F2', 'Father Name')
                    ->setCellValue('G2', 'Mother Name')
                    ->setCellValue('H2', 'Mobile No')
                    ->setCellValue('I2', 'State(Birth)')
                    ->setCellValue('J2', 'Permanent Address')
                    ->setCellValue('K2', 'Correspondance Address')
                    ->setCellValue('L2', 'Category')
                    ->setCellValue('M2', 'Date of Birth')
                    ->setCellValue('N2', 'Gender')
                    ->setCellValue('O2', 'Qualification ')
                    ->setCellValue('P2', 'Total Experience(In Months)')
                    ->setCellValue('Q2', 'Domicile')
                    ->setCellValue('R2', 'Email-Id')
                    ->setCellValue('S2', 'Exam Fee')
                    ->setCellValue('T2', 'Transaction Date')
                    ->setCellValue('U2', 'Rollno');

            if (!empty($records)) {
                $counter = 3;
                foreach ($records as $key => $record) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, ($key + 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, $record['application_no']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, $record['classified_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $counter, $record['transaction_id']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $record['applicant_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $record['father_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $record['mother_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $record['mobile']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, $record['birth_state']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $counter, $record['permanent_address']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $counter, $record['current_address']);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $counter, $record['Category_Name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $counter, $record['date_of_birth']);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $counter, $record['Gender']);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $counter, $record['last_qualification']);
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $counter, $record['exp_in_month']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $counter, $record['is_domicile']);
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $counter, $record['email']);
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $counter, $record['amount']);
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $counter, $record['transaction_date']);
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $counter, $record['rollno']);

                    $counter++;
                }
            } else {
                \Yii::$app->session->setFlash('error', 'Oops no record found.');
                return $this->redirect(\yii\helpers\Url::toRoute(['mis']));
            }

            $advt = MstClassified::getTitle($classified);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="MIS("'.$advt.').xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            $objWriter->save('php://output');
            exit;
        }

        return $this->render('mis', [
                    'model' => $searchModel
        ]);
    }
    
    public function actionApplicationReceived()
    {
        $records = $this->_exportApplicationReceived();
        $params = \Yii::$app->request->queryParams;
        if (!empty($params)) {
            $this->layout = false;
            return $this->render('application-received-print', [
                'model' => $this->_searchModel,
                'records' => $records
            ]);
        }
        else {
            return $this->render('application-received', [
                'model' => $this->_searchModel,
                'records' => $records
            ]);
        }
    }
    
    private function _exportApplicationReceived()
    {
        if (!Yii::$app->user->hasAdminRole() && !Yii::$app->user->hasClientAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }

        $this->_searchModel = new \common\models\search\ApplicantSearch();
        $params = \Yii::$app->request->queryParams;
        $this->_searchModel->load($params);

        if(!$this->_searchModel->validate()) {
            throw new AppException(Helper::convertModelErrorsToString($this->_searchModel->errors));
        }
        if (!empty($params)) {
            $conditions = "true = true";
            if (isset($params['ApplicantSearch']['classified_id']) && !empty($params['ApplicantSearch']['classified_id'])) {
                $conditions .= " AND applicant_post.classified_id = ". $params['ApplicantSearch']['classified_id'];
            }
            $sql = "SELECT mst_classified.code advt_no, mst_classified.title advt_name, COUNT(DISTINCT applicant_post.id) AS total_applicants,
COUNT(DISTINCT CASE WHEN applicant_post.application_status = 1 THEN applicant_post.id END) AS paid,
COUNT(DISTINCT CASE WHEN applicant_post.application_status = 0 THEN applicant_post.id END) AS unpaid,
COUNT(DISTINCT CASE WHEN applicant_post.application_status = 2 THEN applicant_post.id END) AS cancelled,
COUNT(DISTINCT CASE WHEN applicant_post.application_status = 3 THEN applicant_post.id END) AS re_applied,

SUM(CASE WHEN applicant_post.application_status = 1 AND is_consumed= 1 THEN transaction.amount ELSE 0 END) AS paid_consumed_payment,
SUM(CASE WHEN applicant_post.application_status = 1 AND is_consumed= 0 THEN transaction.amount ELSE 0 END) AS paid_unconsumed_payment,
SUM(CASE WHEN applicant_post.application_status = 2 AND is_consumed= 1 THEN transaction.amount ELSE 0 END) AS cancelled_consumed_payment,
SUM(CASE WHEN applicant_post.application_status = 2 AND is_consumed= 0 THEN transaction.amount ELSE 0 END) AS cancelled_unconsumed_payment,
SUM(CASE WHEN applicant_post.application_status = 3 AND is_consumed= 1 THEN transaction.amount ELSE 0 END) AS reapplied_consumed_payment,
SUM(CASE WHEN applicant_post.application_status = 3 AND is_consumed= 0 THEN transaction.amount ELSE 0 END) AS reapplied_unconsumed_payment

FROM applicant_post 
INNER JOIN mst_classified ON mst_classified.id = applicant_post.classified_id
LEFT JOIN applicant_fee ON applicant_fee.`applicant_post_id` = applicant_post.`id` AND applicant_fee.`status` = 1
LEFT JOIN `transaction` ON transaction.applicant_fee_id = applicant_fee.`id` AND transaction.status = 'PAID' 
WHERE {$conditions}
AND applicant_post.post_id != 0
GROUP BY applicant_post.classified_id;";
            set_time_limit(-1);
            return Yii::$app->db->createCommand($sql)->queryAll();
        }
    }
    
    public function actionApplicationReceivedExport()
    {
        $records = $this->_exportApplicationReceived();
        if (!empty($records)) {
            $objPHPExcel = new \PHPExcel();

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:Q1');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, ucwords(('Application Received Report')));
            $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true)->setName('Times New Roman')->setSize(15)->getColor()->setRGB('FFFFFF');
            $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->applyFromArray([
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => [
                    'rgb' => '151B54'
                ]
            ]);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', 'Sr.No.')
                    ->setCellValue('B2', 'Advnumber')
                    ->setCellValue('C2', 'Advt. Name')
                    ->setCellValue('D2', 'Total Applications Received')
                    ->setCellValue('E2', 'Paid Applications')
                    ->setCellValue('F2', 'Unpaid Applications')
                    ->setCellValue('G2', 'Cancelled Applications')
                    ->setCellValue('H2', 'Reapply Applications')
                    ->setCellValue('I2', 'Online Paid Consumed Payment')
                    ->setCellValue('J2', 'Online Paid Unconsumed Payment')
                    ->setCellValue('K2', 'Online Cancelled Consumed Payment')
                    ->setCellValue('L2', 'Online Cancelled Unconsumed Payment')
                    ->setCellValue('M2', 'Online Reapply Consumed Payment')
                    ->setCellValue('N2', 'Online Reapply Unconsumed Payment')
                    ->setCellValue('O2', 'Exempted')
                    ->setCellValue('P2', 'Cancelled Exempted')
                    ->setCellValue('Q2', 'Exam Fees');

            if (!empty($records)) {
                $counter = 3;
                foreach ($records as $key => $record) {

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, ($key + 1));
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, $record['advt_no']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, $record['advt_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $counter, $record['total_applicants']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $record['paid']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $record['unpaid']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $record['cancelled']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $record['re_applied']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, $record['paid_consumed_payment']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $counter, $record['paid_unconsumed_payment']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $counter, $record['cancelled_consumed_payment']);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $counter, $record['cancelled_unconsumed_payment']);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $counter, $record['reapplied_consumed_payment']);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $counter, $record['reapplied_unconsumed_payment']);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $counter, 0);
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $counter, 0);
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $counter, 0);

                    $counter++;
                }
            }
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="application-received.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_end_clean();
            $objWriter->save('php://output');
            exit;
        } else {
            \Yii::$app->session->setFlash('error', 'Oops no record found.');
            return $this->redirect(\yii\helpers\Url::toRoute(['application-received']));
        }
    }

    public function actionAttendance()
    {

        $searchModel = new \common\models\search\ApplicantSearch();
        $params = \Yii::$app->request->queryParams;

        $searchModel->load($params);
        if(!$searchModel->validate()) {
            throw new AppException(Helper::convertModelErrorsToString($searchModel->errors));
        }
        if (isset($params['ApplicantSearch']['classified_id']) && !empty($params['ApplicantSearch']['classified_id']) && !empty($params['ApplicantSearch']['exam_centre_id'])) {
            $searchPrams = $params['ApplicantSearch'];
            $this->layout = false;
            $classifiedId = $params['ApplicantSearch']['classified_id'];
            $conditions = "";
            if(!empty($searchPrams['exam_centre_id'])) {
                $conditions .= " AND applicant_exam.exam_centre_id = '".$searchPrams['exam_centre_id']."'";
            }
            set_time_limit(-1);
            $sql ="SELECT applicant.name, applicant_exam.comments, applicant_exam.rollno, applicant_detail.father_name, applicant_detail.social_category_id, applicant_detail.disability_id,
            photo,
            signature
            FROM applicant_exam 
            INNER JOIN applicant ON applicant.id = applicant_exam.applicant_id 
            INNER JOIN applicant_post ON applicant_post.id = applicant_exam.applicant_post_id 
            INNER JOIN applicant_detail ON applicant_post.id = applicant_detail.applicant_post_id
            INNER JOIN exam_centre_detail ON exam_centre_detail.id = applicant_exam.exam_centre_detail_id
            LEFT JOIN (SELECT applicant_document.media_id, applicant_document.applicant_post_id, 
            MAX(CASE WHEN `type` = 1 THEN media.cdn_path END) photo,
            MAX(CASE WHEN `type` = 2 THEN media.cdn_path END) signature
            FROM applicant_document
            JOIN media ON media.id = applicant_document.media_id
            GROUP BY applicant_document.applicant_post_id
            ) applicant_document ON applicant_post.id = applicant_document.applicant_post_id
            WHERE applicant_exam.rollno IS NOT NULL 
            AND applicant_exam.type = ".$searchPrams['exam_type']." 
            AND applicant_post.application_status  = 1
            AND applicant_post.classified_id={$classifiedId} {$conditions} ORDER BY applicant_exam.rollno ASC;";
            $records = Yii::$app->db->createCommand($sql)->queryAll();
            return $this->render('attendance/'.$searchPrams['exam_type'].'/'.$classifiedId, [
                'records' => $records,
                'classifiedId' => $classifiedId
            ]);
        }

        return $this->render('attendance-filter', [
                    'model' => $searchModel
        ]);
    }
}