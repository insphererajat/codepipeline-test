<?php
use common\models\MstQualification;
use common\models\MstQualificationSubject;
$stateDropDownList = common\models\location\MstState::getStateDropdown();
$councilList = \common\models\MstListType::getListTypeDropdownByParentId(\common\models\MstListType::COUNCIL);

$qualificationDegreeList = $boardList = [];
if (isset($model->qualification_degree_id) && $model->qualification_degree_id > 0 && $model->applicantQualificationId > 0) {
    $qualificationDegreeList = MstQualification::getQualificationDropdown(['parentid' => $model->qualification_type_id]);
}
if (isset($model->board_university) && $model->board_university > 0 && $model->applicantQualificationId > 0) {
    $params = [];
    if (\yii\helpers\ArrayHelper::isIn($model->qualification_type_id, [MstQualification::PARENT_10TH, MstQualification::PARENT_12])) {
        $params = ['parentId' => \common\models\MstUniversity::BOARD];
    }
    else if (\yii\helpers\ArrayHelper::isIn($model->qualification_type_id, [MstQualification::CERTIFICATIONS])) {
        $params = ['id' => \common\models\MstUniversity::OTHER];
    } else {
        $params = ['stateCode' => $model->university_state, 'parentId' => \common\models\MstUniversity::UNIVERSITY];
    }
    $boardList = common\models\MstUniversity::getUniversityDropdown($params);
}

$qualificationTypeList = common\models\MstQualification::getQualificationDropdown(['recordIsNull' => ['column' => 'parent_id']]);
$courseDurationList = common\models\MstQualification::getCourseDropDown();
$qualificationDegreeList[MstQualification::CHILD_OTHER] = 'Other';
$yearList = \common\models\ApplicantQualification::getYearDropdown();
?>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Qualifications Details <span class="badge badge-danger"><marquee>Note: Kindly enter your qualification details from SSC/10th onwards</marquee></span></div>
        </div>
    </div>
</div>
<div class="col-12 p-0">
    <div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3">
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Qualification Type</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'qualification_type_id', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($qualificationTypeList, ['class' => 'chzn-select-with-search qualificationType', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Year</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'qualification_year', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($yearList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Name of Course</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'qualification_degree_id', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($qualificationDegreeList, ['class' => 'chzn-select-with-search degree', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1 js-course <?= ($model->qualification_degree_id == MstQualification::CHILD_OTHER) ? '' : 'hide' ?>">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Course Name</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'course_name', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('course_name'),
                        'placeholder' => $model::instance()->getAttributeLabel('course_name'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Board/University (State)</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'university_state', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($stateDropDownList, ['class' => 'chzn-select-with-search qualificationstate', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1 js-universitySection <?= (isset($model->qualification_type_id) && $model->qualification_type_id == MstQualification::CERTIFICATIONS) ? 'hide' : '' ?>">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Board/University</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'board_university', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($boardList, ['class' => 'chzn-select-with-search qualificationuniversity'])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1 js-otherboard <?= ($model->board_university == common\models\MstUniversity::OTHER) ? '' : 'hide' ?>">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Other Board/University</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'other_board', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('other_board'),
                        'placeholder' => $model::instance()->getAttributeLabel('other_board'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Result Status</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'result_status', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList([1 => 'PASSED'], ['class' => 'chzn-select', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Course Duration</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'course_duration', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList($courseDurationList, ['class' => 'chzn-select-with-search', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Result Type</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'mark_type', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList(\common\models\ApplicantQualification::getResultType(), ['class' => 'chzn-select qualificationselectAttr', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1 qualification qualificationmarks hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Out Of</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'total_marks', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('total_marks'),
                        'placeholder' => $model::instance()->getAttributeLabel('total_marks'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1 qualification qualificationmarks hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Marks Obtained</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'obtained_marks', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('obtained_marks'),
                        'placeholder' => $model::instance()->getAttributeLabel('obtained_marks'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1 qualification qualificationcgpa hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">CGPA</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'cgpa', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('cgpa'),
                        'placeholder' => $model::instance()->getAttributeLabel('cgpa'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>


        <div class="form-grider design1 qualification qualificationgrade hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Grade</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'grade', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'], ['class' => 'chzn-select', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>

        <div class="form-grider design1 qualification qualificationmarks qualificationgrade  qualificationcgpa hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Percentage</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'percentage', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('percentage'),
                        'placeholder' => $model::instance()->getAttributeLabel('percentage'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 only-number disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1 qualification qualificationmarks qualificationgrade  qualificationcgpa hide">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Division</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'division', [
                        'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->dropDownList(\common\models\ApplicantQualification::getDivisionList(), ['class' => 'chzn-select', 'prompt' => ''])
                    ->label(FALSE);
            ?>
        </div>
    </div>
    <div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 js-net_set <?= ($model->qualification_type_id == MstQualification::NET_SLET_SET) ? '': 'hide' ?>">
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title">
                    <div class="head-wrapper__title-label fs14__medium">Date of Qualifying</div>
                </div>
            </div>

            <?=
                    $form->field($model, 'net_qualifying_date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('net_qualifying_date'),
                        'placeholder' => $model::instance()->getAttributeLabel('net_qualifying_date'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                    ])
                    ->label(FALSE);
            ?>
        </div>
    </div>
    <div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mb-3 js-mphil_phd <?= (\yii\helpers\ArrayHelper::isIn($model->qualification_type_id, [MstQualification::MPHILL, MstQualification::PHD])) ? '': 'hide' ?>">
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title">
                    <div class="head-wrapper__title-label fs14__medium">Registration No.</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'mphil_phd_registration_no', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('mphil_phd_registration_no'),
                        'placeholder' => $model::instance()->getAttributeLabel('mphil_phd_registration_no'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title">
                    <div class="head-wrapper__title-label fs14__medium">Project Title</div>
                </div>
            </div>
            <?=
                    $form->field($model, 'mphil_phd_project_title', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('mphil_phd_project_title'),
                        'placeholder' => $model::instance()->getAttributeLabel('mphil_phd_project_title'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 alpha-numeric-with-special disable-copy-paste'
                    ])
                    ->label(FALSE);
            ?>
        </div>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title">
                    <div class="head-wrapper__title-label fs14__medium">Registration Date</div>
                </div>
            </div>

            <?=
                    $form->field($model, 'mphil_phd_registration_date', [
                        'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                        'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                    ->textInput([
                        'autocomplete' => 'off',
                        'maxlength' => true,
                        'data-label' => $model::instance()->getAttributeLabel('mphil_phd_registration_date'),
                        'placeholder' => $model::instance()->getAttributeLabel('mphil_phd_registration_date'),
                        'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-datetimepicker disable-copy-paste nothing-press'
                    ])
                    ->label(FALSE);
            ?>
        </div>
    </div>
</div>
<div class="u-flexed u-justify-center mt-4"> 
    <?= yii\helpers\Html::submitButton($model->applicantQualificationId > 0 ? 'Update' : 'Add', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'submitButton']) ?>
</div>