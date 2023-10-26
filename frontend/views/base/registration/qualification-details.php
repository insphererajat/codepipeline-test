<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use components\Helper;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';

$this->registerJs("RegistrationV2Controller.getQualificationDegree('');");
$this->registerJs("RegistrationV2Controller.getQualificationSubject('');");
$this->registerJs("RegistrationV2Controller.getBoardUniversity('qualification');");
$this->registerJs("RegistrationV2Controller.getBoardUniversityByQualification('qualification');");
$this->registerJs("RegistrationV2Controller.showHideInputs('qualification');");
$this->registerJs("RegistrationV2Controller.calculatePercentage();");
$this->registerJs("RegistrationV2Controller.qualificationConsil();");
$this->registerJs("RegistrationV2Controller.qualification();");
$this->registerJs("RegistrationV2Controller.formUnloadPrompt('form#qualificationDetailsForm');");
?>
<?=$this->render('/layouts/partials/flash-message.php');?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step3' => TRUE, 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'qualificationDetailsForm',
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= $this->render('partials/_qualification-detail.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
                <?= $this->render('partials/_qualification-list.php', ['qualifications' => $qualifications, 'guid' => $guid]); ?>
                <div class="u-flexed u-justify-btw mt-4"> 
                    <a href="<?= Url::toRoute(Helper::stepsUrl('registration/other-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                    <?php if (\common\models\ApplicantQualification::minimumQualificationValidation($model->applicantPostId)): ?>
                        <a href="<?= Url::toRoute(Helper::stepsUrl('registration/employment-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button">Next</a>
                    <?php endif; ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>