<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\LogOtp;

$hide = (isset($model->id) && ($model->id) > 0 ) ? 'hide' : '';

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['LogOtpSearch']) && !empty($searchParams['LogOtpSearch'])) ? "" : "hide";
?>
<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'otpForm',
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
        <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4">

            <?=
            $form->field($model, 'otp', [
                'options' => ['class' => 'form-grider cop-form design1'],
                'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                'errorOptions' => ['class' => ' cop-form--help-block'],
                'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
            ])->textInput([
                'autocomplete' => 'off',
                'autofocus' => true,
                'maxlength' => true,
                'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
                'placeholder' => 'Otp'
            ])->label(false);
            ?>
            <?=
                    $form->field($model, 'otp_type', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList([LogOtp::EMAIL_OTP => 'email', LogOtp::MOBILE_OTP => 'mobile'], ['class' => 'chzn-select', 'prompt' => 'Select Type']
                    )->label(FALSE)
            ?>
            <?=
                    $form->field($model, 'is_verified', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList([LogOtp::VERIFIED => 'Verified', LogOtp::NOT_VERIFIED => 'Not Verified'], ['class' => 'chzn-select', 'prompt' => 'Select Verification']
                    )->label(FALSE)
            ?>
        </div>
    <div class="filters-wrapper__action">
        <div class="adm-c-button cml-10 cmt-10">
            <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
            <a href="<?= yii\helpers\Url::toRoute(['otp/index']) ?>" class="btn btn-rounded btn-secondary adm-u-pad8_20 mb-3">Reset</a>
        </div> 
    </div>
    <?php ActiveForm::end(); ?>
</div>