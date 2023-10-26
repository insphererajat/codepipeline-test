<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\location\MstState;
use common\models\location\MstDistrict;
use common\models\location\MstTehsil;
use common\models\MstListType;
use common\models\ApplicantDetail;
use common\models\Transaction;
use yii\helpers\ArrayHelper;
use common\models\MstConfiguration;

$this->title = 'Review Registration Form';
$this->params['bodyClass'] = 'frame__body';
$this->registerJs("RegistrationV2Controller.createUpdate();");

$presentAddressState = MstState::findByCode($model->present_address_state_code, ['selectCols' => ['name']]);
$presentAddressDistrict = MstDistrict::findByCode($model->present_address_district_code, ['selectCols' => ['name']]);

if (!$model->same_as_present_address) {
    $permanentAddressState = MstState::findByCode($model->permanent_address_state_code, ['selectCols' => ['name']]);
    $permanentAddressDistrict = MstDistrict::findByCode($model->permanent_address_district_code, ['selectCols' => ['name']]);
}
$socialCategory = \common\models\MstListType::findById($model->social_category_id, ['selectCols' => ['name']]);
$socialCategoryAuthority = \common\models\MstListType::findById($model->social_category_certificate_issue_authority_id, ['selectCols' => ['name']]);
$certificateIssueDistrict = MstDistrict::findByCode($model->social_category_certificate_district_code, ['selectCols' => ['name']]);
$disability = \common\models\MstListType::findById($model->disability_id, ['selectCols' => ['name']]);

$otherdetailEmployerType = $otherdetailEmploymentNature = $employmentRegistrationOffice = NULL;
if (!empty($model->employer_type_id)) {
    $otherdetailEmployerType = \common\models\MstListType::findById($model->employer_type_id, ['selectCols' => ['name']]);
}
if (!empty($model->employment_nature)) {
    $otherdetailEmploymentNature = common\models\ApplicantDetail::getEmploymentNatureDropDown($model->employment_nature);
}

$photo = $signature = $birthCertificate = $castCertificate = [];

