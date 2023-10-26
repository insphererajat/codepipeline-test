<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ApplicantDetail;
use common\models\ApplicantPost;

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['ApplicantSearch']) && !empty($searchParams['ApplicantSearch'])) ? "" : "hide";
$applicantStatus = ApplicantPost::getApplicationStatus();
unset($applicantStatus[ApplicantPost::APPLICATION_STATUS_REAPPLIED]);
?>
<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'ApplicantSearchForm',
                'action' => $url,
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <?= Html::hiddenInput('page', isset($searchParams['page']) ? $searchParams['page'] : 0) ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
        <?=
                $form->field($model, 'classified_id', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(common\models\MstClassified::getClassifiedDropdown(), ['class' => 'chzn-select', 'prompt' => 'Select Advertisement']
                )->label(FALSE)
        ?>
        <?=
        $form->field($model, 'search', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => $isPost ? 'Search by name, email, mobile, Application No' : 'Search by name, email, mobile, mother, father name'
        ])->label(FALSE);
        ?>
        <?php if (!$isPost): ?>
            <?=
                    $form->field($model, 'gender', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList(ApplicantDetail::getGenderDropdown(), ['class' => 'chzn-select', 'prompt' => 'Select Gender']
                    )->label(FALSE)
            ?>
        <?php endif; ?>
        <?php if ($isPost): ?>
            <?=
                    $form->field($model, 'application_status', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList($applicantStatus, ['class' => 'chzn-select', 'prompt' => 'Select Application Status']
                    )->label(FALSE)
            ?>
            <?=
                    $form->field($model, 'payment_status', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList(ApplicantPost::getPaymentStatus(), ['class' => 'chzn-select', 'prompt' => 'Select Payment Status']
                    )->label(FALSE)
            ?>
        <?php endif; ?>
        <?=
        $form->field($model, 'from_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 from__date nothing-press disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'From Date'
        ])->label(FALSE);
        ?>
        <?=
        $form->field($model, 'to_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 to__date nothing-press disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'To Date'
        ])->label(FALSE);
        ?>
        <?=
                $form->field($model, 'limit', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(['' => 'Default', 100 => '100', 200 => '200', 300 => '300'], ['class' => 'chzn-select', 'prompt' => 'Select Limit']
                )->label(FALSE)
        ?>
        </div>
        <div class="filters-wrapper__action">
            <div class="adm-c-button cml-10 cmt-10">
                <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
                <a href="<?= Url::toRoute($url) ?>" class="btn btn-rounded btn-secondary adm-u-pad8_20 mb-3">Reset</a>
                <?php if (Yii::$app->user->hasAdminRole()): ?>
                    <input type="submit" class="btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3" value="Export" formaction="/applicant/export-post">
                <?php endif; ?>
                <?php if (Yii::$app->user->hasAdminRole() || Yii::$app->user->hasClientAdminRole()): ?>
                    <input type="submit" class="btn btn-rounded adm-u-pad8_20 theme-button  btn-info mb-3" value="Print Applications" formaction="/applicant/export-preview">
                <?php endif; ?>
            </div> 
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
