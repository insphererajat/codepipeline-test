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

$this->title = 'Review Registration Form';
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
$preference1 = $preference2 = "";
if (isset($applicantPostExamCentreModel) && !empty($applicantPostExamCentreModel)):
    foreach ($applicantPostExamCentreModel as $key => $applicantPostExamCentre):
        if ($applicantPostExamCentre['preference'] == common\models\ApplicantPostExamCentre::PREFERENCE_1):
            $preference1 = !empty($applicantPostExamCentre['district_code']) ? MstDistrict::getName($applicantPostExamCentre['district_code']) : '';
        endif;
        if ($applicantPostExamCentre['preference'] == common\models\ApplicantPostExamCentre::PREFERENCE_2):
            $preference2 = !empty($applicantPostExamCentre['district_code']) ? MstDistrict::getName($applicantPostExamCentre['district_code']) : '';
        endif;
    endforeach;
endif;

$identityType = MstListType::getListTypeDropdownByParentId(MstListType::IDENTITY_TYPE)
?>
<style type="text/css">
    @page {
        size: A4
    }
    * {
        margin: 0px;
        padding: 0px;
        color: #000;
    }
    td {
        padding-left: 1mm;
        padding-right: 1mm;
        padding-top: 1mm;
        padding-bottom: 1mm;
        font-size: 9pt;
        color: #000000;
        font-family: Arial, Helvetica, sans-serif;
    }
    th {
        padding-left: 1mm;
        padding-right: 1mm;
        padding-top: 2mm;
        padding-bottom: 2mm;
        background: #e7e7e7;
        font-family: Arial, Helvetica, sans-serif;
    }
    .signImg {
        width: 100px;
        height: auto;
    }
    @media print {
        @page {
            margin:0;
            size: A4
        }
        .signImg {
            width: 100px;
            height: auto;
        }
    }
</style>

