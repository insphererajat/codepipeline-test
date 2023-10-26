<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Media;

$media = null;
$hasMedia = 0;
if ($model->profile_media_id > 0) {
    $media = Media::findById($model->profile_media_id);
    if (!empty($media)) {
        $hasMedia = 1;
    }
}
$this->registerJs("ProfileController.summary();");
?>
<div class="adm-basicBlock white adm-u-pad20_25">
    <?php
    $form = ActiveForm::begin([
                'id' => 'admin-profile-form',
                'options' => [
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <?= Html::activeHiddenInput($model, 'id') ?>
    <?= Html::activeHiddenInput($model, 'guid') ?>
    <?= Html::activeHiddenInput($model, 'profile_media_id') ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col2">

        <?=
        $form->field($model, 'username', [
            'options' => ['class' => 'form-grider cop-form design1 col-fullwidth'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper label-required'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Username'
        ])->label('Username');
        ?>
        <?=
        $form->field($model, 'firstname', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper label-required'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 only-alphabet disable-copy-paste',
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'placeholder' => 'Firstname'
        ])->label('First Name');
        ?>
        <?=
        $form->field($model, 'lastname', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper label-required'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 only-alphabet disable-copy-paste',
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'placeholder' => 'Lastname'
        ])->label('Last Name');
        ?>
        <?=
        $form->field($model, 'email', [
            'options' => ['class' => 'form-grider cop-form design1 col-fullwidth'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper label-required'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'placeholder' => 'Email'
        ])->label('Email');
        ?>
        <?=
        $form->field($model, 'password', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->passwordInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'placeholder' => 'Leave empty to not update'
        ])->label('Password');
        ?>
        <?=
        $form->field($model, 'verifypassword', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->passwordInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'placeholder' => 'Leave empty to not update'
        ])->label('Verify Password');
        ?>
        <!--<div class="form-grider cop-form design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-icon">
                        <span class="icon fas fa-file"></span>
                    </div>
                    <div class="head-wrapper__title-label fs14__medium">Upload</div>
                </div>
            </div>
            <div class="cop-form--container">
                <div class="cop-form__uploader design2 design2--auto">
                    <span class="cop-form__uploader--btn"><em class="fa fa-arrow-up"></em></span>
                    <a href="javascript:;" class="uploadMedia"><p class="cop-form__uploader--text"><span>Upload Profile Picture</span></p></a>
                    <button type="button" class="trash <?= !$hasMedia ? "hide" : "js-deleteUserProfileMedia" ?>"><em class="fa fa-trash-alt"></em></button>
                    <div class="cop-form__uploader--placeholder <?= !$hasMedia ? "hide" : "" ?>">
                        <img class="rounded-circle js-userProfile" src="<?= $hasMedia ? $media['cdn_path'] : "" ?>">
                    </div>
                </div>
            </div>
        </div>-->
    </div>
    <div class="adm-u-flexed adm-u-justify-start cmt-20">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary adm-u-pad10_30 theme-button mb-3', 'name' => 'user-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>