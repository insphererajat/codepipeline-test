<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';

$this->registerJs("RegistrationV2Controller.createUpdate();")
?>
<?= $this->render('/layouts/partials/flash-message.php'); ?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step1' => TRUE, 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'basicDetailsForm',
                            'enableClientValidation' => false,
                            'enableAjaxValidation' => false,
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter js-formCls',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= $this->render('partials/_basic-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>