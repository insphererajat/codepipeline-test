<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/layouts/partials/flash-message') ?>
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
                                $form = ActiveForm::begin(['id' => 'resetPasswordTokenForm',
                                            'options' => [
                                                'class' => 'login__content-form',
                                            ],
                                ]);
                                ?>
                                <div class="form-grider design1">
                                    <div class="head-wrapper">
                                        <div class="head-wrapper__title">
                                            <div class="head-wrapper__title-label pf-medium fs14">Username/Email</div>
                                        </div>
                                    </div>

                                    <?=
                                    $form->field($model, 'username', [
                                        'template' => '<div class="cop-form--container withIcon-lt"><span class="icon fa fa-envelope"></span>{input}{hint}{error}</div>'
                                    ])->textInput([
                                        'autocomplete' => 'off',
                                        'autofocus' => true,
                                        'maxlength' => true,
                                        'class' => 'cop-form--container-field u-fieldHeight48 disable-copy-paste',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'yourname@mail.com'
                                    ])->label(FALSE);
                                    ?>
                                
                                <div class="buttons-multiple buttons-multiple-sm cmt-20">
                                    <?= Html::submitButton('Submit', ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary orange button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
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