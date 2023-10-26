<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$stateList = [];
$isTest = true;
if (isset($model->country_code) || $isTest) {
    $stateList = \common\models\MstState::getStateDropdown(['countryCode' => '91']);
}
?>

<?php
$form = ActiveForm::begin([
            'id' => 'blockForm',
            'options' => [
                'autocomplete' => 'off'
            ],
        ]);
?>
<?= Html::activeHiddenInput($model, 'guid') ?>
<div class="row">
    <div class="col-sm-6 col-md-6">
        <?=
        $form->field($model, 'name')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control only-alphabet disable-copy-paste',
            'placeholder' => 'Name'
        ])->label('Name')
        ?>
    </div>
    <div class="col-sm-6 col-md-6">
        <?=
        $form->field($model, 'code')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
            'placeholder' => 'Code'
        ])->label('Code')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-3 col-md-3">
        <?=
                $form->field($model, 'country_code', [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                ])
                ->dropDownList(
                        \common\models\MstCountry::getCountryDropdown(), ['class' => 'chosen-select country', 'prompt' => 'Select Country']
                )->label('Select Country')
        ?>
    </div>
    <div class="col-sm-3 col-md-3">
        <?=
                $form->field($model, 'state_code', [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                ])
                ->dropDownList(
                        $stateList, ['class' => 'chosen-select state', 'prompt' => 'Select State']
                )->label('Select State')
        ?>
    </div>
    <div class="col-sm-3 col-md-3">
        <?=
                $form->field($model, 'district_code', [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                ])
                ->dropDownList(
                        [], ['class' => 'chosen-select district', 'prompt' => 'Select District']
                )->label('Select District')
        ?>
    </div>
    <div class="col-sm-3 col-md-3">
        <?=
                $form->field($model, 'is_active', [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                ])
                ->dropDownList(
                        [
                    1 => 'Active',
                    0 => 'Inactive'
                        ], ['class' => 'chosen-select', 'prompt' => 'Select Status']
                )->label('Select Status')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?= Html::submitButton((!isset($model->guid) || empty($model->guid) ) ? 'Create' : 'Update', ['class' => 'c-button c-button-info', 'name' => 'block-button']) ?>
        <a href="<?= Url::toRoute(['block/index']) ?>" class="c-button c-button-inverse">Cancel</a> 
    </div>
</div>
<?php ActiveForm::end(); ?>