<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'blockSearchForm',
            'method' => 'GET',
            'options' => [
                'autocomplete' => 'off'
            ],
        ]);
?>
<div class="clearfix"></div>
<div class="filters-wrapper ">
    <ul class="c-columnd c-columnd--col-5 c-columnd-xs"> 
        <li>
            <?=
            $form->field($searchModel, 'name')->textInput([
                'autocomplete' => 'off',
                'autofocus' => true,
                'maxlength' => true,
                'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
                'placeholder' => 'Name'
            ])->label(False)
            ?>
        </li>
        <li class="action-button">
            <?= Html::submitButton( 'Search', ['class' => 'c-button c-button-outline c-button-outline-themeblue small', 'name' => 'block-search-button']) ?>
            <a href="<?= yii\helpers\Url::toRoute(['block/index']) ?>" class="c-button c-button-outline c-button-outline-inverse small">Reset</a>
        </li>
    </ul>
</div>
<?php ActiveForm::end(); ?>