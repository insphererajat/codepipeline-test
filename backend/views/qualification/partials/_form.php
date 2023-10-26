<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = (isset($model->id) && $model->id > 0) ? 'Update' : 'Create';
$params = [
    'selectCols' => ['id', 'name'],
    'recordIsNull' => [
        'column' => 'mst_qualification.parent_id'
    ],
    'resultCount' => \common\models\caching\ModelCache::RETURN_ALL
];
$parentListTypeModel = common\models\MstQualification::findMstQualificationModel($params);
$list = \yii\helpers\ArrayHelper::map($parentListTypeModel, 'id', 'name');

$subjectList = \common\models\MstSubject::getSubjectDropdown();
?>

<?php
$form = ActiveForm::begin([
            'id' => (isset($model->id) && $model->id > 0) ? 'editQualificationForm' : 'newQualificationForm',
            'action' => (isset($url)) ? $url : '',
            'options' => [
                'class' => 'horizontal-form',
                'autocomplete' => 'off'
            ],
        ]);
?>
<div class="adm-basicBlock white adm-u-pad20_25">
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4">

        <?=
                $form->field($model, 'parent_id', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($list, ['class' => 'chzn-select', 'prompt' => 'Select Parent']
                )->label('Select Parent')
        ?>

        <?=
        $form->field($model, 'name', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 exclude-special-charcters disable-copy-paste',
            'placeholder' => 'Name'
        ])->label('Name');
        ?>

        <?=
        $form->field($model, 'display_order', [
            'options' => ['class' => 'form-grider cop-form design1'],
            'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
            'errorOptions' => ['class' => ' cop-form--help-block'],
            'template' => "<div class='head-wrapper'><div class='head-wrapper__title label-required'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
        ])->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 only-number disable-copy-paste',
            'placeholder' => 'Display Order'
        ])->label('Display Order');
        ?>

        <?=
                $form->field($model, 'is_active', [
                    'options' => ['class' => 'form-grider cop-form design1'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList(['1' => 'Active', '0' => 'Inactive'], ['class' => 'chzn-select', 'prompt' => 'Select Status']
                )->label('Select Status')
        ?>

        <?=
                $form->field($model, 'subjects', [
                    'options' => ['class' => 'form-grider cop-form design1 col-fullwidth'],
                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                    'errorOptions' => ['class' => ' cop-form--help-block'],
                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                ])
                ->dropDownList($subjectList, ['class' => 'chzn-select', 'prompt' => 'Select Subjects', 'multiple' => "multiple"]
                )->label('Select Subjects')
        ?>

    </div>
    <div class="adm-u-flexed adm-u-justify-center adm-u-align-end cmt-20">
        <?= Html::submitButton(isset($model->id) && $model->id > 0 ? 'Update' : 'Create', ['class' => 'btn btn-primary adm-u-pad10_30 theme-button mb-3 cmr-10', 'type' => 'submit']) ?>            
        <a class="btn  adm-u-pad10_30 ml-3 mb-3 btn-secondary" href="<?= Url::toRoute(['qualification/index']) ?>">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>