<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Apply For Exam Centre';

$this->registerJs("AuthController.authenticate()");
?>
<div class="login__container login__container-xs">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <marquee style="color:red" behavior="alternate"><?= Yii::t('app', 'flash1'); ?></marquee>
                <!-- Begin login main wrapper section -->
                <div class="login__wrap">
                    <!-- Begin login left side content section -->
                    <div class="login__wrap__information">
                        <div class="login__wrap__information__header">
                            <div class="login__wrap__information__content">
                                <div class="login__wrap__information__content-title">Login</div>
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
                            <div class="c-form c-form-xs col1 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
                                <?= $this->render('/layouts/partials/flash-message.php') ?>
                                <?php
                                $form = ActiveForm::begin([
                                            'id' => 'admin-login-form',
                                            'options' => [
                                                'class' => 'widget__wrapper-searchFilter',
                                                'autocomplete' => 'off'
                                            ],
                                ]);
                                ?>
                                <?= $this->render('partials/_form.php', ['form' => $form, 'model' => $model, 'formCls' => '']) ?>
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
<?php //$this->registerJs("CaptchaController.summary();"); ?>