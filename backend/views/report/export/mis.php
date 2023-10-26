<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

$this->title = 'MIS Report';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Reports / MIS', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <div class="filters-wrapper adm-u-pad7_10">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'ApplicantSearchForm',
                                'method' => 'GET',
                                'options' => [
                                    'class' => 'widget__wrapper-searchFilter',
                                    'autocomplete' => 'off'
                                ],
                    ]);
                    ?>
                    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
                        <?=
                                $form->field($model, 'classified_id', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                                ])
                                ->dropDownList(common\models\MstClassified::getClassifiedDropdown(['notInIds' => [common\models\MstClassified::MASTER_CLASSIFIED]]), ['class' => 'chzn-select', 'prompt' => 'Select Advertisement']
                                )->label(FALSE)
                        ?>
                    </div>
                    <div class="filters-wrapper__action">
                        <div class="adm-c-button cml-10 cmt-10">
                            <input type="submit" class="btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3" value="Export" formaction="/report/export/mis">
                        </div> 
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

