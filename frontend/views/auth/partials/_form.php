<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<?php if (!\components\Helper::checkCscConnect()): ?>
    <div class="buttons-multiple buttons-multiple-sm cmt-20">
        <?= yii\helpers\Html::a('Csc Connect Login', Url::toRoute('/payment/csc/connect'), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary blue button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
    </div>
<?php endif; ?>
<div class="buttons-multiple buttons-multiple-sm cmt-20">
    <?= yii\helpers\Html::a('Download Admit Card', \yii\helpers\Url::toRoute(['/auth/get-admit-card']), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular']) ?>
    <?= yii\helpers\Html::a('Candidate, Register Here', Url::toRoute(ArrayHelper::merge([0 => '/registration/basic-details'], \Yii::$app->request->queryParams)), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular']) ?>
</div>
<div class="text-center mt-3"><strong>Or</strong> Login</div>
<div class="form-grider design1">
    <?=
            $form->field($model, 'username', [
                'template' => "<div class='head-wrapper'>
                        <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Email Id</div>
                        </div>
                         <div class='head-wrapper__options'>
                         <span class='far fa-question-circle icon' data-toggle='tooltip' title='Email Id'></span>
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
            $form->field($model, 'password', [
                'template' => "<div class='head-wrapper'>
                        <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Password</div>
                        </div>
                         <div class='head-wrapper__options'>
                         <span class='far fa-question-circle icon' data-toggle='tooltip' title='Password'></span>
                        </div>
                        </div><div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
            ->passwordInput([
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

<div class="c-form c-form-xs col-12 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="form-grider design1">
        <?=
        $form->field($model, 'rememberMe', [
            'options' => ['tag' => 'div', 'class' => 'custom-checkbox pull-left'],
            'template' => "<label>\n{input}\n<span for='RememberMe'>Remember Me</span></label>\n{hint}\n{error}"
        ])->checkbox(['id' => 'RememberMe', 'hidefocus' => true], false)
        ?>
    </div>
    <div class="form-grider design1 text-right adm-u-flexed adm-u-justify-btw">
        <a class="adm-u-link grey" href="<?= \yii\helpers\Url::toRoute(['request-password-reset']) ?>">Forgot Password</a><br/>
        <a class="adm-u-link grey" href="<?= \yii\helpers\Url::toRoute(['recover-email']) ?>">Forgot Email Id</a>
    </div>
</div>

<div class="buttons-multiple buttons-multiple-sm cmt-20">
    <?= yii\helpers\Html::submitButton('Login', ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular', 'name' => 'login-button']) ?>
</div>
<!--<div class="buttons-multiple buttons-multiple-sm cmt-20">
<?= yii\helpers\Html::a('Result', 'javascript:;', ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary blue button-v2 fs14 pf-regular', 'data-toggle' => 'modal', 'data-target' => '#myModal']) ?>
</div>
<div class="buttons-multiple buttons-multiple-sm cmt-20">
<?= yii\helpers\Html::a('Change Email Id', \yii\helpers\Url::toRoute(['change-email']), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular']) ?>
<?= yii\helpers\Html::a('Change Mobile', \yii\helpers\Url::toRoute(['change-mobile']), ['class' => 'button-v2 blocked u-radius8  u-pad12_18 button-v2--primary green button-v2 fs14 pf-regular']) ?>
</div>-->