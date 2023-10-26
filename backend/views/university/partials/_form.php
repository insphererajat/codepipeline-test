<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = (isset($model->id) && $model->id > 0) ? 'Edit' : 'Create';
$stateDropDownList = common\models\location\MstState::getStateDropdown();

$parantUniversityType = common\models\MstUniversity::getUniversityDropdownByParentId();
?>

<div class="adm-basicBlock white adm-u-pad20_25">
    <?php
    $form = ActiveForm::begin([
                'id' => 'universityForm',
                'options' => [
                    'class' => 'horizontal-form',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col2">
        <?=
                $form->field($model, 'parent_id', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($parantUniversityType, ['class' => 'chzn-select', 'prompt' => 'Select Parent university type'])->label('Select Parent university type')
        ?>
        <?=
        $form->field($model, 'name', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium',],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
            'placeholder' => 'Name'
        ])->label('Name');
        ?>
        <?=
                $form->field($model, 'state_code', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($stateDropDownList, ['class' => 'chzn-select', 'prompt' => 'Select State']
                )->label('Select State')
        ?>
        <?=
                $form->field($model, 'is_active', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(['1' => 'Active', '0' => 'Inactive'], ['class' => 'chzn-select', 'prompt' => 'Select Status']
                )->label('Select Status')
        ?>

    </div>
    <div class="adm-u-flexed adm-u-justify-start cmt-20">
        <?= Html::submitButton($model->id <= 0 ? 'Create' : 'Update', ['class' => 'btn btn-primary adm-u-pad10_30 theme-button mb-3', 'name' => 'listtype-button']) ?>
        <a href="<?= Url::toRoute(['/university/index']) ?>" class="btn  adm-u-pad10_30 ml-3 mb-3 btn-secondary ">Cancel</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
