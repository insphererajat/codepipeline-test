<?php

use common\models\caching\ModelCache;
use common\models\ExamCentreDetail;
use common\models\location\MstDistrict;
use common\models\Media;
use common\models\MstListType;

$examCentreDetail = ExamCentreDetail::findByClassifiedId($classifiedId, ['resultFormat' => ModelCache::RETURN_TYPE_OBJECT]);
$examCentre = $examCentreDetail->examCentre;
//echo '<pre>';print_r($examCentreDetail);die;
?>
<title>Attendance Sheet</title>
<link rel="stylesheet" type="text/css" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/print/css/print.css" />
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* CSS styling for before/after/avoid. */
.before {
    page-break-before: always;
}

.after {
    page-break-after: always;
}

.avoid {
    page-break-inside: avoid;
}
thead
{
    display: table-header-group;
}
tfoot
{
    display: table-footer-group;
}

.text-red {
    color: red;
}
table.datawithlable__v4 td.table-item1 {
 width:30% !important	
}

table.datawithlable__v4 td.table-item2 {
 width:70% !important		
}
table.datawithlable td {
     font-size: 8pt !important;	
}
table.dataview th {
	font-size: 9pt !important;
}
table.dataview td {
font-size: 11px !important;
line-height: 17px !important;	
}
table.dataview__v15 td img.student-img {
	width: 60px;
    height: auto;
}
table.header__elemv9 {
 margin-bottom:10px;	
}
@media print {
    @page {
        size: A4;
        /* DIN A4 standard, Europe */
        margin-left: 2mm;
        margin-right: 2mm;
        margin-top: 10mm;
        margin-bottom: 10mm;
    }

    table.dataview th.dataview-head span.edit-bt {
        display: none;
    }

    table.dataview__v15 td, th {
        font-size: 9pt;
        border-bottom: solid 0.264583333mm #4a4a4a;
        border-top: solid 0.264583333mm #4a4a4a;
        border-left: solid 0.264583333mm #4a4a4a;

        text-align: left;
    }

    table tr {
        page-break-inside: avoid;
        page-break-after: auto
    }

    table.dataview th.dataview-head span.edit-bt,
    .printbtwrap {
        display: none;
    }
}
</style>
<page orientation="portrait" format="A4" margin="0" padding="0">
    <table cellpadding="0" cellspacing="0" class="maintable maintable__v1 w200 marginauto printbtwrap">
        <tr>
            <td align="right" style="padding-top:10px; padding-bottom:15px;"><a onclick="window.print()" class="printbt"
                    href="javascript:;">PRINT</a></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" class="maintable  w200 marginauto">
        <tr>
            <td class="fullwidth">
                <table cellpadding="0" cellspacing="0" class="header__elemv9 w100">
                    <tr>
                        <td class="table-item1">
                            <table cellpadding="0" cellspacing="0" class="headercentertext__v3 w100">
                                <tr>
                                    <td class="topline"><u>HIGH COURT OF HIMACHAL PRADESH SHIMLA-171001</u></td>
                                </tr>
                                <tr>
                                    <td class="topline"><u>ATTENDANCE SHEET</u></td>
                                </tr>
                                <tr>
                                    <td class="middleline">(<u><strong>Attendance to be obtained by the Invigilation
                                                staff</strong></u>)</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="dataview dataview__v11 w100">
                    <tbody>
                        <tr>
                            <td class="table-item1">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v4">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1"><strong>NAME OF POST</strong></td>
                                            <td class="table-item2">:
                                                <strong class="text-red">HIGH COURT DRIVER’S<br /> &nbsp;&nbsp;ADVT.
                                                    NOTICE NO.<br /> &nbsp;&nbsp;HHC/ESTT.7(14)/94-IV-</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="table-item2">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v4">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1"><strong>NAME OF EXAM</strong></td>
                                            <td class="table-item2">:
                                                <strong class="text-red">WRITTEN<br />&nbsp; SCREENING<br />&nbsp;
                                                    TEST</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-item1">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v4">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1"><strong>EXAM DISTRICT</strong></td>
                                            <td class="table-item2">:
                                                <strong
                                                    class="text-red"><?= !empty($examCentre->district_code) ? strtoupper(MstDistrict::getName($examCentre->district_code)) : '' ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="table-item2">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v4">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1"><strong>DATE OF TEST</strong></td>
                                            <td class="table-item2">:
                                                <strong
                                                    class="text-red"><?= !empty($examCentreDetail->date) ? date('d.m.Y', strtotime($examCentreDetail->date)) : '' ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-item1">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v4">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1"><strong>EXAM. CENTRE</strong></td>
                                            <td class="table-item2">:
                                                <strong
                                                    class="text-red"><?= !empty($examCentre->name) ? strtoupper($examCentre->name) : '' ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="table-item2">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v4">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1"><strong>TIME OF TEST</strong></td>
                                            <td class="table-item2">:
                                                <strong
                                                    class="text-red"><?= !empty($examCentreDetail->examtime) ? strtoupper($examCentreDetail->examtime) : '' ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table cellpadding="0" cellspacing="0" class="minheight200">
                    <tr>
                        <td class="item1">

                            <table cellpadding="0" cellspacing="0" class="dataview dataview__v15">
                                <thead>
                                    <tr>
                                        <th class="table-item1">Sr. No.</th>
                                        <th class="table-item2">Candidate <br />Details</th>
                                        <th class="table-item3">Candidate’s <br />Photo</th>
                                        <th class="table-item4">Signature of <br />Candidate’s <br />(as Uploaded)</th>
                                        <th class="table-item5">OMR Sheet <br />No.</th>
                                        <th class="table-item6">Signature of <br />Candidate’s <br />(During test)</th>
                                        <th class="table-item7">Remarks, if any</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($records) && !empty($records)):
                                        $categories = MstListType::getListTypeDropdownByParentId(MstListType::SOCIAL_CATEGORY);
                                        foreach ($records as $key => $record):
                                         ?>
                                    <tr>
                                        <td class="table-item1 fw-bold"><?= ($key+1) ?></td>
                                        <td class="table-item2 left-text">
                                            Name: <?= $record['name'] ?> <br />
                                            Roll No: <?= $record['rollno'] ?> <br />
                                            Father's Name : <?= $record['father_name'] ?> <br />
                                            Category:
                                            <?= isset($categories[$record['social_category_id']]) ? $categories[$record['social_category_id']] : '' ?>
                                        </td>
                                        <td class="table-item3"><img
                                                src="<?= isset($record['photo']) && !empty($record['photo']) ? Media::getEmbededCode($record['photo']) : ''; ?>"
                                                class="student-img" alt="Signature Not Uploaded"></td>
                                        <td class="tab le-item4"><img
                                                src="<?= isset($record['signature']) && !empty($record['signature']) ? Media::getEmbededCode($record['signature']) : ''; ?>"
                                                class="student-sign" alt=""></td>
                                        <td class="table-item5"></td>
                                        <td class="table-item6"></td>
                                        <td class="table-item7"></td>
                                    </tr>
                                    <?php endforeach; endif;?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" class="dataview dataview__v11 w100">
                    <tbody>
                        <tr>
                            <td class="table-item1">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v13">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1">
                                                <div class="label">Signature of Invigilator</div>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="table-item2">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v13">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1">
                                                <div class="label">Signature of Centre Superintendent</div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="table-item1">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v13">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1">
                                                <div class="label">Name & Designation</div>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="table-item2">
                                <table cellpadding="0" cellspacing="0" class="datawithlable datawithlable__v13">
                                    <tbody>
                                        <tr>
                                            <td class="table-item1">
                                                <div class="label">Name & Designation</div>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </td>
        </tr>
    </table>
</page>