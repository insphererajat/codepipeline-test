<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ApplicantDetail;
use common\models\ApplicantPost;

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['ApplicantSearch']) && !empty($searchParams['ApplicantSearch'])) ? "" : "hide";
?>
<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'ApplicantSearchForm',
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
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
            'placeholder' => 'Search by name, email, mobile'
        ])->label(FALSE);
        ?>
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
        </div>
        <div class="filters-wrapper__action">
            <div class="adm-c-button cml-10 cmt-10">
                <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
                <a href="<?= Url::toRoute($url) ?>" class="btn btn-rounded btn-secondary adm-u-pad8_20 mb-3">Reset</a>
            </div> 
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
