<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login__container login__container-xs">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Begin login main wrapper section -->
                <div class="login__wrap">
                    <!-- Begin login left side content section -->
                    <div class="login__wrap__information">
                        <div class="login__wrap__information__header">
                            <div class="login__wrap__information__content">
                                <div class="login__wrap__information__content-title">Request for forgot password</div>
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
                                $form = ActiveForm::begin(['id' => 'resetPasswordTokenForm',
                                            'options' => [
                                                'class' => 'login__content-form',
                                            ],
                                ]);
                                ?>
                                <div class="form-grider design1">
                                    <?=
                                            $form->field($model, 'email', [
                                                'template' => "<div class='head-wrapper'>
                        <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Email</div>
                        </div>
                         <div class='head-wrapper__options'>
                         <span class='far fa-question-circle icon' data-toggle='tooltip' title='Email'></span>
                        </div>
                        </div><div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                            ->textInput([
                                                'autocomplete' => 'off',
                                                'autofocus' => true,
                                                'maxlength' => true,
                                                'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 disable-copy-paste'
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