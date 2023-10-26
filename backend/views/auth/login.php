<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = 'Login';
$this->params['bodyClass'] = 'adm-c-login-page';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("AuthController.authenticate()");
?>

<!-- Begin Login container -->
<div class="adm-c-login__container adm-c-login__container-xs">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <!-- Begin adm-c-login main wrapper section -->
                <div class="adm-c-login__wrap">
                    <?= $this->render('_sidebar'); ?>
                    <div class="adm-c-login__wrap__form">
                        <div class="adm-c-login__wrap__container">
                            <?= \frontend\widgets\alert\AlertWidget::widget() ?>
                            <div class="adm-c-form adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2">
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'admin-login-form',
                                    'options' => [
                                        'class' => 'login__content-form',
                                        'autocomplete' => 'off'
                                    ],
                                ]);
                                ?>
                                <?=
                                $form->field($model, 'username', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container withIcon-lt'><span class='icon fa fa-user'></span>{input}</div>{hint}\n{error}"
                                ])->textInput([
                                    'autocomplete' => 'off',
                                    'autofocus' => true,
                                    'maxlength' => true,
                                    'class' => 'cop-form--container-field adm-u-fieldHeight48 disable-copy-paste',
                                    'autocomplete' => 'off',
                                    'placeholder' => 'Userame or Email'
                                ])->label('Username');
                                ?>

                                <?=
                                $form->field($model, 'password', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container withIcon-lt'><span class='icon fa fa-lock'></span>{input}</div>{hint}\n{error}"
                                ])->passwordInput([
                                    'autocomplete' => 'off',
                                    'autofocus' => true,
                                    'maxlength' => true,
                                    'class' => 'cop-form--container-field adm-u-fieldHeight48',
                                    'autocomplete' => 'off',
                                    'placeholder' => 'Password'
                                ])->label('Password');
                                ?>
                                <div class="form-grider design1">
                                    <?=
                                    $form->field($model, 'reCaptcha')->widget(
                                            \himiklab\yii2\recaptcha\ReCaptcha::className(), ['siteKey' => \Yii::$app->params['reCAPTCHA.siteKey']]
                                    )->label(false)
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <?=
                                            $form->field($model, 'rememberMe', [
                                                'options' => ['tag' => 'div', 'class' => 'adm-c-formSelectors design1'],
                                                'template' => "{input}\n<label>Remember Me</label>\n{hint}\n{error}"
                                            ])->checkbox(['id' => 'RememberMe', 'hidefocus' => true], false)
                                        ?>
                                    </div>

                                    <!--<div class="col-md-6 col-sm-6 col-12 text-right">
                                        <a href="<?= Url::toRoute(['/admin/auth/forgot-password']) ?>" id="forgot-password" class="adm-u-link grey pf-regular fs14">Forgot your password?</a>
                                    </div>-->
                                </div>
                                <div class="buttons-multiple  cmt-20">
                                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary adm-u-pad10_30 theme-button mb-3', 'name' => 'login-button']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $('#captcha-image').yiiCaptcha('refresh');
    $('#refresh-captcha').on('click', function(e){
        e.preventDefault();
        $('#loginform-captcha').val('');
        $('#captcha-image').yiiCaptcha('refresh');
    });
JS;
$this->registerJs($js, $this::POS_READY);

$this->registerJs("CaptchaController.summary();");
?>