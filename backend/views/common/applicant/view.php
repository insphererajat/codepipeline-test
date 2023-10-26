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
use components\Helper;

$this->title = 'View Profile';
$this->params['bodyClass'] = 'frame__body';
$ageCalculateDate = common\models\MstClassified::AGE_CALCULATE_DATE;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?= Html::csrfMetaTags() ?>
        <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/css/reviewdata.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>">
        <title><?= Html::encode($this->title) ?> | <?= \Yii::$app->params['appName'] ?></title>
    </head>
    <body>

        <div class="c-reviewdatamain">
            <!--Header section start-->
            <div class="c-reviewdatamain__header">
                <div class="logowrap">
                    <img align="center" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda.png" />
                </div> 
                <div class="c-reviewdatamain__header-content">
                    <h2><?= \Yii::$app->params['appName'] ?></h2>
                    <!--<h3>of Himachal Pradesh</h3>-->
                    <p>Online Application Portal</p>
                </div>
            </div>
            <!--Header section end-->

            <!--Section Document-->
            <?php
            if (!empty($applicantDocumentModel)):
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
                ?>
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
            <?php endif; ?>
            <!--Section Document-->

            <!--Section  Identity Details detail-->
            <?php if(!empty($applicantDetailModel)): ?>
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
                            <span class="valuedata"> : <?= \common\models\MstListType::selectTypeList($applicantDetailModel['is_aadhaar_card_holder']); ?></span>
                        </div>
                        <div class="column">
                            <span class="label">Identity Certificate No </span>
                            <span class="valuedata"> : <?= $applicantDetailModel['identity_certificate_no']; ?></span>
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
            <?php endif; ?>
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
                    <?php if(!empty($applicantDetailModel)): ?>
                    <div class="column">
                        <span class="label">Date of Birth </span>
                        <span class="valuedata"> :  <?= Helper::displayDate($applicantDetailModel['date_of_birth']); ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Age <strong class="note">(As 01/07/2020(21 years to 58 years change in calender))</strong> </span>
                        <span class="valuedata"> :  <?= !empty($applicantDetailModel['date_of_birth']) ? date_diff(date_create($applicantDetailModel['date_of_birth']), date_create($ageCalculateDate))->y : ''; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Nationality  </span>
                        <span class="valuedata"> : <?= common\models\ApplicantDetail::getNationality($applicantDetailModel['nationality']); ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Father's Name  </span>
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
                        <span class="label">Birth District  </span>
                        <span class="valuedata"> : <?= !empty($applicantDetailModel['birth_district_code']) ? MstDistrict::getName($applicantDetailModel['birth_district_code']) : ''; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Birth Tehsil  </span>
                        <span class="valuedata">  <?php
                            if (!empty($applicantDetailModel['birth_tehsil_code'])):
                                echo MstTehsil::getName($applicantDetailModel['birth_tehsil_code']);
                            elseif (!empty($applicantDetailModel['birth_tehsil_name'])):
                                echo 'Other';
                            endif;
                            ?></span>
                    </div>
                    <?php if (!empty($applicantDetailModel['birth_tehsil_name'])): ?>
                        <div class="column">
                            <span class="label">Birth Tehsil Name  </span>
                            <span class="valuedata">  : <?= $applicantDetailModel['birth_tehsil_name']; ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="column">
                        <span class="label">Birth Village/City </span>
                        <span class="valuedata">  :  <?= $applicantDetailModel['birth_city']; ?></span>
                    </div>
                    
                    <div class="column">
                        <span class="label">Proof of Birth Certificate </span>
                        <span class="valuedata"> : <?= !empty($applicantDetailModel['birth_certificate_type']) ? ApplicantDetail::getBirthCertificate($applicantDetailModel['birth_certificate_type']) : ''; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Std Code </span>
                        <span class="valuedata">  :  <?= $applicantDetailModel['std_code']; ?></span>
                    </div>
                    <div class="column">
                        <span class="label">Phone no. with STD Code </span>
                        <span class="valuedata">  : <?= $applicantDetailModel['phone_no']; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Alternate Mobile </span>
                        <span class="valuedata">  : <?= $applicantDetailModel['alternate_mobile']; ?></span>
                    </div>

                    <div class="column">
                        <span class="label">Permanent Identification Mark on Body</span>
                        <span class="valuedata">  :  <?= $applicantDetailModel['identification_mark1']; ?></span>
                    </div>
                    
                    <div class="column">
                        <span class="label">Permanent Identification Mark2 on Body</span>
                        <span class="valuedata">  :  <?= $applicantDetailModel['identification_mark2']; ?></span>
                    </div>

                    <div class="column full">
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
                    <?php endif; ?>
                    
                </div>
            </section>
            <!--Section  Personal Information detail-->

            <!--Section  Domicile & Disability Details detail-->
            <?php if(!empty($applicantDetailModel)): ?>
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Domicile & Disability Details</div>
                <div class="reviewrow longlabeltext">

                    <div class="column">
                        <span class="label"><?= Yii::t('app', 'is_domiciled') ?></span>
                        <span class="valuedata">  :  <?= isset($applicantDetailModel['is_domiciled']) && $applicantDetailModel['is_domiciled'] ? 'YES' : 'NO'; ?></span>
                    </div>
                    <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label"><?= Yii::t('app', 'domicile_no') ?></span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['domicile_no'] != null) ? $applicantDetailModel['domicile_no'] : ''; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label"><?= Yii::t('app', 'domicile_issue_district') ?></span>
                            <span class="valuedata">  :  <?= ($applicantDetailModel['domicile_issue_district'] != null) ? MstDistrict::getName($applicantDetailModel['domicile_issue_district']) : ''; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
                        <div class="column">
                            <span class="label"><?= Yii::t('app', 'domicile_issue_date') ?></span>
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
            <?php endif; ?>
            
            <!--Section  Address Details-->
            <?php
            if (!empty($applicantPermanentAddressModel)):
                $permanentAddressState = MstState::findByCode($applicantPermanentAddressModel['state_code'], ['selectCols' => ['name']]);
                $permanentAddressDistrict = MstDistrict::findByCode($applicantPermanentAddressModel['district_code'], ['selectCols' => ['name']]);

                if (!$applicantPostModel['same_as_present_address']) {
                    $correspondenceAddressState = MstState::findByCode($applicantCorrespondenceAddressModel['state_code'], ['selectCols' => ['name']]);
                    $correspondenceAddressDistrict = MstDistrict::findByCode($applicantCorrespondenceAddressModel['district_code'], ['selectCols' => ['name']]);
                }
                ?>
                <section class="c-reviewdatamain__sectionwrap">
                    <div class="sectionhead">Address Details</div>
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
                                <li>
                                    <span class="label">Nearest Police Station</span>
                                    <span class="valuedata">  : <?= $applicantPermanentAddressModel['nearest_police_station']; ?></span>
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
                                <li>
                                    <span class="label">Nearest Police Station</span>
                                    <span class="valuedata">  : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['nearest_police_station'] : $applicantCorrespondenceAddressModel['nearest_police_station']; ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
            <!--Section  Address Details-->
            <!--Section  Other Details-->
            <?php
            if(!empty($applicantDetailModel)):
            $employmentRegistrationOffice = '';
            if ($applicantDetailModel['is_employment_registered']) {
                if (!empty($applicantDetailModel['employment_registration_office_id'])) {
                    $employmentRegistrationOffice = \common\models\MstListType::findById($applicantDetailModel['employment_registration_office_id'], ['selectCols' => ['name']]);
                }
            }
            ?>
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Other Details</div>
                <div class="reviewrow longlabeltext">
                    <div class="column">
                        <span class="label">Are You Ex-Army Person ?</span>
                        <span class="valuedata">  :  <?= !empty($applicantDetailModel['is_exserviceman']) ? 'Yes' : 'No'; ?></span>
                    </div>
                    <?php if ($applicantDetailModel['is_dismissed_from_defence'] !== null): ?>
                        <div class="column">
                            <span class="label">Have You Been Dissmissed On Disciplinary Grounds From Defence Services?</span>
                            <span class="valuedata">  :  <?= !empty($applicantDetailModel['is_dismissed_from_defence']) ? 'Yes' : 'No'; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['is_voluntary_retirement'] !== null): ?>
                        <div class="column">
                            <span class="label">Did You Seek Voluntary Retirement?</span>
                            <span class="valuedata">  :  <?= !empty($applicantDetailModel['is_voluntary_retirement']) ? 'Yes' : 'No'; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($applicantDetailModel['is_relieved_on_medical'] !== null): ?>
                        <div class="column">
                            <span class="label">Have You Been Relieved On Medical Grounds From Defence Services?</span>
                            <span class="valuedata">  :  <?= !empty($applicantDetailModel['is_relieved_on_medical']) ? 'Yes' : 'No'; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <!--Section  Other Details-->
            <!--Section  Black List/ Declaration-->
            <section class="c-reviewdatamain__sectionwrap">
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

            <?php endif; ?>
            <!--Section  Other Detail of Candidate-->

            <!--Section  qualification-->
            <?php if (isset($applicantQualificationModel) && !empty($applicantQualificationModel)): ?>
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
                        ?>
                    </table>
                </div>
            </section>
            <?php endif; ?>
            <!--Section  qualification-->

            <!--Section  Employments-->
            <?php if(!empty($applicantEmploymentModel)): ?>
            <section class="c-reviewdatamain__sectionwrap">
                <div class="sectionhead">Employments</div>
                <div class="tablewrap">
                    <table>
                        <?php
                        $isEmployed = !empty($applicantDetailModel['is_employed']) ? 'Yes' : 'No';
                        echo "<tr><td>Are You Employed</td><td colspan='4'>{$isEmployed}</td></tr>";
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
                                $endDate = (!empty($data['start_date'])) ? date('d-m-Y', strtotime($data['start_date'])) : '';

                                echo "<tr>";
                                echo "<td>{$employmentType}</td>";
                                echo "<td>{$experienceType}</td>";
                                echo "<td>{$emplyerType}</td>";
                                echo "<td>{$employmentNature}</td>";
                                echo "<td>" . $data['office_name'] . "</td>";
                                echo "<td>" . $data['designation'] . "</td>";
                                echo "<td>{$startDate}</td>";
                                echo "<td>{$endDate}</td>";
                                echo "</tr>";
                            endforeach;
                        endif;
                        ?>

                    </table>
                </div>
            </section>
            <?php endif; ?>
            <!--Section  Employments-->

            <div class="c-reviewdatamain__footer">
                <div class="c-reviewdatamain__footer-top">
                    Disclaimer : Contents published on this website are managed and maintained by <?= \Yii::$app->params['appName'] ?>. For any query regarding this website, Please contact Web Information Manager.
                </div>
                <div class="c-reviewdatamain__footer-bottom">
                    © <?= \Yii::$app->params['appName'] ?>, All Rights Reserved.
                </div>
            </div>


        </div>



    </body>
</html>






