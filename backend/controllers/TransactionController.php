<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use common\models\caching\ModelCache;
use common\models\search\TransactionSearch;
use common\models\Transaction;
/**
 * Description of TransactionController
 *
 * @author HP
 */
class TransactionController extends AdminController
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->hasAdminRole() && !Yii::$app->user->hasClientAdminRole() && !Yii::$app->user->hasHelpdeskRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        $searchModel = new TransactionSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);

    }
    
    public function actionExport()
    {
        if (!Yii::$app->user->hasAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        set_time_limit(-1);
        $searchModel = new TransactionSearch;
        $transactionModel = $searchModel->search(\Yii::$app->request->queryParams, true);

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Sr.No.')
                ->setCellValue('B1', 'Date')
                ->setCellValue('C1', 'Gateway')
                ->setCellValue('D1', 'Transaction No.')
                ->setCellValue('E1', 'Gateway Transaction No.')
                ->setCellValue('F1', 'Amount')
                ->setCellValue('G1', 'Applicant Name')
                ->setCellValue('H1', 'Email')
                ->setCellValue('I1', 'Status');

        if (!empty($transactionModel)) {
            $counter = 2;
            foreach ($transactionModel as $key => $model) {

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $counter, ($key + 1));
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $counter, \components\Helper::convertNetworkTimeZone($model->created_on, 'd F Y', Yii::$app->timeZone, 'UTC'));
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $counter, $model->type);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $counter, $model->transaction_id);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $counter, $model->gateway_id);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $counter, $model->amount);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $counter, $model->applicant->name);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $counter, $model->applicant->email);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $counter, $model->status);
                $counter++;
            }
        } else {
            \Yii::$app->session->setFlash('error', 'Oops no record found.');
            return $this->redirect(\yii\helpers\Url::toRoute(['index']));
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="transactions.xls"');
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
    
    public function actionSchedule($transactionId)
    {
        if (!Yii::$app->user->hasAdminRole()) {
            throw new \components\exceptions\AppException(Yii::t('app', 'forbidden.access'));
        }
        $transactionModel = Transaction::findByTransactionId($transactionId, [
            'resultFormat' => \common\models\caching\ModelCache::RETURN_TYPE_OBJECT
        ]);

        if(empty($transactionModel)){
            throw new \components\exceptions\AppException("Invalid Transaction");
        }
        
        try{
            $transactionObj = new Transaction();
            $result = $transactionObj->processScheduler($transactionModel->id, $transactionModel->type);
            if($result == true){
                $this->setSuccessMessage('Transaction has been updated successfully');
            }else{
                $this->setErrorMessage($transactionObj->schedulerJobError);
            }
            
            return $this->redirect(Yii::$app->request->referrer);
        } catch (\Exception $ex) {
            throw new \components\exceptions\AppException($ex->getMessage());
        }
    }
}