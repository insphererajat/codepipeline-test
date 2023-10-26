<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\components;

use Yii;
/**
 * Description of ImportComponent
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class ImportComponent extends \yii\base\Component
{

    public function dataFile($filePath, $mapingFields = [], $startCounter = 0, $requiredFields = [], $dateColumn =[])
    {
        $filename = explode('/',$filePath);
        $localFilePath = Yii::$app->params['upload.dir'] . "/" . Yii::$app->params['upload.dir.tempFolderName'].'/'. end($filename);
        $getLocalFilePath = $this->downloadfile($filePath, $localFilePath);

        if (!$this->isFileExists($getLocalFilePath)) {
            throw new \components\exceptions\AppException("Oops! We could not find imported file form given file path.");
        }

        try {
            
            $excelFactory = \PHPExcel_IOFactory::identify($getLocalFilePath);
            $objReader = \PHPExcel_IOFactory::createReader($excelFactory);
            $objPHPExcel = $objReader->load($getLocalFilePath);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
           
            $i = 0;
            $importedData = [];
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
             
                $counter = $startCounter;
               
                if($counter > $row) {
                    continue;
                }
                
                if ((isset($mapingFields) && !empty($mapingFields)) && (count($rowData[0]) == count($mapingFields))) {

                    foreach ($rowData[0] as $key => $data) { 
                        
                        if(count($requiredFields) > 0 && in_array($key, $requiredFields) && empty($data)) {
                            break;
                        }
                        $alphabet = range('A', 'Z');
                        if(isset($dateColumn) && !empty($dateColumn) && in_array($alphabet[$key], $dateColumn)){ 
                            $date = date('Y-m-d',\PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell($alphabet[$key].''.$row)->getValue()));
                            $importedData[$i][$mapingFields[$key]] = $date; 
                        }
                        else {
                           $importedData[$i][$mapingFields[$key]] = $data; 
                        }
                    }
                }
                
                $i++;
            }
            return $importedData;
        }
        catch (\Exception $ex) {
            throw new \components\exceptions\AppException("Oops! unable to read imported file. Please find below error : " . $ex->getMessage());
        }
    }

    private function isFileExists($filePath)
    {
        return (file_exists($filePath)) ? TRUE : FALSE;
    }
    
    /**
     * Download file from remote server and save on local server
     * @param type $downloadFilePath
     * @param type $saveFilePath
     * @return boolean
     * @throws \Exception
     */
    protected function downloadfile($downloadFilePath, $saveFilePath)
    {
        try {

            $ch = curl_init($downloadFilePath);
            if (!$ch) {
                return false;
            }
            $fp = fopen($saveFilePath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            return $saveFilePath;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
    }

}
