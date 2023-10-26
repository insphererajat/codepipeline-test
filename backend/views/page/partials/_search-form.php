<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'pageSearchForm',
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
            $form->field($searchModel, 'title')->textInput([
                'autocomplete' => 'off',
                'autofocus' => true,
                'maxlength' => true,
                'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
                'placeholder' => 'Title'
            ])->label(False)
            ?>
        </li>
        <li class="action-button">
            <?= Html::submitButton( 'Search', ['class' => 'c-button c-button-outline c-button-outline-themeblue small', 'name' => 'page-search-button']) ?>
            <a href="<?= yii\helpers\Url::toRoute(['page/index']) ?>" class="c-button c-button-outline c-button-outline-inverse small">Reset</a>
        </li>
    </ul>
</div>
<?php ActiveForm::end(); ?>