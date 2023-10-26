<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="modal-dialog">
    <?php
    $form = ActiveForm::begin([
                'id' => 'verifyotpform',
                'action' => (isset($url)) ? $url : \yii\helpers\Url::toRoute(['/api/registration/validate-otp']),
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'options' => [
                    'class' => 'horizontal-form',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="modal-content">
        <div class="c-sectionHeader design2">
            <div class="c-sectionHeader__container">
                <div class="c-sectionHeader__label">
                    <div class="c-sectionHeader__label__title fs16__medium">Enter OTP</div>
                </div>
                <button type="button" class="adm-u-close" data-dismiss="modal"></button>
            </div>
        </div>
        <div class="modal-body">
            <div class="c-form u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
                <div class="form-grider design1">
                    <?=Html::hiddenInput('mobileOtpId', $mobileOtpId, ['id' => 'mobileOtpId']);?>
                    <?php if (isset($scenario) && !empty($scenario)): ?>
                        <?= Html::hiddenInput('scenario', $scenario); ?>
                    <?php endif; ?>
                    <?=
                            $form->field($model, 'mobileOtp', [
                                'template' => "<div class='head-wrapper'>
                                        <div class='head-wrapper__title label-required'>
                                        <div class='head-wrapper__title-label fs14__medium'>OTP</div>
                                        </div>
                                        </div><div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                            ->textInput([
                                'type' => 'password',
                                'autocomplete' => 'off',
                                'maxlength' => true,
                                'placeholder' => "Enter OTP",
                                'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                            ])
                            ->label(FALSE);
                    ?>
                    <p class="text-right"><span id="resendMobile" data-type="2" data-otp-type="<?= isset($otpType) ? $otpType : '' ?>" class="resend__otp button-v2 button-v2--primary u-radius4 fs14__regular u-pad2_10 red theme-button d-none">Resend OTP</span></p>
                    <div class="text-right" id="clockdiv"></div>
                </div> 
                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                    <?= Html::submitButton('Submit', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button submitClass', 'name' => 'class-button']) ?>

                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
