<?php

use common\models\caching\ModelCache;
use common\models\MstListType;
use components\Helper;

$applicantDetail = \common\models\ApplicantDetail::findByApplicantPostId($model->applicantPostId, ['selectCols' => ['id', 'father_name', 'date_of_birth']]);
$dobRead = $fatherNameRead = FALSE;
if (isset($applicantDetail['date_of_birth']) && !empty($applicantDetail['date_of_birth']) && !ModelCache::testEmail(Yii::$app->applicant->identity->email)):
    $dobRead = TRUE;
endif;
if (isset($applicantDetail['father_name']) && !empty($applicantDetail['father_name'])):
    $fatherNameRead = TRUE;
endif;

$stateDropDownList = $districtDropDownList = [];
$stateDropDownList = common\models\location\MstState::getStateDropdown();
if (isset($model->birth_state_code) && !empty($model->birth_state_code)) {
    $districtDropDownList = common\models\location\MstDistrict::getDistrictDropdown(['stateCode' => $model->birth_state_code]);
}
$this->registerJs("RegistrationV2Controller.getDistrict('birth');");
$this->registerJs("RegistrationV2Controller.getTehsil('birth');");
$this->registerJs("RegistrationV2Controller.personalDetails();");
$this->registerJs("RegistrationV2Controller.tehsilCascade();");
$this->registerJs("RegistrationV2Controller.motherTongueCascade();");

$params = \Yii::$app->request->queryParams;
if (isset($params['guid']) && !empty($params['guid'])) {
    $ageCalculateDate = common\models\MstClassified::getReferenceDate($params['guid']);
}
if(empty($ageCalculateDate)) {
    $ageCalculateDate = common\models\MstClassified::AGE_CALCULATE_DATE;
}
$disabilityList = MstListType::getListTypeDropdownByParentId(MstListType::DISABILITY);
$birthCertificate = \common\models\ApplicantDetail::getBirthCertificate();
unset($birthCertificate[\common\models\ApplicantDetail::SCHOOL_LEAVING_CERTIFICATE]);
?>
<div class="c-sectionHeader c-sectionHeader-xs design1">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Additional Information</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col3 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="form-grider design1" id="dateRange" data-max="<?= date('d-m-Y', strtotime($ageCalculateDate.' -18 year')) ?>" data-min="<?= date('d-m-Y', strtotime($ageCalculateDate.' -58 year')) ?>">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Date of Birth</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title="<?= Yii::t('app', 'age.limit') ?>"></span>
            </div>
        </div>
        <?=
                $form->field($model, 'date_of_birth', [
                    'template' => "<div class='cop-form--container calender-field'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'placeholder' => 'Date of Birth',
                    'readonly' => $dobRead,
                    'disabled' => $dobRead,
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-birthDate disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
         
    </div>   
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Age <?= Yii::t('app', 'age.limit') ?></div>
                <div class="head-wrapper__title-subLabel fs13__regular"></div>
            </div>
            <div class="head-wrapper__options">
                <span class="far fa-question-circle icon" data-toggle="tooltip" title="<?= Yii::t('app', 'age.limit') ?>"></span>
            </div>
        </div>
        <div class="cop-form--container">
            <input type="text" class="cop-form--container-field fs14__regular u-fieldHeight48 js-age" disabled placeholder="<?= !empty($model->date_of_birth) ? Helper::displayAge($model->date_of_birth, $ageCalculateDate) : ''; ?>">
        </div>
    </div>
    
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Nationality</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Nationality'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'nationality', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(common\models\ApplicantDetail::getNationality(), ['class' => 'chzn-select'])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col3 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Full Name Of Father/Husband</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Father's Name'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'father_salutation', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(common\models\ApplicantDetail::getFatherSalutation(), ['class' => 'chzn-select'])
                ->label(FALSE);
        ?>
        <?=
                $form->field($model, 'father_name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('father_name'),
                    'readonly' => $fatherNameRead,
                    'disabled' => $fatherNameRead,
                    'placeholder' => 'Full Name Of Father/Husband',
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 text-to-upper only-alphabet disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Mother's Name</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Mother's Name'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'mother_salutation', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(common\models\ApplicantDetail::getMotherSalutation(), ['class' => 'chzn-select'])
                ->label(FALSE);
        ?>
        <?=
                $form->field($model, 'mother_name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'placeholder' => 'Mother Name',
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-alphabet disable-copy-paste text-to-upper'
                ])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Birth State/UT</div>
            </div>
        </div>
        <?=
                $form->field($model, 'birth_state_code', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($stateDropDownList, ['class' => 'chzn-select-with-search birthstate', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Proof of Birth Certificate</div>
            </div>
        </div>
        <?=
                $form->field($model, 'birth_certificate_type', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($birthCertificate, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col3 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Gender</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Gender'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'gender', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(['MALE' => 'MALE', 'FEMALE' => 'FEMALE', 'TRANSGENDER' => 'TRANSGENDER'], ['class' => 'chzn-select', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Marital Status</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Marrital Status'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'marital_status', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList(common\models\ApplicantDetail::getMaritalStatus(), ['class' => 'chzn-select', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>

</div>
<script type="text/javascript">
    var ageCalculateDate = '<?= $ageCalculateDate ?>';
</script>