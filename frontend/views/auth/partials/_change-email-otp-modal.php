<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="modal-dialog">
    <?php
    $form = ActiveForm::begin([
                'id' => 'change-request-form',
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'options' => [
                    'name' => 'change-email-form',
                    'class' => 'horizontal-form',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="modal-content">
        <div class="c-sectionHeader design2">
            <div class="c-sectionHeader__container">
                <div class="c-sectionHeader__label">
                    <div class="c-sectionHeader__label__title fs16__medium">Enter New Email</div>
                </div>
                <button type="button" class="adm-u-close" data-dismiss="modal"></button>
            </div>
        </div>
        <div class="modal-body">
            <div class="c-form u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
                <div class="form-grider design1">
                    <?= Html::activeHiddenInput($model, 'type'); ?>
                    <?= Html::activeHiddenInput($model, 'applicant_id'); ?>
                    <?=
                            $form->field($model, 'email', [
                                'template' => "</div><div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>"
                            ])
                            ->textInput([
                                'autocomplete' => 'off',
                                'maxlength' => true,
                                'placeholder' => "Enter new email",
                                'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 disable-copy-paste'
                            ])
                            ->label(FALSE);
                    ?>
                </div>
                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                    <?= Html::submitButton('Submit', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button submitClass', 'name' => 'class-button']) ?>

                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
