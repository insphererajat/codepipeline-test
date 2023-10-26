<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\search\report\PostWiseForm;
use common\models\MstClassified;
use common\models\MstPost;

$classifieds = MstClassified::getClassifiedDropdown(['notMsaterId' => true]);
$posts = MstPost::getPostDropdown();
?>

<div class="adm-basicBlock white adm-u-pad20_25">
    <?php
    $form = ActiveForm::begin([
                'id' => 'postWiseSearchForm',
                'method' => 'GET',
                'options' => [
                    'class' => 'widget__wrapper-searchFilter',
                    'autocomplete' => 'off'
                ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col3 col3--withButton">
        <?=
                $form->field($model, 'type', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(PostWiseForm::getType(), ['class' => 'chzn-select', 'prompt' => 'Select Type']
                )->label(FALSE)
        ?>
        <?=
        $form->field($model, 'from_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 from__date',
            'autocomplete' => 'off',
            'placeholder' => 'From Date'
        ])->label(FALSE);
        ?>
        <?=
        $form->field($model, 'to_date', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 to__date',
            'autocomplete' => 'off',
            'placeholder' => 'To Date'
        ])->label(FALSE);
        ?>
        <?=
                $form->field($model, 'classified_id', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($classifieds, ['class' => 'chzn-select', 'prompt' => 'Select Advertisement']
                )->label(FALSE)
        ?>
        <?=
                $form->field($model, 'post_id', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($posts, ['class' => 'chzn-select', 'prompt' => 'Select Post']
                )->label(FALSE)
        ?>
    </div>
    <div class="filters-wrapper__action">
        <div class="adm-c-button cml-10 cmt-10">
            <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
            <a href="<?= Url::toRoute(['report/post-wise/index']) ?>" class="btn btn-rounded btn-secondary adm-u-pad8_20 mb-3">Reset</a>
        </div> 
    </div>
    <?php ActiveForm::end(); ?>
</div>