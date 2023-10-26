<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\components;

use Yii;
use frontend\models\RegistrationForm;
use yii\helpers\ArrayHelper;
/**
 * Description of AgeValidatorComponent
 *
 * @author Amit Handa
 */
class AgeValidatorComponent extends \yii\base\Component
{
    public $classifiedId = null;
    public $dob;
    public $minCalculateDate = null;
    public $maxCalculateDate = null;
    public $minAge;
    public $maxAge;
    public $year;
    public $month;
    public $day;

    public function validate()
    {
        
        $mstClassified = \common\models\MstClassified::findById($this->classifiedId);
        if ($mstClassified == null) {
            throw new \components\exceptions\AppException("Oops! Advertisement not found.");
        }
        
        $age = $this->calculateAge($this->dob, $mstClassified['reference_date']);
        if ($age < $this->minAge || $age > ($this->maxAge)) {
            return false;
        }

        return true;
    }
    
    private function calculateAge($dob, $ageCalculateDate = null)
    {
        $ageCalculateDate = !empty($ageCalculateDate) ? date('Y-m-d', strtotime($ageCalculateDate)) : \common\models\MstClassified::AGE_CALCULATE_DATE;
        return date_diff(date_create($dob), date_create($ageCalculateDate))->y;
    }

}
