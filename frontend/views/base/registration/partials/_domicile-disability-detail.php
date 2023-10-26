<?php

use common\models\MstListType;

$stateDropDownList = common\models\location\MstState::getStateDropdown(['code' => common\models\ApplicantDetail::DOMICILE_ISSUE_STATE]);
$districtDropDownList = common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => common\models\ApplicantDetail::DOMICILE_ISSUE_STATE]);

$this->registerJs("RegistrationV2Controller.disabilityDetails();");
?>
<div class="c-sectionHeader c-sectionHeader-xs design1">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Domicile & Disability Details</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'><?= Yii::t('app', 'is_domiciled') ?></div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='<?= Yii::t('app', 'is_domiciled') ?>'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'is_domiciled', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select js-isDomicile domicileselectAttr', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-DomicileOfUttarakhand-yes <?= ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'><?= Yii::t('app', 'domicile_no') ?></div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Domicile (Sthai Niwas Praman Patra) Certificate No'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'domicile_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('domicile_no'),
                    'placeholder' => 'Domicile (Sthai Niwas Praman Patra) Certificate No',
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 hide">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'><?= Yii::t('app', 'domicile_issue_state') ?></div>
            </div>
        </div>
        <?=
                $form->field($model, 'domicile_issue_state', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($stateDropDownList, ['class' => 'chzn-select'])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-DomicileOfUttarakhand-yes <?= ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'><?= Yii::t('app', 'domicile_issue_district') ?></div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Domicile (Sthai Niwas Praman Patra) Issuing District'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'domicile_issue_district', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-DomicileOfUttarakhand-yes <?= ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title'>
                <div class='head-wrapper__title-label fs14__medium'><?= Yii::t('app', 'domicile_issue_date') ?></div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Domicile (Sthai Niwas Praman Patra) Issuing Date'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'domicile_issue_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-domicile-issue-date-max disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>

</div>