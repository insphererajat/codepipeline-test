<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use components\Helper;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';

$this->registerJs("RegistrationV2Controller.showHideInputs('employmentType');");
$this->registerJs("RegistrationV2Controller.showHideInputs('employed');");
$this->registerJs("RegistrationV2Controller.employmentDetail();");

if ($model->applicantPostFormStep >= 5) {
    $this->registerJs("RegistrationV2Controller.formUnloadPrompt('form#employmentDetailsForm');");
}
?>
<?= $this->render('/layouts/partials/flash-message.php'); ?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step4' => TRUE, 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'employmentDetailsForm',
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= $this->render('partials/_employment-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <div class="u-flexed u-justify-center mt-4"> 
                    <?= yii\helpers\Html::submitButton('Save', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'submitButton']) ?>
                </div>
                <?= $this->render('partials/_employment-list.php', ['employments' => $employments, 'guid' => $guid]); ?>
                <div class="u-flexed u-justify-btw mt-4"> 
                    <a href="<?= Url::toRoute(Helper::stepsUrl('registration/qualification-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                    <?php if ($model->applicantPostFormStep >= 4): ?>
                        <a href="<?= Url::toRoute(Helper::stepsUrl('registration/document-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button">Next</a>
                    <?php else: ?>
                        <?= yii\helpers\Html::submitButton('Next', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'submitButton']) ?>
                    <?php endif; ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>