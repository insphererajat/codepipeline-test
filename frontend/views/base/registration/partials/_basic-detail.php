<?php
$readonly = $nameReadOnly = FALSE;
if (!Yii::$app->applicant->isGuest):
    $readonly = TRUE;
    $nameReadOnly = TRUE;
endif;

$queryParams = \Yii::$app->request->queryParams;
/*if ($model->applicantPostFormStep == \common\models\Applicant::FORM_STEP_SUBMITTED) {
    $applicantPostCompoment = new \frontend\components\ApplicantPostComponent();
    $applicantPostCompoment->applicantId = Yii::$app->applicant->id;
    $applicantPostCompoment->checkApplicantPost($queryParams['guid']);
    $nameReadOnly = FALSE;
}*/
?>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Personal Information</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col3 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2" id="collapseOne" data-time="<?= common\models\LogOtp::VALIDATION_TIME?>">
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Candidate's Name</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title="Candidate's Name"></span>
            </div>
        </div>
        <?=
                $form->field($model, 'name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('name'),
                    'readonly' => $readonly,
                    'disabled' => $readonly,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxRequired js-textboxName only-alphabet disable-copy-paste text-to-upper'
                ])
                ->label(FALSE);
        ?>

    </div>
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Mobile No.</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Mobile No.'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'mobile', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => 10,
                    'data-label' => $model::instance()->getAttributeLabel('mobile'),
                    'readonly' => $readonly,
                    'disabled' => $readonly,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxMobile js-textboxRequired only-number disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Email Id</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Email Id'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'email', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('email'),
                    'readonly' => $readonly,
                    'disabled' => $readonly,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxEmail js-textboxRequired disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <?php if (!$readonly): ?>
        <div class="form-grider design1">
            <a class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green js-submitForm" id="generateOTP" data-toggle="modal" name="save">Generate OTP</a>
        </div>
    <?php endif; ?>
</div>

