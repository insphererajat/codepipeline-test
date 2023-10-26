<?php

use common\models\MstListType;
use yii\helpers\Html;
use common\models\MstPost;
$i = 0;
?>
<?php if(isset($subjectList) && !empty($subjectList)): ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Subject</th>
            <th class="text-center" scope="col">Read</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subjectList as $subjectId => $subjectName): ?>
        <?= Html::hiddenInput('RegistrationForm[lt_subjects]['.$i.'][subject_id]', $subjectId); ?>
        <tr>
            <th scope="row"><?= ($i+1); ?></th>
            <td><?= $subjectName ?></td>
            <td>
                <div class="form-grider design1">
                    <div class="form-group field-registrationform-lt_subjects-<?= $i ?>-status">
                        <div class="cop-form--container"><div class="dropdowns-customized chosen fs14__regular u-fieldHeight48">
                                <select id="registrationform-lt_subjects-<?= $i ?>-staus" class="chzn-select " name="RegistrationForm[lt_subjects][<?= $i ?>][status]">
                                    <option value=""></option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php $i++; endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<div class="form-grider design1 js-Intermediate <?= (\yii\helpers\ArrayHelper::isIn($postId, [MstPost::SKA_SANVIKSHAK, MstPost::SKA_DEO, MstPost::SKA_GPDO])) ? '': 'hide' ?>">
    <div class="head-wrapper">
        <div class="head-wrapper__title">
            <div class="head-wrapper__title-label fs14__medium">
                <?= (\yii\helpers\ArrayHelper::isIn($postId, [MstPost::SKA_SANVIKSHAK, MstPost::SKA_DEO])) ? MstPost::ENGLISH_INTERMEDIATE_LABEL : MstPost::CCC_CERTIFICATE_LABEL; ?>
            </div>
        </div>
    </div>
    <div class="form-group field-registrationform-lt_intermediate-fourth_year">
        <div class="cop-form--container"><div class="dropdowns-customized chosen fs14__regular u-fieldHeight48">
                <select id="registrationform-lt_subjects-<?= $i ?>-fourth_year" class="chzn-select " name="RegistrationForm[lt_intermediate]">
                    <option value=""></option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
    </div>
</div>