<page backtop="30mm" backbottom="10mm" backleft="1mm" backright="1mm">
    <page_header>
        <table style="width:740px" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width:100px" valign="top" align="center"><img align="center" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/logo.jpg" class="signImg" /></td>
                <td style="width:540px;" valign="top" align="left"><div style=" width:100%; padding-top:7mm; line-height: 150%; text-align:center; font-size:11pt; color:#01293c;"> <span style="color:#01293c; font-size:15pt; font-weight:bold; "><?= \Yii::$app->params['appName'] ?></span><br/>
                        Online Application Portal
                    </div></td>
                <td style="width:100px" valign="top" align="center">&nbsp;</td>
            </tr>
        </table>

    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>

                <td style="width: 100%; text-align: center">
                    page [[page_cu]]/[[page_nb]]
                </td>

            </tr>
        </table>
    </page_footer>
    
    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Post Details</strong></th>
        </tr>
        <tr>
            <td  colspan="2" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:20%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Applied Posts</strong></td>
                        <td valign="top" style="width:80%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : 
                            <?php
                            $posts = '';
                            if (!empty($applicantPostDetailModel)) {
                                foreach ($applicantPostDetailModel as $applicantPostDetail) {
                                    $posts .= common\models\MstPost::getTitleForPdf($applicantPostDetail['post_id']) . '/' . $applicantPostModel['quota'] . ', ';
                                }
                                echo rtrim($posts, ', ');
                            }
                            ?> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Application No.</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantPostModel['application_no']) ? $applicantPostModel['application_no'] : ''; ?></td>
                    </tr>
                </table>
            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Application Status</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= common\models\ApplicantPost::getApplicationStatus($applicantPostModel['application_status']); ?></td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>
    
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="3" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Documents</strong></th>
        </tr>

        <tr>
            <td  style=" width:33%; text-align:center; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><img height="100" width="100" src="<?= isset($photo['cdnPath']) && !empty($photo['cdnPath']) ? common\models\Media::getEmbededCode($photo['cdnPath']) : ''; ?>" alt="image"> <br/><span>Photo</span></td>
            <td  style=" width:33%; text-align:center; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><img height="100" width="100" src="<?= isset($signature['cdnPath']) && !empty($signature['cdnPath']) ? common\models\Media::getEmbededCode($signature['cdnPath']) : ''; ?>" alt="image"> <br/><span>Signature</span></td>
            <td  style=" width:34%; text-align:center; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><img height="100" width="100" src="<?= isset($birth['cdnPath']) && !empty($birth['cdnPath']) ? common\models\Media::getEmbededCode($birth['cdnPath']) : ''; ?>" alt="image"> <br/><span>Proof Of Birth Certificate</span></td>
            <?php if (!empty($caste)): ?>
                <td  style=" width:34%; text-align:center; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><img height="100" width="100" src="<?= isset($caste['cdnPath']) && !empty($caste['cdnPath']) ? common\models\Media::getEmbededCode($caste['cdnPath']) : ''; ?>" alt="image"> <br/><span>Caste Certificate</span></td>
            <?php endif; ?>
        </tr>
    </table>
    
    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Identity Details</strong></th>
        </tr>
        <tr>
            <td  colspan="2" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Are you holding an Aadhar Card?</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= \common\models\MstListType::selectTypeList($applicantDetailModel['is_aadhaar_card_holder']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if ($applicantDetailModel['is_aadhaar_card_holder'] != ModelCache::IS_DELETED_YES): ?>
            <tr>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Identity Type</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= isset($identityType[$applicantDetailModel['identity_type_id']]) ? $identityType[$applicantDetailModel['identity_type_id']] : ''; ?></td>
                        </tr>
                    </table>
                </td>

                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Identity Certificate No</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantDetailModel['identity_certificate_no']; ?></td>
                        </tr>
                    </table>
                </td>

            </tr>
        <?php else: ?>
            <tr>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Aadhar No</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantDetailModel['aadhaar_no']; ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Name On Aadhar Card</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantDetailModel['name_on_aadhaar']; ?></td>
                        </tr>
                    </table>
                </td>

            </tr>
        <?php endif; ?>
    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>
    
    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Personal Information</strong></th>
        </tr>
        <tr>
            <td style="width: 50%; border-top: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Candidate's Name</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $applicantModel['name']; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Mobile No. </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantModel['mobile']; ?></td>
                    </tr>
                </table></td>
        </tr>

        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Email Id</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= components\Helper::emailConversion($applicantModel['email']); ?></td>
                    </tr>
                </table>
            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Date of Birth </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= Helper::displayDate($applicantDetailModel['date_of_birth']); ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Age</strong> (<?= Yii::t('app', 'age.limit') ?>)</td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= !empty($applicantDetailModel['date_of_birth']) ? date_diff(date_create($applicantDetailModel['date_of_birth']), date_create($ageCalculateDate))->y : ''; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Nationality </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= common\models\ApplicantDetail::getNationality($applicantDetailModel['nationality']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Are You Orphan?</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= MstListType::selectTypeList($applicantDetailModel['is_orphan']); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; border-top: solid 0.264583333mm #000000;"></td>
        </tr>
        <?php if (!empty($applicantDetailModel['is_orphan'])): ?>
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Orphan Name</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $applicantDetailModel['orphan_name']; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Orphan Certificate No</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantDetailModel['orphan_certificate_no']; ?></td>
                        </tr>
                    </table></td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Orphan Certificate Issue Date </strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= Helper::displayDate($applicantDetailModel['orphan_certificate_issue_date']); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Orphan Authority</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantDetailModel['orphan_authority']; ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; border-top: solid 0.264583333mm #000000;"></td>
            </tr>
        <?php else: ?>
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Father's Name </strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $applicantDetailModel['father_name']; ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Mother's Name</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $applicantDetailModel['mother_name']; ?></td>
                        </tr>
                    </table>
                </td>

            </tr> 
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Birth State/UT</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantDetailModel['birth_state_code']) ? MstState::getName($applicantDetailModel['birth_state_code']) : ''; ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Birth District</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantDetailModel['birth_district_code']) ? MstDistrict::getName($applicantDetailModel['birth_district_code']) : ''; ?></td>
                        </tr>
                    </table>
                </td>

            </tr>
        <?php endif; ?>


        <tr>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Present Residential Area/Domicle Residential Area?</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= !empty($applicantDetailModel['permanent_residence_type']) ? \common\models\ApplicantDetail::getPermanentresidenceType($applicantDetailModel['permanent_residence_type']) : ''; ?></td>
                    </tr>
                </table></td>

        </tr>


        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Gender</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $applicantDetailModel['gender']; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Marital Status</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= common\models\ApplicantDetail::getMaritalStatus($applicantDetailModel['marital_status']); ?></td>
                    </tr>
                </table></td>

        </tr>

    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>
    
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Domicile & Disability Details </strong></th>
        </tr>
        <tr>
            <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><?= Yii::t('app', 'is_domiciled') ?></td>
            <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= isset($applicantDetailModel['is_domiciled']) && $applicantDetailModel['is_domiciled'] ? 'YES' : 'NO'; ?></td>
        </tr>
        <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><?= Yii::t('app', 'domicile_no') ?></td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['domicile_no'] != null) ? $applicantDetailModel['domicile_no'] : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><?= Yii::t('app', 'domicile_issue_district') ?></td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['domicile_issue_district'] != null) ? MstDistrict::getName($applicantDetailModel['domicile_issue_district']) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($applicantDetailModel['is_domiciled'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><?= Yii::t('app', 'domicile_issue_date') ?></td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['domicile_issue_date'] != null) ? Helper::displayDate($applicantDetailModel['domicile_issue_date']) : ''; ?></td>
            </tr>
        <?php endif; ?>
    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>
    
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Category Reservation Details</strong></th>
        </tr>

        <tr>
            <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Category</td>
            <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['social_category_id'] != null) ? MstListType::getName($applicantDetailModel['social_category_id']) : ''; ?></td>
        </tr>

        <?php if (($applicantDetailModel['social_category_id'] == MstListType::OBC)) { ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Do you belong Non-creamy Layer?</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['is_non_creamy_layer'] != null) ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Valid Upto</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['social_category_certificate_valid_upto_date'] != null) ? Helper::displayDate($applicantDetailModel['social_category_certificate_valid_upto_date']) : ''; ?></td>
            </tr>
        <?php } ?>

        <?php if (in_array($applicantDetailModel['social_category_id'], [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) { ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Number</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['social_category_certificate_no'] != null) ? $applicantDetailModel['social_category_certificate_no'] : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Issuing District</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['social_category_certificate_district_code'] != null) ? MstDistrict::getName($applicantDetailModel['social_category_certificate_district_code']) : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Issuing Authority</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['social_category_certificate_issue_authority_id'] != null) ? MstListType::getName($applicantDetailModel['social_category_certificate_issue_authority_id']) : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Issuing Date</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($applicantDetailModel['social_category_certificate_issue_date'] != null) ? Helper::displayDate($applicantDetailModel['social_category_certificate_issue_date']) : ''; ?></td>
            </tr>
        <?php } ?>
    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>
    
    <!--<div style="page-break-after:always; clear:both"></div>-->

    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th valign="top" colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Identity/Adress Proof (POI/POA)</strong></th>
        </tr>
        <tr>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000; padding-left:0mm; padding-right:0mm;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td colspan="2" style="width:100%; text-align:center; font-size:10pt;"><strong>Permanent Address</strong></td>
                    </tr>
                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Flat / Room / Door / Block / House No.</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['house_no']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Name of Premises / Building</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['premises_name']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Road / Street / Lane / Post Office</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['street']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Area / Locality</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['area']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Landmark</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['landmark']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>State/UT</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $permanentAddressState['name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>District</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $permanentAddressDistrict['name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?php
                            if (!empty($applicantPermanentAddressModel['tehsil_code'])):
                                echo MstTehsil::getName($applicantPermanentAddressModel['tehsil_code']);
                            elseif (!empty($applicantPermanentAddressModel['tehsil_name'])):
                                echo 'Other';
                            endif;
                            ?></td>
                    </tr>
                    <?php if (($applicantPermanentAddressModel['tehsil_name'] != null)): ?>
                        <tr>
                            <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil Name</strong></td>
                            <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['tehsil_name']; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Village/City</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['village_name']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>PIN</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['pincode']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Nearest Police Station</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantPermanentAddressModel['nearest_police_station']; ?></td>
                    </tr>

                </table>

            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;padding-left:0mm; padding-right:0mm;">

                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td colspan="2" style="width:100%; text-align:center; font-size:10pt;"><strong>Correspondence Address</strong></td>
                    </tr>
                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Flat / Room / Door / Block / House No.</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['house_no'] : $applicantCorrespondenceAddressModel['house_no']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Name of Premises / Building</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['premises_name'] : $applicantCorrespondenceAddressModel['premises_name']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Road / Street / Lane / Post Office</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['street'] : $applicantCorrespondenceAddressModel['street']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Area / Locality</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['area'] : $applicantCorrespondenceAddressModel['area']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Landmark</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['landmark'] : $applicantCorrespondenceAddressModel['landmark']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>State/UT</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $permanentAddressState['name'] : $correspondenceAddressState['name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>District</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $permanentAddressDistrict['name'] : $correspondenceAddressDistrict['name']; ?> </td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?php
                            if (!empty($applicantCorrespondenceAddressModel['tehsil_code'])):
                                echo MstTehsil::getName($applicantCorrespondenceAddressModel['tehsil_code']);
                            elseif (!empty($applicantCorrespondenceAddressModel['tehsil_name'])):
                                echo 'Other';
                            endif;
                            ?></td>
                    </tr>

                    <?php if (($applicantCorrespondenceAddressModel['tehsil_name'] != null)): ?>
                        <tr>
                            <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil Name</strong></td>
                            <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $applicantCorrespondenceAddressModel['tehsil_name']; ?></td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Village/City</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['village_name'] : $applicantCorrespondenceAddressModel['village_name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>PIN</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['pincode'] : $applicantCorrespondenceAddressModel['pincode']; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Nearest Police Station</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($applicantPostModel['same_as_present_address']) ? $applicantPermanentAddressModel['nearest_police_station'] : $applicantCorrespondenceAddressModel['nearest_police_station']; ?></td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>

    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Other Details</strong></th>
        </tr>
        <tr>
            <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Are You Ex-Army Person ?</td>
            <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($applicantDetailModel['is_exserviceman']) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php if ($applicantDetailModel['is_exserviceman'] == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate No</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $applicantDetailModel['exserviceman_qualification_certificate']; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Black List/ Declaration</strong></th>
        </tr>
        <?php if ($applicantDetailModel['is_debarred'] !== null): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Whether Debarded or Black listed for examination by UPSC/SSC/State PSC/Board etc?</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($applicantDetailModel['is_debarred']) ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($applicantDetailModel['debarred_from_date'] !== null): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">From Date</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= Helper::displayDate($applicantDetailModel['debarred_from_date']); ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($applicantDetailModel['debarred_to_date'] !== null): ?>
            <tr>
                <td align="left" style=" width:70%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">To Date</td>
                <td align="center" style="width:30%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= Helper::displayDate($applicantDetailModel['debarred_to_date']); ?></td>
            </tr>
        <?php endif; ?>
    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>

    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="7" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Qualifications</strong></th>
        </tr>
        <tr>
            <td  style=" width:15%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Qualification <br/>Type</td>
            <td  style=" width:5%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Year</td>
            <td  style="width:17%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;">Name of <br/>Course</td>
            <td  style=" width:19%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Subjects</td>
            <td  style=" width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Board/University</td>
            <td  style=" width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Result<br/>Status</td>
            <td  style=" width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Percentage</td>
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
                echo "<td style='width:15%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$qualificationType}</td>";
                echo "<td  style='width:5%; border-top: solid 0.264583333mm #000000; font-size:9pt;  border-left: solid 0.264583333mm #000000;'>".$qualification['qualification_year']."</td>";
                echo "<td style='width:17%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$course}</td>";
                echo "<td  style='width:19%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>{$subjects}</td>";
                echo "<td  style='width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$board}</td>";
                echo "<td  style='width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$result}</td>";
                echo "<td  style='width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>".$qualification['percentage']."</td>";
                echo "</tr>";
            endforeach;
        endif;
        ?>

    </table>
    
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>

    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="8" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Employments</strong></th>
        </tr>
        <?php
        $isEmployed = !empty($applicantDetailModel['is_employed']) ? 'Yes' : 'No';
        echo "<tr><td style='width:50%; border-top: solid 0.264583333mm #000000; font-size:9pt;' colspan='4'>Are You Employed</td><td colspan='4' style='width:50%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$isEmployed}</td></tr>";
        if (isset($applicantEmploymentModel) && !empty($applicantEmploymentModel)):
        ?>
        <tr>
            <td  style=" width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Employment <br/>Type</td>
            <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Experience <br/>Type</td>
            <td  style=" width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;">Employer <br/>Type</td>
            <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Nature of <br/>Employment</td>
            <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Institution / Department / <br/>Organisation</td>
            <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Designation</td>
            <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Start Date</td>
            <td  style=" width:9%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">End Date</td>
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
                echo "<td style='width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$employmentType}</td>";
                echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$experienceType}</td>";
                echo "<td  style='width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt;  border-left: solid 0.264583333mm #000000;'>{$emplyerType}</td>";
                echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>{$employmentNature}</td>";
                echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>".$data['office_name']."</td>";
                echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>".$data['designation']."</td>";
                echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$startDate}</td>";
                echo "<td  style='width:9%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$endDate}</td>";
                echo "</tr>";
            endforeach;
        endif;
        ?>

    </table>
    
    <!--Section Samaj kalyan Details-->
    <?= $this->render('_print/_criteria-list', ['applicantDetailModel' => $applicantDetailModel, 'applicantLtModel' => $applicantLtModel, 'applicantLtDetailModel' => $applicantLtDetailModel]) ?>
    <!--Section Samaj kalyan Details-->
    
    
    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Exam Centre Preferences</strong></th>
        </tr>
        <tr>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:60%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Date</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= date('d-m-Y', strtotime($applicantPostModel['date'])); ?></td>
                    </tr>
                </table>

            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:60%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Place</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $applicantPostModel['place']; ?></td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:60%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Exam Centre Preference 1</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $preference1; ?></td>
                    </tr>
                </table>

            </td>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:60%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Exam Centre Preference 2</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $preference2; ?></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <?php if(isset($applicantFeeModel) && !empty($applicantFeeModel)): ?>
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Fee Details</strong></th>
        </tr>

        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Total Amount</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= 'Rs.' . $applicantFeeModel['fee_amount']; ?></td>
        </tr>
    </table>
    <?php endif; ?>
    <?php if (isset($transactionModel) && !empty($transactionModel)): ?>
        <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
            <tr>
                <th colspan="6" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Transaction Details</strong></th>
            </tr>

            <tr>
                <td  style=" width:10%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">#</td>
                <td  style=" width:18%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Date</td>
                <td  style="width:10%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;">Gateway</td>
                <td  style=" width:26%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Transaction Id</td>
                <td  style=" width:26%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Gateway Transaction Id</td>
                <td  style=" width:10%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Amount</td>
            </tr>

            <?php
            foreach ($transactionModel as $key => $transaction):
                echo "<tr>";
                echo "<td style='width:10%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>" . ($key + 1) . "</td>";
                echo "<td style='width:18%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . date('d-m-Y H:i:s', $transaction['modified_on']) . "</td>";
                echo "<td style='width:10%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transaction['type'] . "</td>";
                echo "<td style='width:26%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transaction['transaction_id'] . "</td>";
                echo "<td style='width:26%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transaction['gateway_id'] . "</td>";
                echo "<td style='width:10%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transaction['amount'] . "</td>";
                echo "</tr>";
            endforeach;
            ?>

        </table>
    <?php endif; ?>
</page>