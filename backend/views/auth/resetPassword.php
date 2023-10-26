<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login__container login__container-xs">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <!-- Begin login main wrapper section -->
                <div class="login__wrap">
                    <!-- Begin login left side content section -->
                    <div class="login__wrap__information">
                        <div class="login__wrap__information__header">
                            <a href="javascript:;" class="logo">
                                <img src="<?= Yii::$app->params['staticHttpPath']?>/images/logos/hpslda.png" class="img-fluid" />
                            </a>
                            <div class="login__wrap__information__content">
                                <div class="login__wrap__information__content-title">Welcome to the Govt Portal</div>
                                <div class="login__wrap__information__content-description">Login with us for access the world class
                                    platform</div>
                                <div class="login__wrap__information__content-media">
                                    <img src="<?= Yii::$app->params['staticHttpPath']?>/images/icons/icon1.png" class="img-fluid" />
                                </div>
                            </div>
                        </div>

                        <div class="login__wrap__information__footer">
                            <div class="login__wrap__information__footer__text">We are working with some highly reputed brands</div>
                            <div class="login__wrap__information__footer__logos">
                                <a href="javascript:;" class="logo">
                                    <img src="<?= Yii::$app->params['staticHttpPath']?>/images/logos/hpslda.png" class="img-fluid" />
                                </a>
                                <a href="javascript:;" class="logo">
                                    <img src="<?= Yii::$app->params['staticHttpPath']?>/images/logos/hpslda.png" class="img-fluid" />
                                </a>
                                <a href="javascript:;" class="logo">
                                    <img src="<?= Yii::$app->params['staticHttpPath']?>/images/logos/hpslda.png" class="img-fluid" />
                                </a>
                                <a href="javascript:;" class="logo">
                                    <img src="<?= Yii::$app->params['staticHttpPath']?>/images/logos/hpslda.png" class="img-fluid" />
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End login left side content section -->

                    <!-- Begin login right side form section -->
                    <div class="login__wrap__form">
                        <div class="login__wrap__container">
                            <div class="c-form u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
                                <?php
                                $form = ActiveForm::begin(['id' => 'login-form',
                                            'options' => [
                                                'class' => 'login__content-form',
                                            ],
                                ]);
                                ?>
                                <div class="form-grider design1">
                                    <div class="head-wrapper">
                                        <div class="head-wrapper__title">
                                            <div class="head-wrapper__title-label pf-medium fs14">Password</div>
                                        </div>
                                    </div>

                                <?=
                                    $form->field($model, 'password', [
                                        'template' => "<div class='cop-form--container withIcon-rt'>{input}<span class='icon  u-cursor-pointer fa fa-eye'></span>{hint}{error}</div>"
                                    ])->passwordInput([
                                        'class' => 'cop-form--container-field u-fieldHeight48 disable-copy-paste',
                                        'autocomplete' => 'off',
                                        'autofocus' => true,
                                        'maxlength' => true,
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
                                        'template' => "<div class='cop-form--container withIcon-rt'>{input}<span class='icon  u-cursor-pointer fa fa-eye'></span>{hint}{error}</div>"
                                    ])->passwordInput([
                                        'class' => 'cop-form--container-field u-fieldHeight48 disable-copy-paste',
                                        'autocomplete' => 'off',
                                        'autofocus' => true,
                                        'maxlength' => true,
                                        'placeholder' => 'Password'
                                    ])->label(false);
                                    ?>
                                </div>
                                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                                    <?= Html::submitButton('Save', ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary orange button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
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