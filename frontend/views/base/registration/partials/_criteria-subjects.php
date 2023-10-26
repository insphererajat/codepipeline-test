<?php

use common\models\MstListType;
use yii\helpers\Html;
use common\models\MstPost;
?>
<?php if(isset($subjectList) && !empty($subjectList)): ?>
<div class="adm-c-tableGrid__box it-subject">
  <div id="listType" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
    <div id="w0" class="grid-view">
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Subject</th>
            <th class="text-center" scope="col">Read</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; foreach ($subjectList as $subjectId => $subjectName): ?>
        <?= Html::hiddenInput('RegistrationForm[lt_subjects]['.$i.'][subject_id]', $subjectId); ?>
        <tr>
            <th scope="row"><?= ($i+1); ?></th>
            <td><?= $subjectName ?></td>
            <td>
                <div class="form-grider design1">
                    <?=
                            $form->field($model, 'lt_subjects['.$i.'][status]', [
                                'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                                'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                            ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select', 'prompt' => ''])
                            ->label(FALSE);
                    ?>
                </div>
            </td>
        </tr>
        <?php $i++; endforeach; ?>
    </tbody>
</table>
</div>
</div>
</div>
<?php endif; ?>
<div class="form-grider design1 js-Intermediate <?= (\yii\helpers\ArrayHelper::isIn($model->lt_applicant_post_id, [MstPost::SKA_SANVIKSHAK, MstPost::SKA_DEO, MstPost::SKA_GPDO])) ? '': 'hide' ?>">
    <div class="head-wrapper">
        <div class="head-wrapper__title">
            <div class="head-wrapper__title-label fs14__medium"><?= (\yii\helpers\ArrayHelper::isIn($model->lt_applicant_post_id, [MstPost::SKA_SANVIKSHAK, MstPost::SKA_DEO])) ? MstPost::ENGLISH_INTERMEDIATE_LABEL : MstPost::CCC_CERTIFICATE_LABEL;?></div>
        </div>
    </div>
    <?=
        $form->field($model, 'lt_intermediate', [
            'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
            'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
        ->dropDownList(MstListType::selectTypeList(), ['class' => 'chzn-select', 'prompt' => ''])
        ->label(FALSE);
    ?>
</div>