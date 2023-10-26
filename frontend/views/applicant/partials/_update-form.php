<?php

use yii\helpers\Url;

$queryParams = \Yii::$app->request->queryParams;

$document = [];

if (isset($model->media_id) && $model->media_id > 0) {

    $mediaModel = \common\models\Media::findById($model->media_id, ['selectCols' => ['id', 'guid', 'cdn_path']]);
    if (!empty($mediaModel)) {
        $document['id'] = $mediaModel['id'];
        $document['guid'] = $mediaModel['guid'];
        $document['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
    }
}

$this->registerJs("LogProfileController.createUpdate();");
?>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">OTR Update Request <span class="badge badge-info">(<strong>Note:</strong> You can only request for update Date of Birth, Candidate's Name, Father Name)</span></div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col3 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Date of Birth</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Date of Birth'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'date_of_birth', [
                    'template' => "<div class='cop-form--container calender-field'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'placeholder' => 'Date of Birth',
                    'data-label' => $model::instance()->getAttributeLabel('date_of_birth'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-birthDate disable-copy-paste nothing-press'
                ])
                ->label(FALSE);
        ?>
    </div>
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Candidate's Name</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Candidate's Name'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('name'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxRequired js-textboxName only-alphabet disable-copy-paste text-to-upper'
                ])
                ->label(FALSE);
        ?>

    </div>
    <div class="form-grider design1">
        <div class='head-wrapper'>
            <div class='head-wrapper__title label-required'>
                <div class='head-wrapper__title-label fs14__medium'>Father Name</div>
            </div>
            <div class='head-wrapper__options'>
                <span class='far fa-question-circle icon' data-toggle='tooltip' title='Father Name'></span>
            </div>
        </div>
        <?=
                $form->field($model, 'father_name', [
                    'template' => "<div class='cop-form--container'>\n{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                ->textInput([
                    'autocomplete' => 'off',
                    'maxlength' => true,
                    'data-label' => $model::instance()->getAttributeLabel('father_name'),
                    'class' => 'cop-form--container-field fs14__regular u-fieldHeight48 js-textboxRequired js-textboxName only-alphabet disable-copy-paste text-to-upper'
                ])
                ->label(FALSE);
        ?>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium <?= isset($document) && !empty($document) ? 'hide' : ''; ?>">Upload Document</div>
            </div>
        </div>
        <div class="cop-form--container <?= isset($document) && !empty($document) ? '' : 'uploadPhotoContainer'; ?>">
            <a href="javascript:;" class="<?= isset($document) && !empty($document) ? '' : 'uploadPhoto'; ?>">
                <div class="cop-form__uploader design2 design2--auto">
                    <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                    <p class="cop-form__uploader--text <?= isset($document) && !empty($document) ? 'hide' : ''; ?>"><span>High school certificate/ Aadhar Card</span></p>
                    <p class="cop-form__uploader--text <?= isset($document) && !empty($document) ? 'hide' : ''; ?>"><span>File type: .jpg, .jpeg, .png</span></p>
                    <p class="cop-form__uploader--text <?= isset($document) && !empty($document) ? 'hide' : ''; ?>"><span>File size: 100kb</span></p>
                    <button type="button" data-id="<?= isset($document['id']) ? $document['id'] : '' ?>" data-guid="<?= isset($document['guid']) ? $document['guid'] : '' ?>" data-log-profile-guid="<?= $model->guid ?>" class="trash <?= isset($document) && !empty($document) ? '' : 'hide'; ?>"><i class="fa fa-trash-alt"></i></button>
                    <div class="cop-form__uploader--placeholder <?= isset($document) && !empty($document) ? '' : 'hide'; ?>">
                        <img class="rounded-circle" src="<?= isset($document['cdnPath']) && !empty($document['cdnPath']) ? $document['cdnPath'] : ''; ?>">
                    </div>
                </div>
            </a>
        </div>
        <div class="uploadedPhotoContainer hide"></div>
    </div>
</div>
<div class="form-grider design1">
    <?=
    $form->field($model, 'reCaptcha')->widget(
            \himiklab\yii2\recaptcha\ReCaptcha::className(), ['siteKey' => \Yii::$app->params['reCAPTCHA.siteKey']]
    )->label(false)
    ?>
</div>
<div class="c-form c-form-xs u-fieldRadius8 u-fieldShadow u-fieldBorderClr2">
    <div class="u-flexed u-justify-btw mt-4"> 
    <?= yii\helpers\Html::submitButton('Save', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'submitButton']) ?>
    </div>
</div>

