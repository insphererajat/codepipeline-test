<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use components\Helper;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';

$this->registerJs("RegistrationV2Controller.uploadDocuments();");
$this->registerJs("CropController.crop();");
?><?= $this->render('/layouts/partials/flash-message.php'); ?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step5' => TRUE, 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'documentDetailsForm',
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= Html::hiddenInput('RegistrationForm[photo]', $model->photo, ['class' => 'inputPhoto']); ?>
                <?= Html::hiddenInput('RegistrationForm[signature]', $model->signature, ['class' => 'inputSignature']); ?>
                <?= Html::hiddenInput('RegistrationForm[birth_certificate]', $model->birth_certificate, ['class' => 'inputBirth']); ?>
                <?= Html::hiddenInput('RegistrationForm[caste_certificate]', $model->caste_certificate, ['class' => 'inputCaste']); ?>

                <?= $this->render('partials/_document-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <div class="u-flexed u-justify-btw mt-4"> 
                    <a href="<?= Url::toRoute(Helper::stepsUrl('registration/employment-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                    <?= yii\helpers\Html::submitButton('Save & Next', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'submitButton']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>