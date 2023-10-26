<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Transaction;

$hide = (isset($model->id) && ($model->id) > 0 ) ? 'hide' : '';

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['TransactionSearch']) && !empty($searchParams['TransactionSearch'])) ? "" : "hide";
?>
<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls ?>">
    <?php
    $form = ActiveForm::begin([
                'id' => 'transactionForm',
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col2">
        <?=
                    $form->field($model, 'classified_id', [
                        'options' => ['class' => 'form-grider cop-form design1'],
                        'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                        'errorOptions' => ['class' => ' cop-form--help-block'],
                        'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                    ])
                    ->dropDownList(common\models\MstClassified::getClassifiedDropdown(), ['class' => 'chzn-select', 'prompt' => 'Select Advertisement']
                    )->label(FALSE)
            ?>
        <?=
        $form->field($model, 'from_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 from__date nothing-press disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'From Date'
        ])->label(false);
        ?>
        <?=
        $form->field($model, 'to_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 to__date nothing-press disable-copy-paste',
            'autocomplete' => 'off',
            'placeholder' => 'To Date'
        ])->label(false);
        ?>
        <?=
        $form->field($model, 'search', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autofocus' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special',
            'autocomplete' => 'off',
            'placeholder' => 'Transaction No., Gateway Transaction No. & Applicant Name /Mobile /Email',
        ])->label(false);
        ?>
        <?=
                $form->field($model, 'type', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(Transaction::getTypeDropdown(), ['class' => 'chzn-select', 'prompt' => 'Select Type']
                )->label(FALSE)
        ?>
        <?=
                $form->field($model, 'status', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(Transaction::getStatus(), ['class' => 'chzn-select', 'prompt' => 'Select Status']
                )->label(FALSE)
        ?>
        <?=
                $form->field($model, 'is_consumed', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(Transaction::getConsumeDropdown(), ['class' => 'chzn-select', 'prompt' => 'Select Consume Status']
                )->label(FALSE)
        ?>

    </div>
    <div class="filters-wrapper__action">
        <div class="adm-c-button cml-10 cmt-10">
            <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
            <a href="<?= yii\helpers\Url::toRoute(['transaction/index']) ?>" class="btn btn-rounded btn-secondary adm-u-pad8_20 mb-3">Reset</a>
            <?php if (Yii::$app->user->hasAdminRole()): ?>
                <input type="submit" class="btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3" value="Export" formaction="/transaction/export">
            <?php endif; ?>
        </div> 
    </div>
    <?php ActiveForm::end(); ?>
</div>
