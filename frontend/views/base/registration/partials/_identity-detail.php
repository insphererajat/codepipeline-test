<?php

use common\models\MstListType;
echo $form->field($model, 'is_aadhaar_card_holder')->hiddenInput()->label(false);
$identityVal = $model->identity_certificate_no;
$lastDigit = substr($model->identity_certificate_no, -4);
$identity = str_pad("", (strlen($model->identity_certificate_no) - 4), "x", STR_PAD_LEFT) . $lastDigit;
?>
<div class="c-sectionHeader c-sectionHeader-xs design1">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Identity Details</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col2 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Identity Type</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Identity Type'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'identity_type_id', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::getListTypeDropdownByParentId(MstListType::IDENTITY_TYPE), ['class' => 'chzn-select', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Identity Certificate No <?= !empty($identity) ? "($identity)" : '' ?></div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Identity Certificate No'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'identity_certificate_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->passwordInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('identity_certificate_no'),
                    'placeholder' => 'Identity Certificate No',
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste text-to-upper'
                ])
                ->label(FALSE);
        ?>
    </div>

</div>