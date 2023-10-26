<?php

use yii\helpers\Html;
use common\models\ApplicantDetail;
use common\models\Media;
$applicationPostModel = \common\models\ApplicantPost::findById($model->applicantPostId, ['selectCols' => ['id', 'guid']]);
$photo = $signature = $birth = $caste = [];

if (isset($model->photo) && $model->photo > 0) {

    $mediaModel = \common\models\Media::findById($model->photo, ['selectCols' => ['id', 'guid', 'cdn_path']]);
    if (!empty($mediaModel)) {
        $photo['id'] = $mediaModel['id'];
        $photo['guid'] = $mediaModel['guid'];
        $photo['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
    }
}
if (isset($model->signature) && $model->signature > 0) {

    $mediaModel = \common\models\Media::findById($model->signature, ['selectCols' => ['id', 'guid', 'cdn_path']]);
    if (!empty($mediaModel)) {
        $signature['id'] = $mediaModel['id'];
        $signature['guid'] = $mediaModel['guid'];
        $signature['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
    }
}
if (isset($model->birth_certificate) && $model->birth_certificate > 0) {

    $mediaModel = \common\models\Media::findById($model->birth_certificate, ['selectCols' => ['id', 'guid', 'cdn_path']]);
    if (!empty($mediaModel)) {
        $birth['id'] = $mediaModel['id'];
        $birth['guid'] = $mediaModel['guid'];
        $birth['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
    }
}
if (isset($model->caste_certificate) && $model->caste_certificate > 0) {

    $mediaModel = \common\models\Media::findById($model->caste_certificate, ['selectCols' => ['id', 'guid', 'cdn_path']]);
    if (!empty($mediaModel)) {
        $caste['id'] = $mediaModel['id'];
        $caste['guid'] = $mediaModel['guid'];
        $caste['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
    }
}

$applicantDetail = ApplicantDetail::findByApplicantPostId($model->applicantPostId, [
            'selectCols' => new \yii\db\Expression("social_category_id, gender"),
        ]);
$applicantEmployment = $model->loadEmploymentList();
//echo '<pre>'; print_r($applicantEmployment);die;
?>
<?= Html::activeHiddenInput($model, 'social_category_id', ['value' => $applicantDetail['social_category_id']]) ?>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Certificates / Documents Uploaded</div>
        </div>
    </div>
</div>
<div class="c-form c-form-xs col4 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 js-applicantPostGuid" data-guid="<?= $applicationPostModel['guid']?>">
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Upload Photo</div>
            </div>
        </div>
        <div class="cop-form--container <?= isset($photo) && !empty($photo) ? '' : 'uploadPhotoContainer'; ?>">
            <a href="javascript:;" class="<?= isset($photo) && !empty($photo) ? '' : 'uploadPhoto'; ?>">
                <div class="cop-form__uploader design2 design2--auto">
                    <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                    <p class="cop-form__uploader--text"><span>Upload Photo</span></p>
                    <button type="button" data-id="<?= isset($photo['id']) ? $photo['id'] : '' ?>" data-guid="<?= isset($photo['guid']) ? $photo['guid'] : '' ?>" data-applicant-post="<?= $model->applicantPostId ?>" class="trash <?= isset($photo) && !empty($photo) ? '' : 'hide'; ?>"><i class="fa fa-trash-alt"></i></button>
                    <div class="cop-form__uploader--placeholder <?= isset($photo) && !empty($photo) ? '' : 'hide'; ?>">
                        <img class="rounded-circle" src="<?= isset($photo['cdnPath']) && !empty($photo['cdnPath']) ? $photo['cdnPath'] : ''; ?>">
                    </div>
                </div>
            </a>
        </div>
        <div class="js-uploadedContainer uploadedPhotoContainer hide"></div>
    </div>
    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Upload Signature</div>
            </div>
        </div>
        <div class="cop-form--container <?= isset($signature) && !empty($signature) ? '' : 'uploadSignatureContainer'; ?>">
            <a href="javascript:;" class="<?= isset($signature) && !empty($signature) ? '' : 'uploadSignature'; ?>">
                <div class="cop-form__uploader design2 design2--auto">
                    <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                    <p class="cop-form__uploader--text"><span>Upload Signature</span></p>
                    <button type="button" data-id="<?= isset($signature['id']) ? $signature['id'] : '' ?>" data-guid="<?= isset($signature['guid']) ? $signature['guid'] : '' ?>" data-applicant-post="<?= $model->applicantPostId ?>" class="trash <?= isset($signature) && !empty($signature) ? '' : 'hide'; ?>"><i class="fa fa-trash-alt"></i></button>
                    <div class="cop-form__uploader--placeholder <?= isset($signature) && !empty($signature) ? '' : 'hide'; ?>">
                        <img class="rounded-circle" src="<?= isset($signature['cdnPath']) && !empty($signature['cdnPath']) ? $signature['cdnPath'] : ''; ?>">
                    </div>
                </div>
            </a>
        </div>
        <div class="js-uploadedContainer uploadedSignatureContainer hide"></div>
    </div>

    <div class="form-grider design1">
        <div class="head-wrapper">
            <div class="head-wrapper__title label-required">
                <div class="head-wrapper__title-label fs14__medium">Upload Proof Of Birth Certificate</div>
            </div>
        </div>
        <div class="cop-form--container <?= isset($birth) && !empty($birth) ? '' : 'uploadBirthContainer'; ?>">
            <a href="javascript:;" class="<?= isset($birth) && !empty($birth) ? '' : 'uploadBirth'; ?>">
                <div class="cop-form__uploader design2 design2--auto">
                    <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                    <p class="cop-form__uploader--text"><span>Upload Proof Of Birth Certificate</span></p>
                    <button type="button" data-id="<?= isset($birth['id']) ? $birth['id'] : '' ?>" data-guid="<?= isset($birth['guid']) ? $birth['guid'] : '' ?>" data-applicant-post="<?= $model->applicantPostId ?>" class="trash <?= isset($birth) && !empty($birth) ? '' : 'hide'; ?>"><i class="fa fa-trash-alt"></i></button>
                    <div class="cop-form__uploader--placeholder <?= isset($birth) && !empty($birth) ? '' : 'hide'; ?>">
                        <img class="rounded-circle" src="<?= isset($birth['cdnPath']) && !empty($birth['cdnPath']) ? $birth['cdnPath'] : ''; ?>">
                    </div>
                </div>
            </a>
        </div>
        <div class="js-uploadedContainer uploadedBirthContainer hide"></div>
    </div>
    <?php if ($applicantDetail['social_category_id'] != \common\models\MstListType::UNRESERVED_GENERAL): ?>
        <div class="form-grider design1">
            <div class="head-wrapper">
                <div class="head-wrapper__title label-required">
                    <div class="head-wrapper__title-label fs14__medium">Upload Caste Certificate</div>
                </div>
            </div>
            <div class="cop-form--container <?= isset($caste) && !empty($caste) ? '' : 'uploadCasteContainer'; ?>">
                <a href="javascript:;" class="<?= isset($caste) && !empty($caste) ? '' : 'uploadCaste'; ?>">
                    <div class="cop-form__uploader design2 design2--auto">
                        <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                        <p class="cop-form__uploader--text"><span>Upload Caste Certificate</span></p>
                        <button type="button" data-id="<?= isset($caste['id']) ? $caste['id'] : '' ?>" data-guid="<?= isset($caste['guid']) ? $caste['guid'] : '' ?>" data-applicant-post="<?= $model->applicantPostId ?>" class="trash <?= isset($caste) && !empty($caste) ? '' : 'hide'; ?>"><i class="fa fa-trash-alt"></i></button>
                        <div class="cop-form__uploader--placeholder <?= isset($caste) && !empty($caste) ? '' : 'hide'; ?>">
                            <img class="rounded-circle" src="<?= isset($caste['cdnPath']) && !empty($caste['cdnPath']) ? $caste['cdnPath'] : ''; ?>">
                        </div>
                    </div>
                </a>
            </div>
            <div class="js-uploadedContainer uploadedCasteContainer hide"></div>
        </div>
    <?php endif; ?>
    <?php
    if (isset($applicantEmployment) && !empty($applicantEmployment)) :
        foreach ($applicantEmployment as $employment) {
            $this->registerJs("RegistrationV2Controller.employmentDocuments(" . $employment->id . ");");
            $employmentCertificate = [];
            $id = '';
            if (isset($model->upload_employment_certificate[$employment->id]) && !empty($model->upload_employment_certificate[$employment->id])) {
                $id = $model->upload_employment_certificate[$employment->id];
                $mediaModel = Media::findById($id, ['selectCols' => ['id', 'guid', 'cdn_path', 'media_type']]);
                if (!empty($mediaModel)) {
                    $employmentCertificate['id'] = $mediaModel['id'];
                    $employmentCertificate['guid'] = $mediaModel['guid'];
                    $employmentCertificate['cdnPath'] = Media::getDocMediaUrl($mediaModel);
                }
            }
            echo yii\helpers\Html::hiddenInput('RegistrationForm[upload_employment_certificate][' . $employment->id . ']', $id, ['class' => 'inputQualificationDocument-' . $employment->id]);
            ?>
            <div class="form-grider design1">
                <div class="head-wrapper">
                    <div class="head-wrapper__title label-required">
                        <div class="head-wrapper__title-label fs14__medium"><?= \common\models\MstListType::getName($employment->experience_type_id) ?> Certificate</div>
                    </div>
                </div>
                <div class="cop-form--container <?= isset($employmentCertificate) && !empty($employmentCertificate) ? '' : 'uploadEmploymentDocumentContainer-' . $employment->id; ?>">
                    <a href="javascript:;" class="<?= isset($employmentCertificate) && !empty($employmentCertificate) ? '' : 'uploadEmploymentDocument-' . $employment->id; ?>">
                        <div class="cop-form__uploader design2 design2--auto">
                            <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                            <p class="cop-form__uploader--text <?= isset($employmentCertificate) && !empty($employmentCertificate) ? 'hide' : ''; ?>"><span>Upload <?= \common\models\MstListType::getName($employment->experience_type_id) ?> Certificate</span></p>
                            <button type="button" data-id="<?= isset($employmentCertificate['id']) ? $employmentCertificate['id'] : '' ?>" data-guid="<?= isset($employmentCertificate['guid']) ? $employmentCertificate['guid'] : '' ?>" data-applicant-post="<?= $model->applicantPostId ?>" class="trash <?= isset($employmentCertificate) && !empty($employmentCertificate) ? '' : 'hide'; ?>"><i class="fa fa-trash-alt"></i></button>
                            <div class="cop-form__uploader--placeholder <?= isset($employmentCertificate) && !empty($employmentCertificate) ? '' : 'hide'; ?>">
                                <img class="rounded-circle" src="<?= isset($employmentCertificate['cdnPath']) && !empty($employmentCertificate['cdnPath']) ? $employmentCertificate['cdnPath'] : ''; ?>" alt="img not found">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="js-uploadedContainer uploadedEmploymentDocumentContainer-<?= $employment->id; ?> hide" data-input="inputQualificationDocument-<?= $employment->id ?>"></div>
            </div>
            <?php
        }
    endif;
    ?>
</div>
<span class="badge badge-info" style="white-space:normal; line-height:19px;">Note: Photo - 30kb to 100KB, Sign - 20kb to 50kb, Proof Of Birth Certificate/Caste Certificate/Employment Certificate - 20kb to 50kb, Image should be .jpg, .jpeg, .png format only.</span>