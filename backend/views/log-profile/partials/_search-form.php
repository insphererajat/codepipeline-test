<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\LogApplicant;

$hide = (isset($model->id) && ($model->id) > 0 ) ? 'hide' : '';

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['LogProfileSearch']) && !empty($searchParams['LogProfileSearch'])) ? "" : "hide";
?>
<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'LogProfileSearchForm',
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4">
        <?=
                $form->field($model, 'status', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(\common\models\LogProfile::statusDropdown(), ['class' => 'chzn-select', 'prompt' => 'Select Status']
                )->label(FALSE)
        ?>
        <?=
        $form->field($model, 'from_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 from__date',
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
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 to__date',
            'autocomplete' => 'off',
            'placeholder' => 'To Date'
        ])->label(FALSE);
        ?>
        <?=
        $form->field($model, 'old_value', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Old Value'
        ])->label(false);
        ?>
        <?=
        $form->field($model, 'new_value', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'New Value'
        ])->label(false);
        ?>
    </div>
    <div class="filters-wrapper__action">
        <div class="adm-c-button cml-10 cmt-10">
            <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
            <a href="<?= yii\helpers\Url::toRoute(['log-applicant/index']) ?>" class="btn btn-rounded btn-secondary adm-u-pad8_20 mb-3">Reset</a>
        </div> 
    </div>
    <?php ActiveForm::end(); ?>
</div>