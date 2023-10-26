<?php

use common\models\MstListType;

$employmentNatureList = common\models\MstListType::getListTypeDropdownByParentId(\common\models\MstListType::EMPLOYMENT_NATURE);
$employmentTypeList = common\models\MstListType::getListTypeDropdownByParentId(\common\models\MstListType::EMPLOYMENT_TYPE);
$employerTypeList = common\models\MstListType::getListTypeDropdownByParentId(\common\models\MstListType::EMPLOYER_TYPE);
$experienceTypeList = common\models\MstListType::getListTypeDropdownByParentId(\common\models\MstListType::EXPERIENCE_TYPE);
?>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Work Experience Details</div>
        </div>
    </div>
</div>
<div class="col-12 p-0">
    <div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Are you employed?</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'is_employed', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select employedselectAttr', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
    </div>
</div>
<div class="col-12 p-0 employed employedyes">
    <div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Employment (Present/Past)</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'employment_type_id', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($employmentTypeList, ['class' => 'chzn-select-with-search employmentTypeselectAttr', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Experience Type</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'experience_type_id', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($experienceTypeList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Employer</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'employer', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('employer'),
                        'placeholder' => $model::instance()->getAttributeLabel('employer'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Nature of Employment?</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'employment_nature_id', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($employmentNatureList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">From Date</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'start_date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('start_date'),
                        'placeholder' => $model::instance()->getAttributeLabel('start_date'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 emp_from__date disable-copy-paste nothing-press'
                    ])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1 employmentType employmentTypepast">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">To Date</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'end_date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('end_date'),
                        'placeholder' => $model::instance()->getAttributeLabel('end_date'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 emp_to__date disable-copy-paste nothing-press'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Name of Post</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'designation', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('designation'),
                        'placeholder' => $model::instance()->getAttributeLabel('designation'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Institution / Department / Organisation</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'office_name', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('office_name'),
                        'placeholder' => $model::instance()->getAttributeLabel('office_name'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
    </div>    
</div>
