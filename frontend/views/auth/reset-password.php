<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("AuthController.authenticate()");
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
                                <div class="login__wrap__information__content-title">Reset Password</div>
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
                                <?= $this->render('/layouts/partials/flash-message.php') ?>
                                <?php
                                $form = ActiveForm::begin(['id' => 'reset-password-form',
                                            'options' => [
                                                'class' => 'login__content-form',
                                            ],
                                ]);
                                ?>
                                <div class="form-grider design1">
                                    <div class="head-wrapper">
                                        <div class="head-wrapper__title label-required">
                                            <div class="head-wrapper__title-label pf-medium fs14">OTP</div>
                                        </div>
                                    </div>

                                    <?=
                                    $form->field($model, 'otp', [
                                        'template' => "<div class='cop-form--container withIcon-rt'>{input}{hint}{error}</div>"
                                    ])->passwordInput([
                                        'class' => 'cop-form--container-field u-fieldHeight48 only-number',
                                        'autocomplete' => 'off',
                                        'maxlength' => true,
                                        'autofocus' => true,
                                        'placeholder' => 'OTP'
                                    ])->label(false);
                                    ?>
                                </div>
                                <div class="form-grider design1">
                                    <div class="head-wrapper">
                                        <div class="head-wrapper__title label-required">
                                            <div class="head-wrapper__title-label pf-medium fs14">Password</div>
                                        </div>
                                    </div>

                                    <?=
                                    $form->field($model, 'password', [
                                        'template' => "<div class='cop-form--container withIcon-rt'>{input}{hint}{error}</div>"
                                    ])->passwordInput([
                                        'class' => 'cop-form--container-field u-fieldHeight48',
                                        'autocomplete' => 'off',
                                        'maxlength' => true,
                                        'autofocus' => true,
                                        'placeholder' => 'Password'
                                    ])->label(false);
                                    ?>
                                </div>
                                <div class="form-grider design1">
                                    <div class="head-wrapper">
                                        <div class="head-wrapper__title label-required">
                                            <div class="head-wrapper__title-label pf-medium fs14">Verify Password</div>
                                        </div>
                                    </div>
                                    <?=
                                    $form->field($model, 'verifypassword', [
                                        'template' => "<div class='cop-form--container withIcon-rt'>{input}{hint}{error}</div>"
                                    ])->passwordInput([
                                        'class' => 'cop-form--container-field u-fieldHeight48',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Password'
                                    ])->label(false);
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
                                    <?= Html::submitButton('Save', ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
                                </div>
                                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                                    <?= Html::a('Back to login', yii\helpers\Url::toRoute(['login']), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary red button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                    <!-- End login right side form section -->
                </div>
                <!-- End login main wrapper section -->
            </div>
        </div>
    </div>
</div>