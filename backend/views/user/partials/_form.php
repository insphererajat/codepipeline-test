<?php

use common\models\Media;
use common\models\Network;
use common\models\Role;
use common\models\University;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$media = null;
$hasMedia = 0;
$requiredClass = ($model->id <= 0) ? "label-required" : "";
if ($model->profile_media_id > 0) {
    $media = Media::findById($model->profile_media_id);
    if (!empty($media)) {
        $hasMedia = 1;
    }
}


$roles = \common\models\Role::getRoleArray();

$this->registerJs("UserController.summary();");

?>
<div class="adm-basicBlock white adm-u-pad20_25">
    <?php
    $form = ActiveForm::begin([
                'id' => 'userForm',
                'options' => [
                    'autocomplete' => 'off',
                    'data-type' => 'normal',
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
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title  label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
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
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title  label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 only-alphabet disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Firstname'
        ])->label('First Name');
        ?>
        <?=
        $form->field($model, 'lastname', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title  label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 only-alphabet disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Lastname'
        ])->label('Last Name');
        ?>

        <?=
        $form->field($model, 'email', [
            'options' => ['class' => 'form-grider cop-form design1 col-fullwidth'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title  label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Email'
        ])->label('Email');
        ?>
        <?=
        $form->field($model, 'password', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title  $requiredClass'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->passwordInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Leave empty to update'
        ])->label('Password');
        ?>

        <?=
        $form->field($model, 'verifypassword', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title   $requiredClass'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->passwordInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Leave empty to update'
        ])->label('Verify Password');
        ?>
         <?=
                $form->field($model, "role_id", [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title  label-required'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(
                        $roles, ['class' => 'chzn-select']
                )->label('Select Role')
        ?>

        <?=
                $form->field($model, 'status', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(
                        ['1' => 'Active', '0' => 'Inactive'], ['class' => 'chzn-select']
                )->label('Status')
        ?>
    </div>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col2">
        <div class="form-grider cop-form design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title">
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
        </div>
    </div>
    <div class="adm-u-flexed adm-u-justify-start cmt-20">
        <?= Html::submitButton($model->id <= 0 ? 'Create' : 'Update', ['class' => 'btn btn-primary adm-u-pad10_30 theme-button mb-3', 'name' => 'user-button']) ?>
        <a href="<?= Url::toRoute(['/user/index']) ?>" class="btn  adm-u-pad10_30 ml-3 mb-3 btn-secondary ">Cancel</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>