if (isset($model->photo) && $model->photo > 0) {

    $mediaModel = \common\models\Media::findById($model->photo, ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
    if (!empty($mediaModel)) {
        $photo['id'] = $mediaModel['id'];
        $photo['guid'] = $mediaModel['guid'];
        $photo['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
        $photo['filename'] = $mediaModel['filename'];
    }
}
if (isset($model->signature) && $model->signature > 0) {

    $mediaModel = \common\models\Media::findById($model->signature, ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
    if (!empty($mediaModel)) {
        $signature['id'] = $mediaModel['id'];
        $signature['guid'] = $mediaModel['guid'];
        $signature['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
        $signature['filename'] = $mediaModel['filename'];
    }
}
if (isset($model->birth_certificate) && $model->birth_certificate > 0) {

    $mediaModel = \common\models\Media::findById($model->birth_certificate, ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
    if (!empty($mediaModel)) {
        $birthCertificate['id'] = $mediaModel['id'];
        $birthCertificate['guid'] = $mediaModel['guid'];
        $birthCertificate['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
        $birthCertificate['filename'] = $mediaModel['filename'];
    }
}
if (isset($model->caste_certificate) && $model->caste_certificate > 0) {

    $mediaModel = \common\models\Media::findById($model->caste_certificate, ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
    if (!empty($mediaModel)) {
        $castCertificate['id'] = $mediaModel['id'];
        $castCertificate['guid'] = $mediaModel['guid'];
        $castCertificate['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
        $castCertificate['filename'] = $mediaModel['filename'];
    }
}

$ageCalculateDate = common\models\MstClassified::AGE_CALCULATE_DATE;
$params = \Yii::$app->request->queryParams;
$qr = [];
foreach ($params as $key => $value) {
    if (!empty($value)) {
        $qr[$key] = $value;
    }
}
if (isset($params['guid']) && !empty($params['guid'])) {
    $ageCalculateDate = common\models\MstClassified::getReferenceDate($params['guid']);
}

$applicantPostModel = common\models\ApplicantPost::findById($model->applicantPostId, ['applicantStatus' => common\models\ApplicantPost::APPLICATION_STATUS_PENDING_ESERVICE]);
$parentApplicantPost = common\models\ApplicantPost::findByParentApplicantPostId($applicantPostModel['parent_applicant_post_id'], ['countOnly' => true]);
$action = '/payment/cc-avenue/application';
if (\components\Helper::checkCscConnect()) {
    $action = '/payment/csc/request';
}
$form = ActiveForm::begin([
            'action' => [$action],
            'method' => 'post',
            'id' => 'ReviewDetailForm',
            'options' => [
                'class' => 'widget__wrapper-searchFilter',
                'autocomplete' => 'off'
            ],
        ]);

echo yii\bootstrap\Html::activeHiddenInput($model, 'is_eservice');
if (\Yii::$app->session->has('_connectData') && !empty(\Yii::$app->session->get('_connectData'))) {
    $mstConfigModel = MstConfiguration::findByType(MstConfiguration::TYPE_PAYMENT_CSC);
    if ($mstConfigModel != null) {
        $mstConfigModel = MstConfiguration::decryptValues($mstConfigModel);
        if (!empty($mstConfigModel['configuration_rule'])) {
            $rule = \yii\helpers\Json::decode($mstConfigModel['configuration_rule']);
            $model->fee_amount += isset($rule['rule']['wallet']) ? $rule['rule']['wallet'] : 0;
        }
    }
}
?>
<div class="main-body">
    <div class="register__wrapper">
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step7' => TRUE, 'formstep' => $step]); ?>
            <div class="f-c__list-section">
                <?= $this->render('/layouts/partials/flash-message.php') ?>
                <div class="f-c__review-section-head d2"><span class="fa fa-eye"></span>Please Confirm The Details Entered By You</div>
                <div class="f-c__review-container">
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Advertisement Details</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/criteria-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li class="third <?= ($model->classifiedId == null) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field">Advertisement Name & No.</span>
                                <span class="f-c__review-section__list--detail">
                                    <?= common\models\MstClassified::getTitle($model->classifiedId); ?>
                                </span>
                            </li>
                        </ul>
                        <ul class="f-c__review-section__list">
                            <li class="third <?= ($model->posts == null) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field">Applied Posts</span>
                                <span class="f-c__review-section__list--detail">
                                    <?php
                                    $posts = '';
                                    foreach ($model->posts as $key => $postRecord) {
                                        $posts .= common\models\MstPost::getTitle($key) . ", ";
                                    }
                                    echo rtrim($posts, ", ");
                                    ?>
                                </span>
                            </li>
                        </ul>
                        <?php if (isset($model->application_no) && !empty($model->application_no)): ?>
                            <ul class="f-c__review-section__list">
                                <li class="third">
                                    <span class="f-c__review-section__list--field">Application No.</span>
                                    <span class="f-c__review-section__list--detail"><?= $model->application_no; ?></span>
                                </li>
                            </ul>
                        <?php endif; ?>
                        <div class="f-c__review-section--title"><span class="text">Documents</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/document-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__img-wrap review-img">
                            <li>
                                <a href="javascript:;">
                                    <figure>
                                        <img src="<?= isset($photo['cdnPath']) && !empty($photo['cdnPath']) ? $photo['cdnPath'] : ''; ?>" alt="image">

                                        <figcaption>
                                            Photo
                                            <small></small>
                                            <br>
                                            <small>
                                            </small>
                                        </figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <figure>
                                        <img src="<?= isset($signature['cdnPath']) && !empty($signature['cdnPath']) ? $signature['cdnPath'] : ''; ?>" alt="image">

                                        <figcaption>
                                            Sign
                                            <small></small>
                                            <br>
                                            <small>
                                            </small>
                                        </figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <figure>
                                        <img src="<?= isset($birthCertificate['cdnPath']) && !empty($birthCertificate['cdnPath']) ? $birthCertificate['cdnPath'] : ''; ?>" alt="image">

                                        <figcaption>
                                            Birth Certificate
                                            <small></small>
                                            <br>
                                            <small>
                                            </small>
                                        </figcaption>
                                    </figure>
                                </a>
                            </li>
                            <?php if (!empty($castCertificate)): ?>
                                <li>
                                    <a href="javascript:;">
                                        <figure>
                                            <img src="<?= isset($castCertificate['cdnPath']) && !empty($castCertificate['cdnPath']) ? $castCertificate['cdnPath'] : ''; ?>" alt="image">

                                            <figcaption>
                                                Cast Certificate
                                                <small></small>
                                                <br>
                                                <small>
                                                </small>
                                            </figcaption>
                                        </figure>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php
                            if (isset($model->upload_employment_certificate) && !empty($model->upload_employment_certificate)) {

                                foreach ($model->upload_employment_certificate as $key => $value) {
                                    $mediaModel = \common\models\Media::findById($value, ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
                                    if (!empty($mediaModel)) {
                                        $applicantEmploymentModel = common\models\ApplicantEmployment::findById($key);
                                        ?>
                                        <li>
                                            <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']); ?>" alt="image">

                                                    <figcaption>
                                                        <?= MstListType::getName($applicantEmploymentModel['experience_type_id']) ?>
                                                        <small></small>
                                                        <br>
                                                        <small>
                                                        </small>
                                                    </figcaption>
                                                </figure>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                        <div class="f-c__review-section--title"><span class="text">Identity Details</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/personal-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li>
                                <span class="f-c__review-section__list--field">Identity Type</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->identity_type_id != null) ? MstListType::getName($model->identity_type_id) : ''; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Identity Certificate No</span>
                                <span class="f-c__review-section__list--detail"><?= $model->identity_type_display; ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Personal Information</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/personal-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li>
                                <span class="f-c__review-section__list--field">Candidate's Name</span>
                                <span class="f-c__review-section__list--detail"><?= $model->name; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Mobile No.</span>
                                <span class="f-c__review-section__list--detail"><?= $model->mobile; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Email Id</span>
                                <span class="f-c__review-section__list--detail"><?= components\Helper::emailConversion($model->email); ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Date Of Birth</span>
                                <span class="f-c__review-section__list--detail"><?= $model->date_of_birth; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Age (<?= Yii::t('app', 'age.limit') ?>)</span>
                                <span class="f-c__review-section__list--detail">
                                    <?php
                                    if (!empty($model->date_of_birth)) {
                                        $age = date_diff(date_create($model->date_of_birth), date_create($ageCalculateDate));
                                        echo $age->y . ' Years, ' . $age->m . ' Months, ' . $age->d . ' Days (as per calculate- ' . date('d-m-Y', strtotime($ageCalculateDate)) . ')';
                                    }
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Nationality</span>
                                <span class="f-c__review-section__list--detail"><?= common\models\ApplicantDetail::getNationality($model->nationality); ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Full Name Of Father/Husband</span>
                                <span class="f-c__review-section__list--detail"><?= !empty($model->father_salutation) ? ApplicantDetail::getFatherSalutation($model->father_salutation) : ''; ?> <?= $model->father_name; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Mother's Name</span>
                                <span class="f-c__review-section__list--detail"><?= !empty($model->mother_salutation) ? ApplicantDetail::getMotherSalutation($model->mother_salutation) : ''; ?> <?= $model->mother_name; ?></span>
                            </li>                            
                            <li>
                                <span class="f-c__review-section__list--field">Birth State/UT</span>
                                <span class="f-c__review-section__list--detail"><?= !empty($model->birth_state_code) ? MstState::getName($model->birth_state_code) : ''; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Gender</span>
                                <span class="f-c__review-section__list--detail"><?= $model->gender; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Marital Status</span>
                                <span class="f-c__review-section__list--detail"><?= common\models\ApplicantDetail::getMaritalStatus($model->marital_status); ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Domicile & Disability Details</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/personal-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li>
                                <span class="f-c__review-section__list--field"><?= Yii::t('app', 'is_domiciled') ?></span>
                                <span class="f-c__review-section__list--detail"><?= isset($model->is_domiciled) && $model->is_domiciled ? 'YES' : 'NO'; ?></span>
                            </li>
                            <li class="<?= ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field"><?= Yii::t('app', 'domicile_no') ?></span>
                                <span class="f-c__review-section__list--detail"><?= ($model->domicile_no != null) ? $model->domicile_no : ''; ?></span>
                            </li>
                            <li class="<?= ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field"><?= Yii::t('app', 'domicile_issue_district') ?></span>
                                <span class="f-c__review-section__list--detail"><?= ($model->domicile_issue_district != null) ? MstDistrict::getName($model->domicile_issue_district) : ''; ?></span>
                            </li>
                            <li class="<?= ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field"><?= Yii::t('app', 'domicile_issue_date') ?></span>
                                <span class="f-c__review-section__list--detail"><?= ($model->domicile_issue_date != null) ? $model->domicile_issue_date : ''; ?></span>
                            </li>                            
                        </ul>
                    </div>
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Category Reservation Details</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/personal-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li>
                                <span class="f-c__review-section__list--field">Category</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->social_category_id != null) ? MstListType::getName($model->social_category_id) : ''; ?></span>
                            </li>
                            <li class="<?= ($model->social_category_id == MstListType::OBC) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Do you belong Non-creamy Layer?</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->is_non_creamy_layer != null) ? 'Yes' : 'No'; ?></span>
                            </li>
                            <li class="<?= ($model->social_category_id == MstListType::OBC) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Valid Upto</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->social_category_certificate_valid_upto_date != null) ? $model->social_category_certificate_valid_upto_date : ''; ?></span>
                            </li>
                            <li class="<?= (in_array($model->social_category_id, [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Certificate Number</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->social_category_certificate_no != null) ? $model->social_category_certificate_no : ''; ?></span>
                            </li>
                            <li class="<?= (in_array($model->social_category_id, [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Certificate Issuing District</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->social_category_certificate_district_code != null) ? MstDistrict::getName($model->social_category_certificate_district_code) : ''; ?></span>
                            </li>
                            <li class="<?= (in_array($model->social_category_id, [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Certificate Issuing Authority</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->social_category_certificate_issue_authority_id != null) ? MstListType::getName($model->social_category_certificate_issue_authority_id) : ''; ?></span>
                            </li>
                            <li class="<?= (in_array($model->social_category_id, [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Certificate Issuing Date</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->social_category_certificate_issue_date != null) ? $model->social_category_certificate_issue_date : ''; ?></span>
                            </li>                            

                        </ul>
                    </div>
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Subcategory Reservation Details</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/personal-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li class="third">
                                <span class="f-c__review-section__list--field">Are you Specially Abled Person(PH/Divyang)?</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->disability_id != null) ? MstListType::getName($model->disability_id) : ''; ?></span>
                            </li>
                            <li class="third <?= (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">Percentage Of Handicap</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->disability_percentage != null) ? $model->disability_percentage : ''; ?></span>
                            </li>
                            <li class="third <?= (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">PH Certificate No :</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->disability_certificate_no != null) ? $model->disability_certificate_no : ''; ?></span>
                            </li>
                            <li class="third <?= (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">PH Certificate Issuing Date</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->disability_certificate_issue_date != null) ? $model->disability_certificate_issue_date : ''; ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Other Details</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/basic-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li class="third">
                                <span class="f-c__review-section__list--field">Are You Ex-Army Person ?</span>
                                <span class="f-c__review-section__list--detail"><?= !empty($model->is_exserviceman) ? 'Yes' : 'No'; ?></span>
                            </li>
                            <li class="third <?= ($model->is_exserviceman != frontend\models\RegistrationForm::SELECT_TYPE_YES) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field">Certificate No</span>
                                <span class="f-c__review-section__list--detail"><?= $model->exserviceman_qualification_certificate; ?></span>
                            </li>
                        </ul>
                        <div class="f-c__review-section--title"><span class="text">Black List/ Declaration</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/basic-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li class="third">
                                <span class="f-c__review-section__list--field">Whether Debarded or Black listed for examination by UPSC/SSC/State PSC/Board etc?</span>
                                <span class="f-c__review-section__list--detail"><?= !empty($model->is_debarred) ? 'Yes' : 'No'; ?></span>
                            </li>
                            <li class="third <?= ($model->debarred_from_date != null) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">From Date</span>
                                <span class="f-c__review-section__list--detail"><?= $model->debarred_from_date; ?></span>
                            </li>
                            <li class="third <?= ($model->debarred_to_date != null) ? '' : 'hide' ?>">
                                <span class="f-c__review-section__list--field">To Date</span>
                                <span class="f-c__review-section__list--detail"><?= $model->debarred_to_date; ?></span>
                            </li>
                        </ul>
                    </div>                    
                    <div class="f-c__review-section multi">
                        <div class="f-c__review-section--title"><span class="text">Identity/Adress Proof (POI/POA)</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/address-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li>
                                <span class="f-c__review-section__list--field">Flat / Room / Door / Block / House No.</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_house_no; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Flat / Room / Door / Block / House No.</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->permanent_address_house_no : $model->permanent_address_house_no; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Name of Premises / Building</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_premises_name; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Name of Premises / Building</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->permanent_address_premises_name : $model->permanent_address_premises_name; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Road / Street / Lane / Post Office</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_street; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Road / Street / Lane / Post Office</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->permanent_address_street : $model->permanent_address_street; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Area / Locality</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_area; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Area / Locality</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->permanent_address_area : $model->permanent_address_area; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Landmark</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_landmark; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Landmark</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->permanent_address_landmark : $model->permanent_address_landmark; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">State/UT</span>
                                <span class="f-c__review-section__list--detail"><?= $presentAddressState['name']; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">State/UT</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $presentAddressState['name'] : $permanentAddressState['name']; ?> </span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">District</span>
                                <span class="f-c__review-section__list--detail"><?= $presentAddressDistrict['name']; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">District</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $presentAddressDistrict['name'] : $permanentAddressDistrict['name']; ?> </span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Tehsil</span>
                                <span class="f-c__review-section__list--detail">
                                    <?php
                                    if (!empty($model->present_address_tehsil_code)):
                                        echo MstTehsil::getName($model->present_address_tehsil_code);
                                    elseif (!empty($model->present_address_tehsil_name)):
                                        echo 'Other';
                                    endif;
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Tehsil</span>
                                <span class="f-c__review-section__list--detail">
                                    <?php
                                    if (!empty($model->permanent_address_tehsil_code)):
                                        echo MstTehsil::getName($model->permanent_address_tehsil_code);
                                    elseif (!empty($model->permanent_address_tehsil_name)):
                                        echo 'Other';
                                    endif;
                                    ?>
                                </span>
                            </li>
                            <li class="<?= ($model->present_address_tehsil_name == null) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field">Tehsil Name</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_tehsil_name; ?></span>
                            </li>
                            <li class="<?= ($model->permanent_address_tehsil_name == null) ? 'hide' : '' ?>">
                                <span class="f-c__review-section__list--field">Tehsil Name</span>
                                <span class="f-c__review-section__list--detail"><?= $model->permanent_address_tehsil_name; ?> </span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Village/City</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_village_name; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">Village/City</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->permanent_address_village_name : $model->permanent_address_village_name; ?> </span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">PIN</span>
                                <span class="f-c__review-section__list--detail"><?= $model->present_address_pincode; ?></span>
                            </li>
                            <li>
                                <span class="f-c__review-section__list--field">PIN</span>
                                <span class="f-c__review-section__list--detail"><?= ($model->same_as_present_address) ? $model->present_address_pincode : $model->permanent_address_pincode; ?> </span>
                            </li>
                        </ul>
                    </div>
                    <div class="f-c__review-section w-100">
                        <div class="f-c__review-section--title"><span class="text">Qualifications</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/qualification-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <?= $this->render('partials/_qualification-list.php', ['qualifications' => $qualifications, 'guid' => $guid, 'class' => 'hide']); ?>
                    </div>
                    <div class="f-c__review-section w-100">
                        <div class="f-c__review-section--title"><span class="text">Employments</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/employment-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
                        <ul class="f-c__review-section__list">
                            <li class="third">
                                <span class="f-c__review-section__list--field">Are You Employed</span>
                                <span class="f-c__review-section__list--detail"><?= !empty($model->is_employed) ? 'Yes' : 'No'; ?></span>
                            </li>
                        </ul>
                        <?php if (!empty($model->is_employed)): ?>
                            <?= $this->render('partials/_employment-list.php', ['employments' => $employments, 'guid' => $guid, 'class' => 'hide']); ?>
                        <?php endif; ?>
                    </div>

                    <div class="f-c__review-section">
                        <?php echo $this->render('partials/_criteria-list.php', ['model' => $model, 'guid' => $guid]); ?>
                    </div>
                    <div class="f-c__review-section">
                        <div class="f-c__review-section--title"><span class="text">Fee Details</span> </div>
                        <ul class="f-c__review-section__list">
                            <li>
                                <span class="f-c__review-section__list--field">Total Amount</span>
                                <span class="f-c__review-section__list--detail"><?= 'Rs.' . $model->fee_amount; ?></span>
                            </li>
                        </ul>
                    </div>
                    <?php if (!$isPaid['feeStatus']): ?>
                        <?= $this->render('partials/_review-form', ['model' => $reviewFormModel, 'form' => $form, 'classifiedId' => $model->classifiedId]); ?>
                        <?php
                        if ($applicantPostModel['eservice_tabs'] == common\models\ApplicantPost::ESERVICE_TAB_QUALIFICATION_VALUE && $parentApplicantPost == common\models\ApplicantPost::QUALIFICATION_ESERVICE_LIMIT) {
                            echo '<input type="hidden" id="guid" name="feeId" value="' . $isPaid['feeId'] . '">';
                            echo '<input type="hidden" id="module" name="appModule" value="' . common\models\ApplicantFee::MODULE_ESERVICE . '">';
                        } else {
                            echo $this->render('partials/_payment-details', ['isPaid' => $isPaid, 'model' => $model]);
                        }
                        ?>
                    <?php else: ?>
                        <?= $this->render('partials/_exam-centre.php', ['model' => $reviewFormModel]); ?>
                        <?= $this->render('partials/_transaction-details.php', ['model' => $model]); ?>
                    <?php endif; ?>

                    <div class="payment__declaration hide">
                        <?= Yii::$app->params['payment.declaration']; ?>
                    </div>
                    <div class="f-c__review-section">
                        <div class="u-flexed u-justify-btw mt-4"> 
                            <a href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/criteria-details'], $qr)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                            <?php if ($applicantPostModel['eservice_tabs'] == common\models\ApplicantPost::ESERVICE_TAB_QUALIFICATION_VALUE && $parentApplicantPost == common\models\ApplicantPost::QUALIFICATION_ESERVICE_LIMIT): ?>
                                <?= Html::submitButton('Submit', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button']) ?>
                                <?php
                            elseif (!$isPaid['feeStatus']):
                                if ($model->is_eservice) {
                                    if (isset($applicantPostModel['eservice_tabs']) && $applicantPostModel['eservice_tabs'] != common\models\ApplicantPost::ESERVICE_TAB_INITAL_VALUE) {
                                        echo Html::submitButton('Pay Now', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'payNow']);
                                    }
                                } else if (common\models\MstClassified::isPaymentDateEnable($model->classifiedId)):
                                    ?>
                                    <?= Html::submitButton('Pay Now', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button', 'id' => 'payNow']) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>