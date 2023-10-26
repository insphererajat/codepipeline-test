<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
$this->registerJs("ApplicantController.encrypt()");
$form = ActiveForm::begin([
            'id' => 'ResetPasswordForm',
            'options' => [
                'class' => 'horizontal-form',
                'autocomplete' => 'off'
            ],
        ]);
?>
<div class="adm-c-mainContainer">
    <?= $this->render('/layouts/partials/flash-message.php') ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs12 cmt-20">
            <div class="adm-c-sectionHeader adm-c-sectionHeader-xs design3">
                <div class="adm-c-sectionHeader__container">
                    <div class="adm-c-sectionHeader__label">
                        <div class="adm-c-sectionHeader__label__title fs16__medium"><?= $this->title; ?></div>
                    </div>

                </div>
            </div>
            <div class="adm-basicBlock white adm-u-pad20_25">
                <div class="adm-c-form u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
                    <?=
                    $form->field($model, 'new_password', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
                    ])->passwordInput([
                        'autocomplete' => 'off',
                        'autofocus' => true,
                        'maxlength' => true,
                        'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
                        'placeholder' => 'New Password'
                    ])->label('New Password');
                    ?>
                    <?=
                    $form->field($model, 'confirm_new_password', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
                    ])->passwordInput([
                        'autocomplete' => 'off',
                        'autofocus' => true,
                        'maxlength' => true,
                        'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
                        'placeholder' => 'Confirm New Password'
                    ])->label('Confirm New Password');
                    ?>
                </div>
            </div>
            <div class="buttons-multiple buttons-multiple-sm cmt-20">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3 submitButton', 'name' => 'class-button']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

