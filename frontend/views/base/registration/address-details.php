<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use components\Helper;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';

$this->registerJs("RegistrationV2Controller.copypresentAddress();");
$this->registerJs("RegistrationV2Controller.getDistrict('presentAddress');");
$this->registerJs("RegistrationV2Controller.getDistrict('permanentAddress');");
$this->registerJs("RegistrationV2Controller.getTehsil('presentAddress');");
$this->registerJs("RegistrationV2Controller.getTehsil('permanentAddress');");
$this->registerJs("RegistrationV2Controller.copyTypeAdress();");
$this->registerJs("RegistrationV2Controller.formUnloadPrompt('form#addressDetailsForm');");
?><?= $this->render('/layouts/partials/flash-message.php'); ?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step2' => TRUE , 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'addressDetailsForm',
//                            'validateOnBlur' => false,
//                            'enableAjaxValidation' => FALSE,
//                            'enableClientValidation'=>FALSE,
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= $this->render('partials/_address-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <div class="u-flexed u-justify-btw mt-4"> 
                    <a href="<?= Url::toRoute(Helper::stepsUrl('registration/personal-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                    <?= yii\helpers\Html::submitButton('Save', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'submitButton']) ?>
                    <a href="<?= Url::toRoute(Helper::stepsUrl('registration/qualification-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button">Next</a>

                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>