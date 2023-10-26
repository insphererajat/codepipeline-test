<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'boardSearchForm',
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
                'autofocus' => true,
                'class' => 'form-control',
                'placeholder' => 'Trade name'
            ])->label(False)
            ?>
        </li>
  
        <li class="action-button">
            <?= Html::submitButton( 'Search', ['class' => 'c-button c-button-outline c-button-outline-themeblue small', 'name' => 'trade-button']) ?>
            <a href="<?= yii\helpers\Url::toRoute(['trade/index']) ?>" class="c-button c-button-outline c-button-outline-inverse small">Reset</a>
        </li>
    </ul>
</div>
<?php ActiveForm::end(); ?>