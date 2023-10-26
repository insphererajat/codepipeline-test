<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\location\MstState;
use common\models\location\MstDistrict;
use common\models\location\MstTehsil;
use common\models\MstListType;
use common\models\ApplicantDetail;
use common\models\Transaction;
use common\models\caching\ModelCache;
use common\models\ApplicantDocument;
use common\models\MstConfiguration;
use components\Helper;

$this->title = 'Print Registration Form';
$this->params['bodyClass'] = 'frame__body';
$ageCalculateDate = common\models\MstClassified::AGE_CALCULATE_DATE;

$permanentAddressState = MstState::findByCode($applicantPermanentAddressModel['state_code'], ['selectCols' => ['name']]);
$permanentAddressDistrict = MstDistrict::findByCode($applicantPermanentAddressModel['district_code'], ['selectCols' => ['name']]);

if (!$applicantPostModel['same_as_present_address']) {
    $correspondenceAddressState = MstState::findByCode($applicantCorrespondenceAddressModel['state_code'], ['selectCols' => ['name']]);
    $correspondenceAddressDistrict = MstDistrict::findByCode($applicantCorrespondenceAddressModel['district_code'], ['selectCols' => ['name']]);
}

$photo = $signature = $birth = $caste = [];
foreach ($applicantDocumentModel as $document) {
    $mediaModel = \common\models\Media::findById($document['media_id'], ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
    if (empty($mediaModel)) {
        continue;
        ;
    }
    switch ($document['type']) {
        case ApplicantDocument::TYPE_USER_PHOTO:
            $photo['id'] = $mediaModel['id'];
            $photo['guid'] = $mediaModel['guid'];
            $photo['cdnPath'] = $mediaModel['cdn_path'];
            $photo['filename'] = $mediaModel['filename'];
            break;
        case ApplicantDocument::TYPE_USER_SIGNATURE:
            $signature['id'] = $mediaModel['id'];
            $signature['guid'] = $mediaModel['guid'];
            $signature['cdnPath'] = $mediaModel['cdn_path'];
            $signature['filename'] = $mediaModel['filename'];
            break;
        case ApplicantDocument::TYPE_USER_BIRTH_CERTIFICATE:
            $birth['id'] = $mediaModel['id'];
            $birth['guid'] = $mediaModel['guid'];
            $birth['cdnPath'] = $mediaModel['cdn_path'];
            $birth['filename'] = $mediaModel['filename'];
            break;
        case ApplicantDocument::TYPE_USER_CASTE_CERTIFICATE:
            $caste['id'] = $mediaModel['id'];
            $caste['guid'] = $mediaModel['guid'];
            $caste['cdnPath'] = $mediaModel['cdn_path'];
            $caste['filename'] = $mediaModel['filename'];
            break;
    }
}

$employmentRegistrationOffice = '';
if ($applicantDetailModel['is_employment_registered']) {
    if (!empty($applicantDetailModel['employment_registration_office_id'])) {
        $employmentRegistrationOffice = \common\models\MstListType::findById($applicantDetailModel['employment_registration_office_id'], ['selectCols' => ['name']]);
    }
}

$preference1 = $preference2 = $preference3 = "";
if (isset($applicantPostExamCentreModel) && !empty($applicantPostExamCentreModel)):
    foreach ($applicantPostExamCentreModel as $key => $applicantPostExamCentre):
        if ($applicantPostExamCentre['preference'] == common\models\ApplicantPostExamCentre::PREFERENCE_1):
            $preference1 = !empty($applicantPostExamCentre['district_code']) ? MstDistrict::getName($applicantPostExamCentre['district_code']) : '';
        endif;
        if ($applicantPostExamCentre['preference'] == common\models\ApplicantPostExamCentre::PREFERENCE_2):
            $preference2 = !empty($applicantPostExamCentre['district_code']) ? MstDistrict::getName($applicantPostExamCentre['district_code']) : '';
        endif;
        if ($applicantPostExamCentre['preference'] == common\models\ApplicantPostExamCentre::PREFERENCE_3):
            $preference3 = !empty($applicantPostExamCentre['district_code']) ? MstDistrict::getName($applicantPostExamCentre['district_code']) : '';
        endif;
    endforeach;
endif;

$identityType = MstListType::getListTypeDropdownByParentId(MstListType::IDENTITY_TYPE);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?= Html::csrfMetaTags() ?>
        <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/css/reviewdata.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>">
        <link rel="shortcut icon" href="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/favicon/favicon.ico" />
        <title><?= Html::encode($this->title) ?> | <?= \Yii::$app->params['appName'] ?></title>
    </head>
    <body>
        <div class="c-reviewdatamain">
            <?php if (!isset($first) || !$first): ?>
                <div class="c-buttonwrap">
                    <a href="<?= Url::toRoute(['/applicant/post']) ?>">Back to Home</a>
                    <!--<a target="_blank" href="<?= Url::toRoute(['/applicant/print', 'guid' => $guid]) ?>" target="_blank"> Print</a>-->
                    <a onclick="window.print();" href="javascript:;"> Print</a>
                </div>

                <!--Header section start-->
                <div class="c-reviewdatamain__header">
                    <div class="logowrap">
                        <img align="center" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda-1.png" />
                    </div> 
                    <div class="c-reviewdatamain__header-content">
                        <h2><?= \Yii::$app->params['appName'] ?></h2>
                        <!--<h3>of Himachal Pradesh</h3>-->
                        <p>Online Application Portal</p>
                    </div>
                </div>
                <!--Header section end-->
            <?php endif; ?>
            <!--Section first Post detail-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Post Details</div>
                <div class="reviewrow">
                    <div class="column">
                        <span class="label">Advertisement Name & No.</span>
                        <span class="valuedata"> : 
                            <?= common\models\MstClassified::getTitle($applicantPostModel['classified_id']); ?>
                        </span>
                    </div>
                </div>
                <div class="reviewrow">
                    <div class="column">
                        <span class="label">Applied Posts </span>
                        <span class="valuedata"> : 
                            <?php
                            $posts = '';
                            if (!empty($applicantPostDetailModel)) {
                                foreach ($applicantPostDetailModel as $applicantPostDetail) {
                                    $posts .= common\models\MstPost::getTitle($applicantPostDetail['post_id']);
                                }
                                echo rtrim($posts, ', ');
                            }
                            ?> 
                        </span>
                    </div>
                </div>
                <div class="reviewrow col2">
                    <?php if (!empty($applicantPostModel['application_no'])): ?>
                        <div class="column">
                            <span class="label">Application No. </span>
                            <span class="valuedata"> : <?= $applicantPostModel['application_no']; ?> </span>
                        </div>
                    <?php endif; ?>
                    <div class="column">
                        <span class="label">Application Status </span>
                        <span class="valuedata"> : <?= common\models\ApplicantPost::getApplicationStatus($applicantPostModel['application_status']); ?> </span>
                    </div>
                </div>
            </section>
            <!--Section first Post detail-->

            <!--Section Document-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Documents</div>
                <div class="reviewrow">
                    <div class="column">
                        <div class="documentwrap">
                            <div class="doumentimg">
                                <div class="docimgwrap"><img height="100" width="100" src="<?= isset($photo['cdnPath']) && !empty($photo['cdnPath']) ? common\models\Media::getEmbededCode($photo['cdnPath']) : ''; ?>" alt="image"></div>
                                <span>Photo</span>
                            </div>

                            <div class="doumentimg">
                                <div class="docimgwrap"><img height="100" width="100" src="<?= isset($signature['cdnPath']) && !empty($signature['cdnPath']) ? common\models\Media::getEmbededCode($signature['cdnPath']) : ''; ?>" alt="image"></div>
                                <span>Signature</span>
                            </div>

                            <div class="doumentimg">
                                <div class="docimgwrap"><img height="100" width="100" src="<?= isset($birth['cdnPath']) && !empty($birth['cdnPath']) ? common\models\Media::getEmbededCode($birth['cdnPath']) : ''; ?>" alt="image"></div>
                                <span>Proof Of Birth Certificate</span>
                            </div>
                            <?php if (!empty($caste)): ?>
                                <div class="doumentimg">
                                    <div class="docimgwrap"><img height="100" width="100" src="<?= isset($caste['cdnPath']) && !empty($caste['cdnPath']) ? common\models\Media::getEmbededCode($caste['cdnPath']) : ''; ?>" alt="image"></div>
                                    <span>Caste Certificate</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            <!--Section Document-->

            <!--Section  Identity Details detail-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Identity Details</div>
                <div class="reviewrow col2">
                    <div class="column full">
                        <span class="label">Are you holding an Aadhar Card? </span>
                        <span class="valuedata"> : <?= \common\models\MstListType::selectTypeList($applicantDetailModel['is_aadhaar_card_holder']); ?></span>
                    </div>
                    <?php if ($applicantDetailModel['is_aadhaar_card_holder'] != ModelCache::IS_DELETED_YES): ?>
                        <div class="column">
                            <span class="label">Identity Type </span>
                            <span class="valuedata"> : <?= isset($identityType[$applicantDetailModel['identity_type_id']]) ? $identityType[$applicantDetailModel['identity_type_id']] : ''; ?></span>
                        </div>
                        <div class="column">
                            <span class="label">Identity Certificate No </span>
                            <span class="valuedata"> : <?= $applicantDetailModel['identity_type_display']; ?></span>
                        </div>
                    <?php else: ?>

                        <div class="column">
                            <span class="label">Aadhar No </span>
                            <span class="valuedata"> : <?= $applicantDetailModel['aadhaar_no']; ?></span>
                        </div>

                        <div class="column">
                            <span class="label">Name On Aadhar Card </span>
                            <span class="valuedata"> : <?= $applicantDetailModel['name_on_aadhaar']; ?></span>
                        </div>

                    <?php endif; ?>

                </div>
            </section>
            <!--Section  Identity Details detail-->

            <!--Section  Personal Information detail-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Personal Information</div>
                <div class="reviewrow col2">
                    <div class="column">
                        <span class="label">Candidate's Name </span>
                        <span class="valuedata"> :  <?= $applicantModel['name']; ?></span>
                    </div>
                    <div class="column">
                        <span class="label">Mobile No. </span>
                        <span class="valuedata"> : <?= $applicantModel['mobile']; ?></span>
                    </div>
                    <div class="column">
                        <span class="label">Email Id </span>
                        <span class="valuedata"> :  <?= components\Helper::emailConversion($applicantModel['email']); ?></span>
                    </div>
                    <div class="column">
                        <span class="label">Date of Birth </span>
                        <span class="valuedata"> :  <?= Helper::displayDate($applicantDetailModel['date_of_birth']); ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Age <strong class="note"><?= Yii::t('app', 'age.limit') ?></strong> </span>
                        <span class="valuedata"> :  
                            <?php
                            if (!empty($applicantDetailModel['date_of_birth'])) {
                                $age = date_diff(date_create($applicantDetailModel['date_of_birth']), date_create($ageCalculateDate));
                                echo $age->y . ' Years, ' . $age->m . ' Months, ' . $age->d . ' Days (as per calculate- ' . date('d-m-Y', strtotime($ageCalculateDate)) . ')';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="column">
                        <span class="label">Nationality  </span>
                        <span class="valuedata"> : <?= common\models\ApplicantDetail::getNationality($applicantDetailModel['nationality']); ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Full Name Of Father/Husband</span>
                        <span class="valuedata"> :  <?= $applicantDetailModel['father_name']; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Mother's Name  </span>
                        <span class="valuedata"> :  <?= $applicantDetailModel['mother_name']; ?></span>
                    </div>
                    <div class="column">
                        <span class="label">Birth State/UT  </span>
                        <span class="valuedata"> : <?= !empty($applicantDetailModel['birth_state_code']) ? MstState::getName($applicantDetailModel['birth_state_code']) : ''; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Present Residential Area/Domicle Residential Area?</span>
                        <span class="valuedata">  :  <?= !empty($applicantDetailModel['permanent_residence_type']) ? \common\models\ApplicantDetail::getPermanentresidenceType($applicantDetailModel['permanent_residence_type']) : ''; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Gender</span>
                        <span class="valuedata">  :  <?= $applicantDetailModel['gender']; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Marital Status</span>
                        <span class="valuedata">  :  <?= !empty($applicantDetailModel['marital_status']) ? common\models\ApplicantDetail::getMaritalStatus($applicantDetailModel['marital_status']) : ''; ?></span>
                    </div>



                </div>
            </section>
            <!--Section  Personal Information detail-->

            <!--Section  Domicile & Disability Details detail-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Domicile & Disability Details</div>
                <div class="reviewrow longlabeltext">
                    <div class="column">
                        <span class="label"><?= Yii::t('app', 'is_domiciled') ?></span>
                        <span class="valuedata">  :  <?= isset($applicantDetailModel['is_domiciled']) && $applicantDetailModel['is_domiciled'] ? 'YES' : 'NO'; ?></span>
                    </div>
                    <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label">Domicile (Sthai Niwas Praman Patra) Certificate No</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['domicile_no'] != null) ? $applicantDetailModel['domicile_no'] : ''; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label">Domicile (Sthai Niwas Praman Patra) Issuing District</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['domicile_issue_district'] != null) ? MstDistrict::getName($applicantDetailModel['domicile_issue_district']) : ''; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label">Domicile (Sthai Niwas Praman Patra) Issuing Date</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['domicile_issue_date'] != null) ? Helper::displayDate($applicantDetailModel['domicile_issue_date']) : ''; ?></span>
                        </div>
                    <?php endif; ?>

                </div>
            </section>
            <!--Section  Domicile & Disability Details detail-->


            <!--Section  Category Reservation Details-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Category Reservation Details</div>
                <div class="reviewrow longlabeltext">
                    <div class="column">
                        <span class="label">Category</span>
                        <span class="valuedata">  :  <?= ($applicantDetailModel['social_category_id'] != null) ? MstListType::getName($applicantDetailModel['social_category_id']) : ''; ?></span>
                    </div>
                    <?php if (($applicantDetailModel['social_category_id'] == MstListType::OBC)) { ?>
                        <div class="column">
                            <span class="label">Do you belong Non-creamy Layer?</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['is_non_creamy_layer'] != null) ? 'Yes' : 'No'; ?></span>
                        </div>
                        <div class="column">
                            <span class="label">Valid Upto</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['social_category_certificate_valid_upto_date'] != null) ? Helper::displayDate($applicantDetailModel['social_category_certificate_valid_upto_date']) : ''; ?></span>
                        </div>
                    <?php } ?>
                    <?php if (in_array($applicantDetailModel['social_category_id'], [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) { ?>
                        <div class="column">
                            <span class="label">Certificate Number</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['social_category_certificate_no'] != null) ? $applicantDetailModel['social_category_certificate_no'] : ''; ?></span>
                        </div>

                        <div class="column">
                            <span class="label">Certificate Issuing District</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['social_category_certificate_district_code'] != null) ? MstDistrict::getName($applicantDetailModel['social_category_certificate_district_code']) : ''; ?></span>
                        </div>

                        <div class="column">
                            <span class="label">Certificate Issuing Authority</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['social_category_certificate_issue_authority_id'] != null) ? MstListType::getName($applicantDetailModel['social_category_certificate_issue_authority_id']) : ''; ?></span>
                        </div>

                        <div class="column">
                            <span class="label">Certificate Issuing Date</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['social_category_certificate_issue_date'] != null) ? Helper::displayDate($applicantDetailModel['social_category_certificate_issue_date']) : ''; ?></span>
                        </div>
                    <?php } ?>

                </div>
            </section>
            <!--Section  Subcategory Reservation Details-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Subcategory Reservation Details</div>
                <div class="reviewrow longlabeltext">
                    <div class="column">
                        <span class="label">Are you Specially Abled Person(PH/Divyang)?</span>
                        <span class="valuedata">  :  <?= ($applicantDetailModel['disability_id'] != null) ? MstListType::getName($applicantDetailModel['disability_id']) : ''; ?></span>
                    </div>
                    <?php if (!empty($applicantDetailModel['disability_id']) && $applicantDetailModel['disability_id'] != MstListType::NOT_APPLICABLE) { ?>
                        <div class="column">
                            <span class="label">Percentage Of Handicap</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['disability_percentage'] != null) ? $applicantDetailModel['disability_percentage'] : ''; ?></span>
                        </div>
                        <div class="column">
                            <span class="label">PH Certificate No</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['disability_certificate_no'] != null) ? $applicantDetailModel['disability_certificate_no'] : ''; ?></span>
                        </div>
                        <div class="column">
                            <span class="label">PH Certificate Issuing Date</span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['disability_certificate_issue_date'] != null) ? Helper::displayDate($applicantDetailModel['disability_certificate_issue_date']) : ''; ?></span>
                        </div>
                    <?php } ?>
                </div>
            </section>
            <!--Section  Subcategory Reservation Details-->
            <!--Section  Address Details-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Identity/Adress Proof (POI/POA)</div>
                <div class="reviewrow col2">
                    <div class="column">
                        <ul class="addresslist">
                            <li class="subhead"><strong>Permanent Address</strong></li>
                            <li>
                                <span class="label">Flat / Room / Door / Block / House No.</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['house_no']; ?></span>
                            </li>
                            <li>
                                <span class="label">Name of Premises / Building</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['premises_name']; ?></span>
                            </li>
                            <li>
                                <span class="label">Road / Street / Lane / Post Office</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['street']; ?></span>
                            </li>
                            <li>
                                <span class="label">Area / Locality</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['area']; ?></span>
                            </li>
                            <li>
                                <span class="label">Landmark</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['landmark']; ?></span>
                            </li>
                            <li>
                                <span class="label">State/UT</span>
                                <span class="valuedata">  : <?= $permanentAddressState['name']; ?></span>
                            </li>
                            <li>
                                <span class="label">District</span>
                                <span class="valuedata">  : <?= $permanentAddressDistrict['name']; ?></span>
                            </li>
                            <li>
                                <span class="label">Tehsil</span>
                                <span class="valuedata">  : <?php
                                    if (!empty($applicantPermanentAddressModel['tehsil_code'])):
                                        echo MstTehsil::getName($applicantPermanentAddressModel['tehsil_code']);
                                    elseif (!empty($applicantPermanentAddressModel['tehsil_name'])):
                                        echo 'Other';
                                    endif;
                                    ?></span>
                            </li>
                            <?php if (($applicantPermanentAddressModel['tehsil_name'] != null)): ?>
                                <li>
                                    <span class="label">Tehsil Name</span>
                                    <span class="valuedata">  : <?= $applicantPermanentAddressModel['tehsil_name']; ?></span>
                                </li>
                            <?php endif; ?>
                            <li>
                                <span class="label">Village/City</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['village_name']; ?></span>
                            </li>
                            <li>
                                <span class="label">PIN</span>
                                <span class="valuedata">  : <?= $applicantPermanentAddressModel['pincode']; ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="column">
                        <ul class="addresslist">
                            <li class="subhead"><strong>Correspondence Address</strong></li>
                            <li>
                                <span class="label">Flat / Room / Door / Block / House No.</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['house_no'] : $applicantCorrespondenceAddressModel['house_no']; ?></span>
                            </li>
                            <li>
                                <span class="label">Name of Premises / Building</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['premises_name'] : $applicantCorrespondenceAddressModel['premises_name']; ?></span>
                            </li>
                            <li>
                                <span class="label">Road / Street / Lane / Post Office</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['street'] : $applicantCorrespondenceAddressModel['street']; ?></span>
                            </li>
                            <li>
                                <span class="label">Area / Locality</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['area'] : $applicantCorrespondenceAddressModel['area']; ?></span>
                            </li>
                            <li>
                                <span class="label">Landmark</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['landmark'] : $applicantCorrespondenceAddressModel['landmark']; ?></span>
                            </li>
                            <li>
                                <span class="label">State/UT</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $permanentAddressState['name'] : $correspondenceAddressState['name']; ?></span>
                            </li>
                            <li>
                                <span class="label">District</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $permanentAddressDistrict['name'] : $correspondenceAddressDistrict['name']; ?></span>
                            </li>
                            <li>
                                <span class="label">Tehsil</span>
                                <span class="valuedata">  : <?php
                                    if (!empty($applicantCorrespondenceAddressModel['tehsil_code'])):
                                        echo MstTehsil::getName($applicantCorrespondenceAddressModel['tehsil_code']);
                                    elseif (!empty($applicantCorrespondenceAddressModel['tehsil_name'])):
                                        echo 'Other';
                                    endif;
                                    ?></span>
                            </li>
                            <?php if (($applicantCorrespondenceAddressModel['tehsil_name'] != null)): ?>
                                <li>
                                    <span class="label">Tehsil Name</span>
                                    <span class="valuedata">  : <?= $applicantCorrespondenceAddressModel['tehsil_name']; ?></span>
                                </li>
                            <?php endif; ?>
                            <li>
                                <span class="label">Village/City</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['village_name'] : $applicantCorrespondenceAddressModel['village_name']; ?></span>
                            </li>
                            <li>
                                <span class="label">PIN</span>
                                <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['pincode'] : $applicantCorrespondenceAddressModel['pincode']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <!--Section  Address Details-->
            <!--Section  Black List/ Declaration-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Other Details</div>
                <div class="reviewrow longlabeltext">
                    <div class="column">
                        <span class="label">Are You Ex-Army Person ?</span>
                        <span class="valuedata">  :  <?= !empty($applicantDetailModel['is_exserviceman']) ? 'Yes' : 'No'; ?></span>
                    </div>
                    <?php if ($applicantDetailModel['is_exserviceman'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label">Certificate No</span>
                            <span class="valuedata">  :  <?= $applicantDetailModel['exserviceman_qualification_certificate']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="sectionhead">Black List/ Declaration</div>
                <div class="reviewrow longlabeltext">
                    <?php if ($applicantDetailModel['is_debarred'] !== null): ?>
                        <div class="column">
                            <span class="label">Whether Debarded or Black listed for examination by UPSC/SSC/State PSC/Board etc?</span>
                            <span class="valuedata">  : <?= !empty($applicantDetailModel['is_debarred']) ? 'Yes' : 'No'; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['debarred_from_date'] !== null): ?> 
                        <div class="column">
                            <span class="label">From Date</span>
                            <span class="valuedata">  : <?= Helper::displayDate($applicantDetailModel['debarred_from_date']); ?></span>
                        </div> 
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['debarred_to_date'] !== null): ?>
                        <div class="column">
                            <span class="label">To Date</span>
                            <span class="valuedata">  : <?= Helper::displayDate($applicantDetailModel['debarred_to_date']); ?></span>
                        </div> 
                    <?php endif; ?>

                </div>
            </section>
            <!--Section   Black List/ Declaration-->

            <!--Section  qualification-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Qualifications</div>
                <div class="tablewrap">
                    <table>
                        <tr>
                            <th>Qualification Type</th>
                            <th>Year</th>
                            <th>Name of Course</th>
                            <th>Subjects</th>
                            <th>Board/University</th>
                            <th>Result Status</th>
                            <th>Percentage</th>
                        </tr>
                        <?php
                        if (isset($applicantQualificationModel) && !empty($applicantQualificationModel)):
                            foreach ($applicantQualificationModel as $key => $qualification):
                                $subjects = \common\models\ApplicantQualificationSubject::getAllSubjectsByApplicantQualificationId($qualification['id']);
                                $board = (isset($qualification['board_university'])) ? common\models\MstUniversity::getName($qualification['board_university']) : '';
                                $result = (!empty($qualification['result_status'])) ? 'PASSED' : '';
                                $course = (isset($qualification['qualification_degree_id'])) ? common\models\MstQualification::getName($qualification['qualification_degree_id']) : '';
                                $qualificationType = (isset($qualification['qualification_type_id'])) ? common\models\MstQualification::getName($qualification['qualification_type_id']) : '';
                                echo "<tr>";
                                echo "<td>{$qualificationType}</td>";
                                echo "<td>" . $qualification['qualification_year'] . "</td>";
                                echo "<td>{$course}</td>";
                                echo "<td>{$subjects}</td>";
                                echo "<td>{$board}</td>";
                                echo "<td>{$result}</td>";
                                echo "<td>" . $qualification['percentage'] . "</td>";
                                echo "</tr>";
                            endforeach;
                        endif;
                        ?>
                    </table>
                </div>
            </section>
            <!--Section  qualification-->

            <!--Section  Employments-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Employments</div>
                <div class="tablewrap">
                    <table>
                        <?php
                        $isEmployed = !empty($applicantDetailModel['is_employed']) ? 'Yes' : 'No';
                        echo "<tr><td colspan='2'>Are You Employed</td><td colspan='6'>{$isEmployed}</td></tr>";
                        if (isset($applicantEmploymentModel) && !empty($applicantEmploymentModel)):
                            ?>
                            <tr>
                                <th>Employment <br/>Type</th>
                                <th>Experience <br/>Type</th>
                                <th>Employer <br/>Type</th>
                                <th>Nature of <br/>Employment</th>
                                <th>Institution / Department / <br/>Organisation</th>
                                <th>Designation</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>

                            <?php
                            foreach ($applicantEmploymentModel as $key => $data):
                                $employmentType = (!empty($data['employment_type_id'])) ? MstListType::getName($data['employment_type_id']) : '';
                                $experienceType = (isset($data['experience_type_id']) && $data['experience_type_id'] > 0) ? MstListType::getName($data['experience_type_id']) : '';
                                $emplyerType = (isset($data['employer_type'])) ? MstListType::getName($data['employer_type']) : '';
                                $employmentNature = (!empty($data['employment_nature_id'])) ? MstListType::getName($data['employment_nature_id']) : '';
                                $startDate = (!empty($data['start_date'])) ? date('d-m-Y', strtotime($data['start_date'])) : '';
                                $endDate = (!empty($data['end_date'])) ? date('d-m-Y', strtotime($data['end_date'])) : '';
                                if ($data['employment_type_id'] == common\models\ApplicantEmployment::EMPLOYMENT_TYPE_PRESENT) {
                                    $endDate = 'Till Date';
                                }
                                echo "<tr>";
                                echo "<td>{$employmentType}</td>";
                                echo "<td>{$experienceType}</td>";
                                echo "<td>{$emplyerType}</td>";
                                echo "<td>{$employmentNature}</td>";
                                echo "<td>" . htmlentities($data['office_name']) . "</td>";
                                echo "<td>" . htmlentities($data['designation']) . "</td>";
                                echo "<td>{$startDate}</td>";
                                echo "<td>{$endDate}</td>";
                                echo "</tr>";
                            endforeach;
                        endif;
                        ?>

                    </table>
                </div>
            </section>
            <!--Section  Employments-->
            <!--Section LT Details-->

            <!--Section LT Details-->

            <!--Section Samaj kalyan Details-->
            <?= $this->render('_preview/_criteria-list', ['applicantDetailModel' => $applicantDetailModel, 'applicantCriteriaModel' => $applicantCriteriaModel, 'applicantPostModel' => $applicantPostModel]) ?>
            <!--Section Samaj kalyan Details-->

            <!--Section Exam Centre Preferences-->
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Exam Centre Preferences</div>
                <div class="reviewrow col2">
                    <div class="column">
                        <span class="label">Date</span>
                        <span class="valuedata">  :  <?= date('d-m-Y', strtotime($applicantPostModel['date'])); ?></span>
                    </div> 

                    <div class="column">
                        <span class="label">Place</span>
                        <span class="valuedata">  :   <?= $applicantPostModel['place']; ?></span>
                    </div>
                    <?php if (!empty($preference1)): ?>
                        <div class="column">
                            <span class="label">Exam Centre Preference 1</span>
                            <span class="valuedata">  :   <?= $preference1; ?></span>
                        </div> 
                    <?php endif; ?>
                    <?php if (!empty($preference2)): ?>
                        <div class="column">
                            <span class="label">Exam Centre Preference 2</span>
                            <span class="valuedata">  :   <?= $preference2; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($preference3)): ?>
                        <div class="column">
                            <span class="label">Exam Centre Preference 3</span>
                            <span class="valuedata">  :   <?= $preference3; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <!--Section Exam Centre Preferences-->

            <!--Section Fee Details-->
            <?php if (isset($applicantFeeModel) && !empty($applicantFeeModel)): ?>
                <section class="c-reviewdatamain__sectionwrap">
                    <div class="sectionhead">Fee Details</div>
                    <div class="reviewrow longlabeltext">
                        <div class="column">
                            <span class="label">Total Amount</span>
                            <span class="valuedata">  :  <?= 'Rs.' . $applicantFeeModel['fee_amount']; ?></span>
                        </div> 
                    </div>
                </section>
            <?php endif; ?>
            <!--Section Fee Details-->
            <?php if (isset($transactionModel) && !empty($transactionModel)): ?>
                <section class="c-reviewdatamain__sectionwrap">
                    <div class="sectionhead">Transaction Details</div>
                    <div class="tablewrap">
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Gateway</th>
                                <th>Transaction Id</th>
                                <th>Gateway Transaction Id</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                            <?php
                            foreach ($transactionModel as $key => $transaction):
                                if($transaction['type'] == Transaction::TYPE_CSC) {
                                    $mstConfigModel = MstConfiguration::findByType(MstConfiguration::TYPE_PAYMENT_CSC);
                                    if ($mstConfigModel != null) {
                                        $mstConfigModel = MstConfiguration::decryptValues($mstConfigModel);
                                        if (!empty($mstConfigModel['configuration_rule'])) {
                                            $rule = \yii\helpers\Json::decode($mstConfigModel['configuration_rule']);
                                            $transaction['amount'] += isset($rule['rule']['wallet']) ? $rule['rule']['wallet'] : 0;
                                        }
                                    }
                                }
                                echo "<tr>";
                                echo "<td>" . ($key + 1) . "</td>";
                                echo "<td>" . date('d-m-Y H:i:s', $transaction['modified_on']) . "</td>";
                                echo "<td>" . $transaction['type'] . "</td>";
                                echo "<td>" . $transaction['transaction_id'] . "</td>";
                                echo "<td>" . $transaction['gateway_id'] . "</td>";
                                echo "<td>" . $transaction['status'] . "</td>";
                                echo "<td>" . $transaction['amount'] . "</td>";
                                echo "</tr>";
                            endforeach;
                            ?>
                        </table>
                    </div>
                </section>
            <?php endif; ?>
            <?php if (!isset($last) || !$last): ?>
                <div class="c-reviewdatamain__footer">
                    <div class="c-reviewdatamain__footer-top">
                        Disclaimer : Contents published on this website are managed and maintained by <?= \Yii::$app->params['appName'] ?>. For any query regarding this website, Please contact Web Information Manager.
                    </div>
                    <div class="c-reviewdatamain__footer-bottom">
                        © <?= \Yii::$app->params['appName'] ?>, All Rights Reserved.
                    </div>
                </div>
            <?php endif; ?>

        </div>



    </body>
</html>
<div class="after"></div>