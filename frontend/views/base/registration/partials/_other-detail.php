<?php

use common\models\MstListType;
$this->registerJs("RegistrationV2Controller.showHideInputs('exserviceman');");
$this->registerJs("RegistrationV2Controller.createUpdate();");
$this->registerJs("RegistrationV2Controller.showHideInputs('blacklist');");
$this->registerJs("RegistrationV2Controller.otherDetails();");
$exServiceList = MstListType::selectTypeList();
?>
<div class="c-sectionHeader c-sectionHeader-xs design3 socialCategory">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Other Reservation Details</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 js-DomicileOfUttarakhand-yes">

    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title">
                <div class="head-wrapper__title-label fs14__medium">Are You Ex-Army Person ?</div>
            </div>
        </div>
        <?=
                $form->field($model, 'is_exserviceman', [
                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->dropDownList($exServiceList, ['class' => 'chzn-select-with-search exservicemanselectAttr', 'prompt' => ''])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 exserviceman exservicemanyes js-exServiceChildSection">
    
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Certificate No</div>
            </div>
        </div>
        <?=
                $form->field($model, 'exserviceman_qualification_certificate', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('exserviceman_qualification_certificate'),
                    'placeholder' => $model::instance()->getAttributeLabel('exserviceman_qualification_certificate'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                ])
                ->label(FALSE);
        ?>
        <span class="badge badge-info"><?= Yii::t('app', 'exserviceman.notification.1') ?></span>&nbsp;<span class="badge badge-info"><?= Yii::t('app', 'exserviceman.notification.2') ?></span>
    </div>
</div>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Black List/ Declaration</div>
        </div>
    </div>
</div>
<div class="col-12 p-0">
    <div class="c-form c-form-xs col1 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Have you been debarred or blacklisted for appearing in any examination held by any university, other PSC or UPSC or any other examination body ?</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'is_debarred', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select blacklistselectAttr', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
    </div>
    <div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
        <div class="form-grider design1 blacklist blacklistyes hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">From Date</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'debarred_from_date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('debarred_from_date'),
                        'placeholder' => $model::instance()->getAttributeLabel('debarred_from_date'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1 blacklist blacklistyes hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">To Date</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'debarred_to_date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('debarred_to_date'),
                        'placeholder' => $model::instance()->getAttributeLabel('debarred_to_date'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                    ])
                    ->label(FALSE);
            ?>
        </div>
    </div>
</div>
<div class="u-flexed u-justify-btw mt-4"> 
    <?= yii\helpers\Html::submitButton('Save', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button js-saveAndContinueAA', 'id' => 'submitButton']) ?>
    <a href="<?= yii\helpers\Url::toRoute(components\Helper::stepsUrl('registration/address-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button">Next</a>

</div>