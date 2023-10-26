<?php

use common\models\MstState;
use common\models\Network;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['MstListTypeSearch']) && !empty($searchParams['MstListTypeSearch'])) ? "" : "hide";
?>

<div class="filters-wrapper  adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'listTypeSearchForm',
                'method' => 'GET',
                'action' => Url::toRoute(['/admin/list-type/index']),
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
                    $form->field($model, 'parent_id', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList(\common\models\MstListType::getListTypeDropdownBy(['recordIsNull' => ['column' => 'parent_id']]), ['class' => 'chzn-select', 'prompt' => 'Select Parent']
                    )->label(FALSE)
                ?>
            </div>
        </div>
        <div class="form-grider design1">
            <div class="cop-form--container">
                <?=
                $form->field($model, 'name', [
                    'labelOptions' => ['class' => 'sr-only']
                ])->textInput([
                    'autofocus' => true,
                    'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48',
                    'placeholder' => 'Name'
                ])->label(true)
                ?>
            </div>
        </div>
        <div class="filters-wrapper__action">
            <div class="adm-c-button cml-10">
                <?= Html::submitButton('Search', ['class' => 'btn theme-button adm-u-pad15_22 text-light mb-1', 'name' => 'button']) ?>
                <a href="<?= Url::toRoute(['/admin/list-type/index']) ?>"  class = 'btn btn-dark  adm-u-pad15_22 mb-1'>Reset</a>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>