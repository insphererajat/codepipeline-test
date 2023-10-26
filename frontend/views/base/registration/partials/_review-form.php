<?php

use yii\helpers\Html;
use common\models\location\MstDistrict;
use yii\helpers\ArrayHelper;
use frontend\models\RegistrationForm;
use common\models\ApplicantDetail;

echo \yii\bootstrap\Html::hiddenInput('classifed_id', $classifiedId);
?>
<div class="f-c__review-section">
    <div class="f-c__review-section--title"><span class="text">Date/Place/Exam Centre Preferences</span></div>
    <div class="c-form c-form-xs col2 u-fieldRadius8 u-fieldShadow u-fieldBorderClr1">
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title label-required'>
                    <div class='head-wrapper__title-label fs14__medium'>Today's Date</div>
                </div>
                <div class='head-wrapper__options'>
                    <span class='far fa-question-circle icon' data-toggle='tooltip' title='Today\'s Date'></span>
                </div>
            </div>
            <?=
                    $form->field($model, 'date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('date'),
                        'readonly' => true,
                        'disabled' => true,
                        'value' => date('d-m-Y'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxRequired disable-copy-paste text-to-upper'
                    ])
                    ->label(false);
            ?>
        </div>
        <div class="form-grider design1">
            <div class='head-wrapper'>
                <div class='head-wrapper__title label-required'>
                    <div class='head-wrapper__title-label fs14__medium'>Place</div>
                </div>
                <div class='head-wrapper__options'>
                    <span class='far fa-question-circle icon' data-toggle='tooltip' title='Place'></span>
                </div>
            </div>
            <?=
                    $form->field($model, 'place', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'autofocus' => true,
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('place'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxRequired js-textboxName only-alphabet disable-copy-paste text-to-upper'
                    ])
                    ->label(false);
            ?>
        </div>
    </div>
    <?php if (false && ArrayHelper::isIn($classifiedId, [RegistrationForm::SCENARIO_4])): 
        $districtDropDownList = MstDistrict::getDistrictDropdown([
            'stateCode' => ApplicantDetail::DOMICILE_ISSUE_STATE
        ]);
        unset($districtDropDownList['910203']);
    ?>
        <div class="c-form c-form-xs col2 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
            <div class="form-grider design1">
                <div class='head-wrapper'>
                    <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Exam Centre Preference 1</div>
                    </div>
                    <div class='head-wrapper__options'>
                        <span class='far fa-question-circle icon' data-toggle='tooltip' title='Exam Centre Preference 1'></span>
                    </div>
                </div>
                <?=
                        $form->field($model, 'preference1', [
                            'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                            'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                        ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                        ->label(FALSE);
                ?>
            </div>
            <div class="form-grider design1">
                <div class='head-wrapper'>
                    <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Exam Centre Preference 2</div>
                    </div>
                    <div class='head-wrapper__options'>
                        <span class='far fa-question-circle icon' data-toggle='tooltip' title='Exam Centre Preference 2'></span>
                    </div>
                </div>
                <?=
                        $form->field($model, 'preference2', [
                            'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                            'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                        ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                        ->label(FALSE);
                ?>
            </div>
            <div class="form-grider design1">
                <div class='head-wrapper'>
                    <div class='head-wrapper__title label-required'>
                        <div class='head-wrapper__title-label fs14__medium'>Exam Centre Preference 3</div>
                    </div>
                    <div class='head-wrapper__options'>
                        <span class='far fa-question-circle icon' data-toggle='tooltip' title='Exam Centre Preference 2'></span>
                    </div>
                </div>
                <?=
                        $form->field($model, 'preference3', [
                            'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                            'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                        ->dropDownList($districtDropDownList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                        ->label(FALSE);
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>


