<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['PageSearch']) && !empty($searchParams['PageSearch'])) ? "" : "hide";

?>

<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls?>">
    <?php
    $form = ActiveForm::begin([
        'id' => 'listTypeSearchForm',
        'method' => 'GET',
        'action' => Url::toRoute(['/list-type/index']),
        'options' => [
            'class' => 'widget__wrapper-searchFilter',
            'autocomplete' => 'off'
        ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
    <div class="form-grider design1">
            <div class="cop-form--container">
                <?=
                $form->field($model, 'name')->textInput([
                    'autocomplete' => 'off',
                    'autofocus' => true,
                    'maxlength' => true,
                    'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
                    'placeholder' => 'Search'
                ])->label(FALSE)
                ?>
            </div>
        </div>
        <div class="filters-wrapper__action">
            <div class="adm-c-button cml-10">
                <?= Html::submitButton('Search', ['class' => 'btn theme-button adm-u-pad15_22 text-light mb-1', 'name' => 'button']) ?>
                <a href="<?= Url::toRoute(['/list-type/index']) ?>"  class = 'btn btn-dark  adm-u-pad15_22 mb-1'>Reset</a>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>