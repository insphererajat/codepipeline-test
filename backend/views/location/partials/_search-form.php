
<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['PageSearch']) && !empty($searchParams['PageSearch'])) ? "" : "hide";
$hide = (isset($model->id) && ($model->id) > 0 ) ? 'hide' : '';
$stateDropDownList = common\models\location\MstState::getStateDropdown();
?>

<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls?>">
    <?php
    $form = ActiveForm::begin([
        'id' => 'locationSearchForm',
        'method' => 'GET',
        'options' => [
            'class' => 'widget__wrapper-searchFilter',
            'autocomplete' => 'off'
        ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
    <div class="form-grider design1">
            <div class="cop-form--container">
            <?php if (isset($showState) && $showState): ?>
         <?=
                        $form->field($model, 'state_code', [
                            'options' => ['class' => 'form-grider cop-form design1'],
                            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                            'errorOptions' => ['class' => ' cop-form--help-block'],
                            'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                        ])
                        ->dropDownList($stateDropDownList, ['class' => 'chzn-select', 'prompt' => 'Select State']
                        )->label(FALSE)
                ?>
             
        <?php endif; ?>
                <?=
                    $form->field($model, 'search')->textInput([
                        'autofocus' => true,
                        'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
                        'placeholder' => 'Search'
                    ])->label(FALSE)
                ?>
            </div>
        </div>
        <div class="filters-wrapper__action">
            <div class="adm-c-button cml-10">
                <?= Html::submitButton('Search', ['class' => 'btn theme-button adm-u-pad15_22 text-light mb-1', 'name' => 'button']) ?>
                <a href="<?= $url ?>"  class = 'btn btn-dark  adm-u-pad15_22 mb-1'>Reset</a>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>