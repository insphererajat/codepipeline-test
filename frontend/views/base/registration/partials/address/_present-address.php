<?php
$districtDropDownList = [];
$tehsilDropDownList = [];
if (isset($model->present_address_state_code) && !empty($model->present_address_state_code)) {
    $districtDropDownList = common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => $model->present_address_state_code]);
}
if (isset($model->present_address_district_code) && !empty($model->present_address_district_code)) {
    $tehsilDropDownList = common\models\location\MstTehsil::getTehsilDropdown(['districtCode' => $model->present_address_district_code]);
}
?>
<div class="col-md-12">
    <div class="c-sectionHeader c-sectionHeader-xs design1 cmb-10">
        <div class="c-sectionHeader__container">
            <div class="c-sectionHeader__label">
                <div class="c-sectionHeader__label__title fs16__medium">Permanent Address</div>
            </div>
        </div>
    </div>
    <div class="c-form c-form-xs col4  u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title'>
                    <div class='head-wrapper__title-label fs14__medium'>Flat / Room / Door / Block / House No. </div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_house_no', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('house_no'),
                        'placeholder' => $model::instance()->getAttributeLabel('house_no'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title'>
                    <div class='head-wrapper__title-label fs14__medium'>Name of Premises / Building</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_premises_name', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('premises_name'),
                        'placeholder' => $model::instance()->getAttributeLabel('premises_name'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title'>
                    <div class='head-wrapper__title-label fs14__medium'>Road / Street / Lane / Post Office</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_street', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('street'),
                        'placeholder' => $model::instance()->getAttributeLabel('street'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title'>
                    <div class='head-wrapper__title-label fs14__medium'>Area / Locality</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_area', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('area'),
                        'placeholder' => $model::instance()->getAttributeLabel('area'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title'>
                    <div class='head-wrapper__title-label fs14__medium'>Landmark</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_landmark', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('area'),
                        'placeholder' => $model::instance()->getAttributeLabel('area'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title label-required'>
                    <div class='head-wrapper__title-label fs14__medium'>State/UT</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_state_code', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($stateDropDownList, ['class' => 'chzn-select-with-search presentAddressstate', 'prompt' => '', 'data-districtclass' => 'presentDistrict'])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title label-required'>
                    <div class='head-wrapper__title-label fs14__medium'>District</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_district_code', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search presentAddressdistrict', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title'>
                    <div class='head-wrapper__title-label fs14__medium'>Tehsil</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_tehsil_code', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($tehsilDropDownList, ['class' => 'chzn-select-with-search presentAddresstehsil js-tehsilCasecade', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1 js-tehsilSecion <?= ($model->present_address_tehsil_name != null && $model->present_address_tehsil_code == \common\models\location\MstTehsil::OTHER) ? '' : 'hide' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title'>
                <div class='head-wrapper__title-label fs14__medium'>Tehsil Name</div>
            </div>
        </div>
        <?=
                $form->field($model, 'present_address_tehsil_name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('present_address_tehsil_name'),
                    'placeholder' => $model::instance()->getAttributeLabel('present_address_tehsil_name'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-tehsilName only-alphabet disable-copy-paste text-to-upper'
                ])
                ->label(FALSE);
        ?>
    </div>
        
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title label-required'>
                    <div class='head-wrapper__title-label fs14__medium'>Village/City</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_village_name', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('village_name'),
                        'placeholder' => $model::instance()->getAttributeLabel('village_name'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title label-required'>
                    <div class='head-wrapper__title-label fs14__medium'>PIN</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'present_address_pincode', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('pincode'),
                        'placeholder' => $model::instance()->getAttributeLabel('pincode'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
    </div>
</div>