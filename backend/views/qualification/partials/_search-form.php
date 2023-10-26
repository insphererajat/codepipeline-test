<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['MstQualificationSearch']) && !empty($searchParams['MstQualificationSearch'])) ? "" : "hide";

$parent = common\models\MstQualification::getQualificationDropdown(['recordIsNull' => ['column' => 'mst_qualification.parent_id']]);
?>
<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'qualificationSearchForm',
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col2">
        <?=
                $form->field($model, 'parent_id', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($parent, ['class' => 'chzn-select', 'prompt' => 'Select Parent']
                )->label(FALSE)
        ?>
        <?=
        $form->field($model, 'name', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'Name',
        ])->label(false);
        ?>
    </div>
    <div class="filters-wrapper__action">
        <div class="adm-c-button cml-10">
            <?= Html::submitButton('Search', ['class' => 'btn theme-button adm-u-pad15_22 text-light mb-1', 'name' => 'button']) ?>
            <a href="<?= Url::toRoute(['/qualification/index']) ?>"  class = 'btn btn-dark  adm-u-pad15_22 mb-1'>Reset</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>