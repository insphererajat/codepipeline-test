
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin([
            'id' => 'roleForm',
            'action' => \yii\helpers\Url::toRoute(['role/create']),
            'options' => [
                'autocomplete' => 'off'
            ],
        ]);
?>
<div class="filters-wrapper">
    <ul class="c-columnd c-columnd--col-3 c-columnd-xs">
        <li>
            <?=
            $form->field($model, 'id')->textInput([
                'autofocus' => true,
                'class' => 'form-control',
                'placeholder' => 'Role Id'
            ])->label('Role ID')
            ?>

        </li>
        <li>
            <?=
            $form->field($model, 'name')->textInput([
                'autofocus' => true,
                'class' => 'form-control',
                'placeholder' => 'Role Name'
            ])->label('Role Name')
            ?>
        </li>
        <li class="action-button">
            <?= Html::submitButton('Save', ['class' => 'c-button c-button-outline c-button-outline-themeblue small', 'name' => 'role-button']) ?>
        </li>
    </ul>
</div>
<?php ActiveForm::end(); ?>