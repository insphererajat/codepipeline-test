<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = isset($title) ? $title : '';
$this->params['breadcrumbs'][] = $this->title;
$stateDropDownList = $districtDropDownList = [];
$stateDropDownList = common\models\location\MstState::getStateDropdown();
if (isset($model->birth_state_code) && !empty($model->birth_state_code)) {
    $districtDropDownList = common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => $model->birth_state_code]);
}
$this->registerJs("LocationController.getDistrict();");
$this->registerJs("LogApplicantController.createUpdate();");
$action = Yii::$app->controller->action->id;
$formId = 'recover-email';
switch ($action) {
    case 'change-email':
        $formId = 'log-applicant-form';
        break;
    case 'change-mobile':
        $formId = 'log-applicant-form';
        break;
}
?>
<div class="login__container login__container-xs" id="collapseOne" data-time="<?= common\models\LogOtp::VALIDATION_TIME?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Begin login main wrapper section -->
                <div class="login__wrap">
                    <!-- Begin login left side content section -->
                    <div class="login__wrap__information">
                        <div class="login__wrap__information__header">
                            <div class="login__wrap__information__content">
                                <div class="login__wrap__information__content-title"><?= $this->title ?></div>
                                <div class="login__wrap__information__content-description">Welcome to the <?= \Yii::$app->params['appName'] ?> Portal</div>
                                <div class="login__wrap__information__content-media">
                                    <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda.png" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End login left side content section -->

                    <!-- Begin login right side form section -->
                    <div class="login__wrap__form">
                        <div class="login__wrap__container">
                            <div class="c-form u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
                                <?= $this->render('/layouts/partials/flash-message') ?>
                                <?php
                                $form = ActiveForm::begin([
                                            'id' => $formId,
                                            'options' => [
                                                'name' => 'log-applicant-form',
                                                'class' => 'login__content-form',
                                            ],
                                ]);
                                ?>
                                <div class="form-grider design1">
                                    <?=
                                            $form->field($model, 'name', [
                                                'template' => "<div class='head-wrapper'>
                        <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Candidate Name</div>
                        </div>
                         <div class='head-wrapper__options'>
                         <span class='far fa-question-circle icon' data-toggle='tooltip' title='Candidate Name'></span>
                        </div>
                        </div><div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                            ->textInput([
                                                'autocomplete' => 'off',
                                                'autofocus' => true,
                                                'maxlength' => true,
                                                'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-alphabet disable-copy-paste'
                                            ])
                                            ->label(FALSE);
                                    ?>
                                </div>
                                <div class="form-grider design1">
                                    <?=
                                            $form->field($model, 'mother_name', [
                                                'template' => "<div class='head-wrapper'>
                        <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Mother`s</div>
                        </div>
                         <div class='head-wrapper__options'>
                         <span class='far fa-question-circle icon' data-toggle='tooltip' title='Mother`s'></span>
                        </div>
                        </div><div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                            ->textInput([
                                                'autocomplete' => 'off',
                                                'autofocus' => true,
                                                'maxlength' => true,
                                                'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-alphabet disable-copy-paste'
                                            ])
                                            ->label(FALSE);
                                    ?>
                                </div>
                                
                                <div class="form-grider design1">
                                    <div class='head-wrapper'>
                                        <div class='head-wrapper__title label-required'>
                                            <div class='head-wrapper__title-label fs14__medium'>Birth State/UT</div>
                                        </div>
                                    </div>
                                    <?=
                                            $form->field($model, 'birth_state_code', [
                                                'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                            ->dropDownList($stateDropDownList, ['class' => 'chzn-select-with-search state', 'prompt' => ''])
                                            ->label(FALSE);
                                    ?>
                                </div>

                                <div class="form-grider design1">
                                    <div class='head-wrapper'>
                                        <div class='head-wrapper__title label-required'>
                                            <div class='head-wrapper__title-label fs14__medium'>Birth District</div>
                                        </div>
                                    </div>
                                    <?=
                                            $form->field($model, 'birth_district_code', [
                                                'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                            ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search district', 'prompt' => ''])
                                            ->label(FALSE);
                                    ?>
                                </div>
                                <div class="form-grider design1">
                                    <div class='head-wrapper'>
                                        <div class='head-wrapper__title label-required'>
                                            <div class='head-wrapper__title-label fs14__medium'>Date of Birth</div>
                                        </div>
                                        <div class='head-wrapper__options'>
                                            <span class='far fa-question-circle icon' data-toggle='tooltip' title="<?= Yii::t('app', 'age.limit') ?>"></span>
                                        </div>
                                    </div>
                                    <?=
                                            $form->field($model, 'date_of_birth', [
                                                'template' => "<div class='cop-form--container calender-field'>\n{input}\n{hint}\n{error}</div>",
                                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                            ->textInput([
                                                'autocomplete' => 'off',
                                                'maxlength' => true,
                                                'placeholder' => 'Date of Birth',
                                                'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-birthDate disable-copy-paste nothing-press'
                                            ])
                                            ->label(FALSE);
                                    ?>

                                </div>
                                <div class="form-grider design1">
                                    <?=
                                    $form->field($model, 'reCaptcha')->widget(
                                            \himiklab\yii2\recaptcha\ReCaptcha::className(), ['siteKey' => \Yii::$app->params['reCAPTCHA.siteKey']]
                                    )->label(false)
                                    ?>
                                </div>
                                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                                    <?= Html::submitButton('Submit', ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
                                </div>
                                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                                    <?= Html::a('Back to login', yii\helpers\Url::toRoute(['login']), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary red button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- End login right side form section -->
                    </div>
                    <!-- End login main wrapper section -->
                </div>
            </div>
        </div>
    </div>
</div>