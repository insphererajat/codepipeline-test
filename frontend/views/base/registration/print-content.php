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

$this->title = 'Review Registration Form';
$this->params['bodyClass'] = 'frame__body';
$this->registerJs("RegistrationV2Controller.createUpdate();");

$presentAddressState = MstState::findByCode($model->present_address_state_code, ['selectCols' => ['name']]);
$presentAddressDistrict = MstDistrict::findByCode($model->present_address_district_code, ['selectCols' => ['name']]);

if (!$model->same_as_present_address) {
    $permanentAddressState = MstState::findByCode($model->permanent_address_state_code, ['selectCols' => ['name']]);
    $permanentAddressDistrict = MstState::findByCode($model->permanent_address_district_code, ['selectCols' => ['name']]);
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

if ($model->is_employment_registered) {
    if (!empty($model->employment_registration_office_id)) {
        $employmentRegistrationOffice = \common\models\MstListType::findById($model->employment_registration_office_id, ['selectCols' => ['name']]);
    }
}

$photo = $signature = $thumb = [];

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
if (isset($model->thumb) && $model->thumb > 0) {

    $mediaModel = \common\models\Media::findById($model->thumb, ['selectCols' => ['id', 'guid', 'cdn_path', 'filename']]);
    if (!empty($mediaModel)) {
        $thumb['id'] = $mediaModel['id'];
        $thumb['guid'] = $mediaModel['guid'];
        $thumb['cdnPath'] = Yii::$app->amazons3->getPrivateMediaUrl($mediaModel['cdn_path']);
        $thumb['filename'] = $mediaModel['filename'];
    }
}

$ageCalculateDate = common\models\MstClassified::AGE_CALCULATE_DATE;
$params = \Yii::$app->request->queryParams;
if (isset($params['guid']) && !empty($params['guid'])) {
    //$referenceDate = common\models\MstClassified::findByGuid($params['guid'], ['selectCols' => ['']]);
    //$ageCalculateDate = 
}
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
                <td style="width:100px" valign="top" align="center"><img align="center" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda.png" class="signImg" /></td>
                <td style="width:540px;" valign="top" align="left"><div style=" width:100%; padding-top:7mm; line-height: 150%; text-align:center; font-size:11pt; color:#01293c;"> <span style="color:#01293c; font-size:15pt; font-weight:bold; "><?= \Yii::$app->params['appName'] ?></span><br/>
                        <strong></strong><br/>
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
    
    <!--<table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Documents</strong></th>
        </tr>

        <tr>
            <td  style=" width:100%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;"><img src="<?= isset($photo['cdnPath']) && !empty($photo['cdnPath']) ? $photo['cdnPath'] : ''; ?>" alt="image"></td>
        </tr>
    </table>-->


    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Identity Details</strong></th>
        </tr>
        <tr>
            <td  colspan="2" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Are you holding an Aadhar Card?</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= \common\models\MstListType::selectTypeList($model->is_aadhaar_card_holder); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if ($model->is_aadhaar_card_holder != frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Identity Type</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= ($model->identity_type_id != null) ? MstListType::getName($model->identity_type_id) : ''; ?></td>
                        </tr>
                    </table>
                </td>

                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Identity Certificate No</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->identity_certificate_no; ?></td>
                        </tr>
                    </table>
                </td>

            </tr>
        <?php else: ?>
            <tr>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Aadhar No</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->aadhaar_no; ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Name On Aadhar Card</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->name_on_aadhaar; ?></td>
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
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->name; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Mobile No. </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->mobile; ?></td>
                    </tr>
                </table></td>
        </tr>

        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Email Id</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= components\Helper::emailConversion($model->email); ?></td>
                    </tr>
                </table>
            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Date of Birth </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->date_of_birth; ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Age</strong> (<?= Yii::t('app', 'age.limit') ?>)</td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= !empty($model->date_of_birth) ? date_diff(date_create($model->date_of_birth), date_create($ageCalculateDate))->y : ''; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Nationality </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= common\models\ApplicantDetail::getNationality($model->nationality); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Are You Orphan?</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= MstListType::selectTypeList($model->is_orphan); ?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; border-top: solid 0.264583333mm #000000;"></td>
        </tr>
        <?php if (!empty($model->is_orphan)): ?>
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Orphan Name</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->orphan_name; ?></td>
                        </tr>
                    </table></td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Orphan District </strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($model->birth_district_code) ? MstDistrict::getName($model->birth_district_code) : ''; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Orphan Certificate No</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->orphan_certificate_no; ?></td>
                        </tr>
                    </table></td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Orphan Certificate Issue Date </strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->orphan_certificate_issue_date; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Orphan Authority</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->orphan_authority; ?></td>
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
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->father_name; ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Mother's Name</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->mother_name; ?></td>
                        </tr>
                    </table>
                </td>

            </tr> 
            <tr>
                <td  colspan="2" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Birth State/UT</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($model->birth_state_code) ? MstState::getName($model->birth_state_code) : ''; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>                
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Birth Tehsil</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?php
                                            if(!empty($model->birth_tehsil_code)):
                                                echo MstTehsil::getName($model->birth_tehsil_code);
                                            elseif(!empty($model->birth_tehsil_name)):
                                                echo 'Other';
                                            endif;
                                            ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <?php if (!empty($model->birth_tehsil_name)): ?>
                        <table width="100%" border="0" cellspacing="0" >
                            <tr>
                                <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Birth Tehsil Name</strong></td>
                                <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->birth_tehsil_name; ?></td>
                            </tr>
                        </table>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" >
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Birth Village/City</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->birth_city; ?></td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Std Code </strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->std_code; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Phone no. with STD Code</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->phone_no; ?></td>
                    </tr>
                </table>
            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Alternate Mobile </strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $model->alternate_mobile; ?></td>
                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Permanent Identification Mark on Body</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->identification_mark; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Present Residential Area/Domicle Residential Area?</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= !empty($model->permanent_residence_type) ? \common\models\ApplicantDetail::getPermanentresidenceType($model->permanent_residence_type) : ''; ?></td>
                    </tr>
                </table></td>

        </tr>


        <tr>
            <td  style="width: 50%; border-top: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Gender</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= $model->gender; ?></td>
                    </tr>
                </table></td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong> Marital Status</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> :  <?= common\models\ApplicantDetail::getMaritalStatus($model->marital_status); ?></td>
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
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Whether Domicile Of Uttarakhand</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= isset($model->is_domiciled) && $model->is_domiciled ? 'YES' : 'NO'; ?></td>
        </tr>
        <?php if ($model->is_domiciled == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Domicile (Sthai Niwas Praman Patra) Certificate No</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->domicile_no != null) ? $model->domicile_no : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->is_domiciled == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Domicile (Sthai Niwas Praman Patra) Issuing District</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->domicile_issue_district != null) ? MstDistrict::getName($model->domicile_issue_district) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->is_domiciled == frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Domicile (Sthai Niwas Praman Patra) Issuing Date</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->domicile_issue_date != null) ? $model->domicile_issue_date : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->is_domiciled != frontend\models\RegistrationForm::SELECT_TYPE_YES): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Have you Passed High School and Intermediate Examination from Uttarakhand</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_high_school_passed_from_uttarakhand) ? 'Yes' : 'No'; ?></td>
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
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Category</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->social_category_id != null) ? MstListType::getName($model->social_category_id) : ''; ?></td>
        </tr>

        <?php if (($model->social_category_id == MstListType::OBC)) { ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Do you belong Non-creamy Layer?</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->is_non_creamy_layer != null) ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Valid Upto</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->social_category_certificate_issue_date != null) ? $model->social_category_certificate_issue_date : ''; ?></td>
            </tr>
        <?php } ?>

        <?php if (in_array($model->social_category_id, [MstListType::SC, MstListType::ST, MstListType::EWS, MstListType::OBC])) { ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Number</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->social_category_certificate_no != null) ? $model->social_category_certificate_no : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Issuing District</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->social_category_certificate_district_code != null) ? MstDistrict::getName($model->social_category_certificate_district_code) : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Issuing Authority</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->social_category_certificate_issue_authority_id != null) ? MstListType::getName($model->social_category_certificate_issue_authority_id) : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate Issuing Date</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->social_category_certificate_issue_date != null) ? $model->social_category_certificate_issue_date : ''; ?></td>
            </tr>
        <?php } ?>
    </table>

    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Subcategory Reservation Details</strong></th>
        </tr>

        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Are you Specially Abled Person(PH/Divyang)?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->disability_id != null) ? MstListType::getName($model->disability_id) : ''; ?></td>
        </tr>

        <?php if (!empty($model->disability_id) && $model->disability_id != MstListType::NOT_APPLICABLE) { ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Percentage Of Handicap</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->disability_percentage != null) ? $model->disability_percentage : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">PH Certificate No</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= ($model->disability_certificate_no != null) ? $model->disability_certificate_no : ''; ?></td>
            </tr>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">PH Certificate Issuing Date</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"> : <?= ($model->disability_certificate_issue_date != null) ? $model->disability_certificate_issue_date : ''; ?></td>
            </tr>

        <?php } ?>

        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Are You Ex-Army Person ?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_exserviceman) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php if (!empty($model->is_dismissed_from_defence)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Have You Been Dissmissed On Disciplinary Grounds From Defence Services?</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_dismissed_from_defence) ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->is_voluntary_retirement)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Did You Seek Voluntary Retirement?</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_voluntary_retirement) ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->is_relieved_on_medical)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Have You Been Relieved On Medical Grounds From Defence Services?</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_relieved_on_medical) ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->is_dswro_registered)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Is Your Name Registered In Any District Solder Welfare And Rehabilitation Office Employment Exchange Located In UK State?</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_dswro_registered) ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->dswro_registration_no)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Enter Registration No</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->dswro_registration_no; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->dswro_office_name)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Office Name</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->dswro_office_name; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->dswro_registration_date)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Date of Registration</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->dswro_registration_date; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->discharge_certificate_no)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Discharge Certificate No</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->discharge_certificate_no; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->discharge_date)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Discharge Certificate date</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->discharge_date; ?></td>
            </tr>
        <?php endif; ?>

        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Are You Dependent Of Freedom Fighter?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= empty($model->is_dependent_freedom_fighter) ? 'No' : 'Yes'; ?></td>
        </tr>

        <?php if (!empty($model->freedom_fighter_name)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Name Of Freedom Fighter</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->freedom_fighter_name; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->freedom_fighter_relation)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Relation To Freedom Fighter</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->freedom_fighter_relation; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->freedom_fighter_certificate_no)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Certificate No</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->freedom_fighter_certificate_no; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->freedom_fighter_issue_date)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Date Of Issuing</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->freedom_fighter_issue_date; ?></td>
            </tr>
        <?php endif; ?>

    </table>


    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>

    <!--<div style="page-break-after:always; clear:both"></div>-->

    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th valign="top" colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Address Details</strong></th>
        </tr>
        <tr>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000; padding-left:0mm; padding-right:0mm;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td colspan="2" style="width:100%; text-align:center; font-size:10pt;"><strong>Permanent Address</strong></td>
                    </tr>
                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Flat / Room / Door / Block / House No.</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_house_no; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Name of Premises / Building</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_premises_name; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Road / Street / Lane / Post Office</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_street; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Area / Locality</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_area; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Landmark</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_landmark; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>State/UT</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $presentAddressState['name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>District</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $presentAddressDistrict['name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?php
                                if (!empty($model->present_address_tehsil_code)):
                                    echo MstTehsil::getName($model->present_address_tehsil_code);
                                elseif (!empty($model->present_address_tehsil_name)):
                                    echo 'Other';
                                endif;
                                ?></td>
                    </tr>
                    <?php if(($model->present_address_tehsil_name != null)): ?>
                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil Name</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_tehsil_name; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Village/City</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_village_name; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>PIN</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_pincode; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Nearest Police Station</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->present_address_nearest_police_station; ?></td>
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
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_house_no : $model->permanent_address_house_no; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Name of Premises / Building</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_premises_name : $model->permanent_address_premises_name; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Road / Street / Lane / Post Office</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_street : $model->permanent_address_street; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Area / Locality</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_area : $model->permanent_address_area; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Landmark</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_landmark : $model->permanent_address_landmark; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>State/UT</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $presentAddressState['name'] : $permanentAddressState['name']; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>District</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $presentAddressDistrict['name'] : $permanentAddressDistrict['name']; ?> </td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?php
                                    if (!empty($model->permanent_address_tehsil_code)):
                                        echo MstTehsil::getName($model->permanent_address_tehsil_code);
                                    elseif (!empty($model->permanent_address_tehsil_name)):
                                        echo 'Other';
                                    endif;
                                    ?></td>
                    </tr>
                    
                    <?php if (($model->permanent_address_tehsil_name != null)): ?>
                        <tr>
                            <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Tehsil Name</strong></td>
                            <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= $model->permanent_address_tehsil_name; ?></td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Village/City</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_village_name : $model->permanent_address_village_name; ?></td>
                    </tr>

                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>PIN</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->present_address_pincode : $model->permanent_address_pincode; ?></td>
                    </tr>


                    <tr>
                        <td  valign="top" style="width:40%; border-top: solid 0.264583333mm #000000;"><strong>Nearest Police Station</strong></td>
                        <td valign="top" style="width:60%; border-top: solid 0.264583333mm #000000;"> : <?= ($model->same_as_present_address) ? $model->permanent_address_nearest_police_station : $model->permanent_address_nearest_police_station; ?></td>
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
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Is Your Name Registered In Any District Employment Office Located In Uk State?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_employment_registered) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php if (!empty($model->employment_registration_no)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Employment Exchange Registration No.</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->employment_registration_no; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($employmentRegistrationOffice)): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Employment Office District</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $employmentRegistrationOffice['name']; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($model->employment_registration_date)): ?>    
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Date Of Registration/Reregistration</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->employment_registration_date; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->employment_registration_valid_upto_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Validity of Registration upto</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->employment_registration_valid_upto_date; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->have_ncc_nss != null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Have you at least two year experience of Territorial Army?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->have_ncc_nss) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->have_served_territorial_army !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Minimum 2 Years Of Service In Territorial Army?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->have_served_territorial_army) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->is_ncc_b_certificate !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NCC B Certificate</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_ncc_b_certificate) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->ncc_b_certificate_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NCC B Certificate Acquiring Date</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->ncc_b_certificate_date; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->is_ncc_c_certificate !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NCC C Certificate</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_ncc_c_certificate) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->ncc_c_certificate_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NCC C Certificate Acquiring Date</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->ncc_c_certificate_date; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->is_nss_b_certificate !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NSS B Certificate</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_nss_b_certificate) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->nss_b_certificate_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NSS B Certificate Acquiring Date</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->nss_b_certificate_date; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->is_nss_c_certificate !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NSS C Certificate</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_nss_c_certificate) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->nss_c_certificate_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">NSS C Certificate Acquiring Date</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->nss_c_certificate_date; ?></td>
        </tr>
        <?php endif; ?>

    </table>
    
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Black List/ Declaration</strong></th>
        </tr>
        
        <?php if ($model->is_criminal_case !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Is There Any Criminal Case Pending Against You.?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_criminal_case) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->is_criminal_proceed_complete !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Are Criminal Proceeding Completed.?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_criminal_proceed_complete) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->criminal_sentenance_data != null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Details Of Sentence.?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->criminal_sentenance_data; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->is_debarred !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Whether Debarded or Black listed for examination by UPSC/SSC/State PSC/Board etc?</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->is_debarred) ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->debarred_from_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">From Date</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->debarred_from_date; ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($model->debarred_to_date !== null): ?>
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">To Date</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= $model->debarred_to_date; ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Other Detail of Candidate</strong></th>
        </tr>

        <?php if ($model->residence_place !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Place Of Residence</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->residence_place) ? ApplicantDetail::getResidencePlace($model->residence_place) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->high_class_schooling_place !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Place Of 10th Class Schooling</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->high_class_schooling_place) ? ApplicantDetail::getResidencePlace($model->high_class_schooling_place) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->qualifying_examination !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Type Of Institution Of Class 10th/Qualifying Examination</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->qualifying_examination) ? ApplicantDetail::getQualifyingExamination($model->qualifying_examination) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->father_qualification_id !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Father's Qualification</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->father_qualification_id) ? MstListType::getName($model->father_qualification_id) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->father_occupation_id !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Father's Occupation</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->father_occupation_id) ? MstListType::getName($model->father_occupation_id) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->mother_qualification_id !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Mother's Qualification</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->mother_qualification_id) ? MstListType::getName($model->mother_qualification_id) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->mother_occupation_id !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Mother's Occupation</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->mother_occupation_id) ? MstListType::getName($model->mother_occupation_id) : ''; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($model->family_annual_income !== null): ?>
            <tr>
                <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Family Annual Income</td>
                <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= !empty($model->family_annual_income) ? ApplicantDetail::getAnnualIncome($model->family_annual_income) : ''; ?></td>
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
            <td  style=" width:14%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Year</td>
            <td  style="width:15%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;">Name of <br/>Course</td>
            <td  style=" width:14%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Subjects</td>
            <td  style=" width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Board/University</td>
            <td  style=" width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Result<br/>Status</td>
            <td  style=" width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Percentage</td>
        </tr>
        <?php
        if (isset($qualifications) && !empty($qualifications)):
            foreach ($qualifications as $key => $qualification):
            $subjects = \common\models\ApplicantQualificationSubject::getAllSubjectsByApplicantQualificationId($qualification->id);
            $board = (isset($qualification->board_university)) ? $qualification->boardUniversity->name : '';
            $result = (!empty($qualification->result_status)) ? 'PASSED' : '';
            $course =(isset($qualification->qualification_degree_id)) ? $qualification->qualificationDegree->name : ''; 
            echo "<tr>";
            echo "<td style='width:15%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$qualification->qualificationType->name}</td>";
            echo "<td style='width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$course}</td>";
            echo "<td  style='width:15%; border-top: solid 0.264583333mm #000000; font-size:9pt;  border-left: solid 0.264583333mm #000000;'>{$qualification->year}</td>";
            echo "<td  style='width:14%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>{$subjects}</td>";
            echo "<td  style='width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$board}</td>";
            echo "<td  style='width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$result}</td>";            
            echo "<td  style='width:12%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$qualification->percentage}</td>";
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
        $isEmployed = !empty($model->is_employed) ? 'Yes' : 'No';
        echo "<tr><td style='width:50%; border-top: solid 0.264583333mm #000000; font-size:9pt;' colspan='4'>Are You Employed</td><td colspan='4' style='width:50%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$isEmployed}</td></tr>";
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
        if (isset($employments) && !empty($employments)):
            foreach ($employments as $key => $data):
            $employmentType = (!empty($data->employment_type_id)) ? \common\models\MstListType::getName($data->employment_type_id) : '';
            $experienceType = (isset($data->experience_type_id) && $data->experience_type_id > 0) ? $data->experienceType->name : '';
            $emplyerType = (isset($data->employer_type)) ? $data->employerType->name : '';
            $employmentNature = (!empty($data->employment_nature_id)) ? \common\models\MstListType::getName($data->employment_nature_id) : '';
            $startDate = (!empty($data->start_date)) ? date('d-m-Y', strtotime($data->start_date)) : '';
            $endDate = (!empty($data->start_date)) ? date('d-m-Y', strtotime($data->start_date)) : '';
            
            echo "<tr>";
            echo "<td style='width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$employmentType}</td>";
            echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$experienceType}</td>";
            echo "<td  style='width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt;  border-left: solid 0.264583333mm #000000;'>{$emplyerType}</td>";
            echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>{$employmentNature}</td>";
            echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$data->office_name}</td>";
            echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$data->designation}</td>";
            echo "<td  style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$startDate}</td>";            
            echo "<td  style='width:9%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$endDate}</td>";
            echo "</tr>";
        endforeach;
        endif;
        ?>

    </table>

    <!--<div style="page-break-after:always; clear:both"></div>-->
    <table cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
        </tr>
    </table>
    
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Fee Details</strong></th>
        </tr>
        
        <tr>
            <td align="left" style=" width:80%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Total Amount</td>
            <td align="center" style="width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;"><?= 'Rs.'. $model->fee_amount; ?></td>
        </tr>
    </table>
    
    <table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Date/Place/Exam Centre Preferences</strong></th>
        </tr>
        <tr>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:60%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Date</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= date('d-m-Y', strtotime($reviewFormModel->date)); ?></td>
                    </tr>
                </table>

            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:60%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Place</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= $reviewFormModel->place; ?></td>
                    </tr>
                </table>

            </td>
        </tr>
        
        <tr>
            <td valign="top" style="width: 50%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:60%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Exam Centre Preference 1</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($reviewFormModel->preference1) ? MstDistrict::getName($reviewFormModel->preference1) : ''; ?></td>
                    </tr>
                </table>

            </td>
            <td valign="top" style="width: 50%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" style="width:60%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Exam Centre Preference 2</strong></td>
                        <td valign="top" style="width:40%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($reviewFormModel->preference2) ? MstDistrict::getName($reviewFormModel->preference2) : ''; ?></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    
    <?php if($isPaid['feeStatus']): ?>
        <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
            <tr>
                <th colspan="8" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Transaction Details</strong></th>
            </tr>

            <tr>
                <td  style=" width:6%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">#</td>
                <td  style=" width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Transaction Id</td>
                <td  style="width:13%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold; border-left: solid 0.264583333mm #000000;">Gateway Id</td>
                <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Gateway Type</td>
                <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Amount</td>
                <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Status</td>
                <td  style=" width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Is Consumed</td>
                <td  style=" width:9%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Date</td>
            </tr>

            <?php
            $transactionModels = Transaction::findByApplicantId($model->id, [
                        'resultCount' => common\models\caching\ModelCache::RETURN_ALL
            ]);

            foreach ($transactionModels as $key => $transactionModel):
                $isConsume = !empty($transactionModel['is_consumed']) ? 'Yes' : 'No';
                echo "<tr>";
                echo "<td style='width:7%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>" . ($key + 1) . "</td>";
                echo "<td style='width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transactionModel['transaction_id'] . "</td>";
                echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transactionModel['gateway_id'] . "</td>";
                echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transactionModel['type'] . "</td>";
                echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transactionModel['amount'] . "</td>";
                echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $transactionModel['status'] . "</td>";
                echo "<td style='width:13%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . $isConsume . "</td>";
                echo "<td style='width:9%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>" . date('d-m-Y H:i:s', $transactionModel['modified_on']) . "</td>";
                echo "</tr>";
            endforeach;
            ?>

        </table>
    <?php endif; ?>
</page>
