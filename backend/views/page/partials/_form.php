<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$pageMediaId = 0;
if ($model->id > 0) {
    $connection = \common\models\MediaConnection::findByPageId($model->id);
    if (!empty($connection)) {
        $pageMediaId = $connection['media_id'];
    }
}

$this->registerJs('PageController.summary();');
?>

<?php
$form = ActiveForm::begin([
            'id' => 'pageForm',
            'options' => [
                'autocomplete' => 'off'
            ],
        ]);
?>
<?= Html::activeHiddenInput($model, 'id') ?> 
<?= Html::activeHiddenInput($model, 'guid') ?>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <?=
        $form->field($model, 'title')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
            'placeholder' => 'Title'
        ])->label('Title')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <?=
                $form->field($model, 'parent_id', [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                ])
                ->dropDownList(
                        \common\models\Page::getPagesListArr(), ['class' => 'chosen-select', 'prompt' => 'Select Parent Page']
                )->label('Select Parent Page')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <?=
        $form->field($model, 'meta_title')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
            'placeholder' => 'Meta Title'
        ])->label('Meta Title')
        ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?=
        $form->field($model, 'meta_keywords')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
            'placeholder' => 'Meta Keywords'
        ])->label('Meta Keywords')
        ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?=
        $form->field($model, 'meta_description')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control alpha-numeric-with-special disable-copy-paste',
            'placeholder' => 'Meta Description'
        ])->label('Meta Description')
        ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?=
        $form->field($model, 'external_link')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control alpha-numeric-with-special',
            'placeholder' => 'External Link'
        ])->label('External Link')
        ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?=
        $form->field($model, 'display_order')->textInput([
            'autocomplete' => 'off',
            'autofocus' => true,
            'maxlength' => true,
            'class' => 'form-control only-number disable-copy-paste',
            'placeholder' => 'Display Order'
        ])->label('Display Order')
        ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <?=
                $form->field($model, 'is_active', [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                ])
                ->dropDownList(
                        ['1' => 'Active', '0' => 'Inactive'], ['class' => 'chosen-select']
                )->label('Status')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <?=
        $form->field($model, 'content')->textarea([
            'id' => 'editor'
        ])->label(false);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="c-uploadwrap  <?= $pageMediaId > 0 ? 'c-uploadwrap--borderradius c-uploadwrap--withthemeblue js-addimg' : 'uploadPagePhoto' ?> ">
                <?php if ($pageMediaId > 0): ?>
                    <?php $mediaModel = common\models\Media::findById($pageMediaId); ?>
                    <img class="profile-image" src="<?= $mediaModel['cdn_path'] ?>"  />
                <?php endif; ?>
                <?= Html::activeHiddenInput($model, 'page_media_id') ?>
                <div class="c-uploadwrap__textsection <?= $pageMediaId > 0 ? 'd-none' : '' ?> " >
                    <div class="icon"><i class="fa fa-arrow-up" aria-hidden="true"></i></div>
                    <div class="content"><span>Upload</span> your page image </div>
                </div>
                <div class="c-uploadwrap_action <?= $pageMediaId <= 0 ? 'd-none' : '' ?> ">
                    <a href="javascript:;" class="c-uploadwrap_action-link withRedBg deletePageMedia" data-mediaid="<?= $pageMediaId ?>">
                        <span class="fa fa-trash icon"></span>
                    </a> 
                </div>
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-sm-12">
        <?= Html::submitButton((!isset($model->id) || $model->id < 0) ? 'Create' : 'Update', ['class' => 'c-button c-button-info', 'name' => 'page-button']) ?>
        <a href="<?= Url::toRoute(['page/index']) ?>" class="c-button c-button-inverse">Cancel</a> 
    </div>
</div>
<?php ActiveForm::end(); ?>