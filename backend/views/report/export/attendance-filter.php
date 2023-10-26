<?php

use common\models\ApplicantExam;
use common\models\caching\ModelCache;
use common\models\ExamCentre;
use common\models\ExamCentreDetail;
use common\models\MstClassified;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Attendance Sheet';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Reports / Attendance', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
$this->registerJs("ExportController.attendance()");

$examCentreList = $roomList = [];
if(!empty($model->classified_id)) {
    $examCentreList = ExamCentre::getExamCentreDropdown(['classifiedId' => $model->classified_id]);
}
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
                                $form->field($model, 'exam_type', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                                ])
                                ->dropDownList(ApplicantExam::getExamType(), ['class' => 'chzn-select js-classified', 'required' => true]
                                )->label(FALSE)
                        ?>
                        <?=
                                $form->field($model, 'classified_id', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                                ])
                                ->dropDownList(common\models\MstClassified::getClassifiedDropdown(['isAttendance' => ModelCache::IS_ACTIVE_YES, 'notInIds' => [MstClassified::MASTER_CLASSIFIED]]), ['class' => 'chzn-select js-classified', 'prompt' => 'Select Advertisement', 'required' => true]
                                )->label(FALSE)
                        ?>
                        <?=
                                $form->field($model, 'exam_centre_id', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                                ])
                                ->dropDownList($examCentreList, ['class' => 'chzn-select js-examcentre', 'prompt' => 'Select Exam Centre', 'required' => true]
                                )->label(FALSE)
                        ?>
                    </div>
                    <div class="filters-wrapper__action">
                        <div class="adm-c-button cml-10 cmt-10">
                            <input type="submit" class="btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3" value="Preview" formaction="/report/export/attendance">
                        </div> 
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>