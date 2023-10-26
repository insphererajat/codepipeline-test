<?php

use common\models\MstListType;
use yii\helpers\Url;
use components\Helper;

$stateDropDownList = common\models\location\MstState::getStateDropdown(['code' => common\models\ApplicantDetail::DOMICILE_ISSUE_STATE]);
$districtDropDownList = common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => common\models\ApplicantDetail::DOMICILE_ISSUE_STATE]);

$categoryList = MstListType::getListTypeDropdownByParentId(MstListType::SOCIAL_CATEGORY);
$disabilityList = MstListType::getListTypeDropdownByParentId(MstListType::DISABILITY);
$authorityList = MstListType::getListTypeDropdownByParentId(MstListType::ISSUING_AUTHORITY);
$exServiceList = MstListType::selectTypeList();
$dffList = MstListType::selectTypeList();
if ($model->is_domiciled == \frontend\models\RegistrationForm::SELECT_TYPE_NO) {
    $categoryList = MstListType::getListTypeDropdownByParentId(MstListType::SOCIAL_CATEGORY, ['id' => MstListType::UNRESERVED_GENERAL]);
    $disabilityList = MstListType::getListTypeDropdownByParentId(MstListType::DISABILITY, ['id' => MstListType::NOT_APPLICABLE]);
    $exServiceList = [common\models\caching\ModelCache::IS_ACTIVE_NO => 'No'];
    $dffList = [common\models\caching\ModelCache::IS_ACTIVE_NO => 'No'];
}
$this->registerJs("RegistrationV2Controller.categoryDetail();");
$this->registerJs("RegistrationV2Controller.isDomicile();");
$this->registerJs("RegistrationV2Controller.showHideInputs('disciplinary');");
$this->registerJs("RegistrationV2Controller.showHideInputs('voluntary');");
$this->registerJs("RegistrationV2Controller.showHideInputs('soliderwelfare');");
$this->registerJs("RegistrationV2Controller.showHideInputs('isDependentFreedomFighter');");
?>
<div class="c-sectionHeader c-sectionHeader-xs design3 socialCategory">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Category Reservation Details</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Category</div>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_id', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($categoryList, ['class' => 'chzn-select js-socialCategory'])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-categoryOBC-yes <?= ($model->social_category_id == MstListType::OBC) ? '' : 'hide' ?>">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Do you belong Non-creamy Layer?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_non_creamy_layer', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-categoryOBC-yes <?= ($model->social_category_id == MstListType::OBC) ? '' : 'hide' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Valid Upto</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Date should be greater or equal to today'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_certificate_valid_upto_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 js-category-yes <?= (in_array($model->social_category_id, [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) ? '' : 'hide' ?>">
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title'>
                <div class='head-wrapper__title-label fs14__medium'>Certificate Number</div>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_certificate_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'placeholder' => 'Certificate No',
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 hide">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Certificate Issuing State</div>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_certificate_state_code', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($stateDropDownList, ['class' => 'chzn-select'])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Certificate Issuing District</div>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_certificate_district_code', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                ->label(FALSE);
        ?>

    </div>

    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Certificate Issuing Authority</div>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_certificate_issue_authority_id', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($authorityList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Certificate Issuing Date</div>
            </div>
        </div>
        <?=
                $form->field($model, 'social_category_certificate_issue_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'placeholder' => 'Certificate Issuing Date',
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datepicker-max-yesterday disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-sectionHeader c-sectionHeader-xs design3 socialCategory">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Subcategory Reservation Details</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Are you Specially Abled Person(PH/Divyang)?</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Are you Specially Abled Person(PH/Divyang)?'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'disability_id', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($disabilityList, ['class' => 'chzn-select-with-search js-disabilityId'])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-disability-yes <?= (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) ? '' : 'hide' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Percentage Of Handicap</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Percentage Of Handicap'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'disability_percentage', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('disability_percentage'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-disability-yes <?= (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) ? '' : 'hide' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title'>
                <div class='head-wrapper__title-label fs14__medium'>PH Certificate No :</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='PH Certificate No'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'disability_certificate_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('disability_certificate_no'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1 js-disability-yes <?= (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) ? '' : 'hide' ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>PH Certificate Issuing Date</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Domicile (Sthai Niwas Praman Patra) Issuing Date'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'disability_certificate_issue_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datepicker-max-yesterday disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>
<!--<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 exserviceman exservicemanyes js-exServiceChildSection">
    <div class="form-grider design1 exserviceman exservicemanyes hide">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Have You Been Dissmissed On Disciplinary Grounds From Defence Services?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_dismissed_from_defence', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select disciplinaryselectAttr', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1 disciplinary disciplinaryno hide">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Did You Seek Voluntary Retirement?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_voluntary_retirement', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select voluntaryselectAttr', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1 voluntary voluntaryyes hide">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Have You Been Relieved On Medical Grounds From Defence Services?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_relieved_on_medical', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1 voluntary voluntaryyes voluntaryno hide">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Discharge Certificate No</div>
            </div>
        </div>
        <?=
                $form->field($model, 'discharge_certificate_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('discharge_certificate_no'),
                    'placeholder' => $model::instance()->getAttributeLabel('discharge_certificate_no'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1 voluntary voluntaryyes voluntaryno hide">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Discharge Certificate date</div>
            </div>
        </div>
        <?=
                $form->field($model, 'discharge_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('discharge_date'),
                    'placeholder' => $model::instance()->getAttributeLabel('discharge_date'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Is your name registered in any District Soldier Welfare and Rehabilitation Office Employment Exchange located in Uttarakhand State or are you in last year of your service?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_dswro_registered', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select soliderwelfareselectAttr', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 soliderwelfare soliderwelfareyes hide">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Enter Registration No or Army Order Number with AO 78/79</div>
            </div>
        </div>
        <?=
                $form->field($model, 'dswro_registration_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('dswro_registration_no'),
                    'placeholder' => $model::instance()->getAttributeLabel('dswro_registration_no'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Office Name</div>
            </div>
        </div>
        <?=
                $form->field($model, 'dswro_office_name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('dswro_office_name'),
                    'placeholder' => $model::instance()->getAttributeLabel('dswro_office_name'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-alphabet disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Date Of Registration /NOC dated on</div>
            </div>
        </div>
        <?=
                $form->field($model, 'dswro_registration_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('dswro_registration_date'),
                    'placeholder' => $model::instance()->getAttributeLabel('dswro_registration_date'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">In last year Service</div>
            </div>
        </div>
        <?=
                $form->field($model, 'dswro_registration_upto_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('dswro_registration_upto_date'),
                    'placeholder' => $model::instance()->getAttributeLabel('dswro_registration_upto_date'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-dswroUptoDate disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>-->
<!--<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Are You Dependent Of Freedom Fighter?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_dependent_freedom_fighter', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select isDependentFreedomFighterselectAttr', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 isDependentFreedomFighter isDependentFreedomFighteryes hide">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Name Of Freedom Fighter</div>
            </div>
        </div>
        <?=
                $form->field($model, 'freedom_fighter_name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('freedom_fighter_name'),
                    'placeholder' => $model::instance()->getAttributeLabel('freedom_fighter_name'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-alphabet disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Relation To Freedom Fighter</div>
            </div>
        </div>
        <?=
                $form->field($model, 'freedom_fighter_relation', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('freedom_fighter_relation'),
                    'placeholder' => $model::instance()->getAttributeLabel('freedom_fighter_relation'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-alphabet disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Certificate No</div>
            </div>
        </div>
        <?=
                $form->field($model, 'freedom_fighter_certificate_no', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('freedom_fighter_certificate_no'),
                    'placeholder' => $model::instance()->getAttributeLabel('freedom_fighter_certificate_no'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48alpha-numeric-with-special'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Date Of Issuing</div>
            </div>
        </div>
        <?=
                $form->field($model, 'freedom_fighter_issue_date', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('freedom_fighter_issue_date'),
                    'placeholder' => $model::instance()->getAttributeLabel('freedom_fighter_issue_date'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>-->