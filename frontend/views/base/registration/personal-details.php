<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';

$this->registerJs("RegistrationV2Controller.createUpdate();");
$this->registerJs("RegistrationV2Controller.isDomicile();");
$this->registerJs("RegistrationV2Controller.showHideInputs('orphan');");
$this->registerJs("RegistrationV2Controller.showHideInputs('domicile');");
$this->registerJs("RegistrationV2Controller.formUnloadPrompt('form#personalDetailsForm');");
$this->registerJs("AuthController.encrypt();");
?>
<?=$this->render('/layouts/partials/flash-message.php') ?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step1' => TRUE, 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'personalDetailsForm',
                              'options' => [
                                'class' => 'widget__wrapper-searchFilter',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= yii\bootstrap\Html::activeHiddenInput($model, 'id') ?>
                <?= \yii\bootstrap\Html::activeHiddenInput($model, 'guid') ?>
                <?= $this->render('partials/_basic-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?= $this->render('partials/_identity-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?= $this->render('partials/_personal-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?= $this->render('partials/_domicile-disability-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?= $this->render('partials/_category-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?= $this->render('partials/_other-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>