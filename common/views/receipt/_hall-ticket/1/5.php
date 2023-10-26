<?php

use common\models\Media;
use common\models\MstClassified;
use common\models\MstListType;

$advt = strtoupper(MstClassified::getTitle($model['classified_id']));
?>
<title>Hall Ticket (Admit Card)</title>
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

.affix {
    padding-left: 15px;
    padding-right: 15px;
    padding-top: 5px;
    padding-bottom: 5px;
    border: solid 0.264583333mm #4a4a4a;
}

.red {
    color: red;
}

.normaltext3 {
    margin: auto 40px;
}

table.bulletpoint td.bulletpoint__head {
    padding-bottom: 2px;
}

table.datawithlable__v1 td {
    padding-bottom: 2mm !important;
}

table.bulletpoint td.content {
    padding: 1mm !important;
}

table.mediawrap img.studentimg {
    height: 175px !important;
    width: 140px !important;
}

table.tbl-omr td {
    padding: 5px;
}

table.bulletpoint td.content {
    font-size: 10pt;
}

table.bulletpoint td.bullet {
    font-size: 10pt;
}

.txt-justify {
    text-align: justify;
}

span.omr {
    width: 13px;
    height: 13px;
    border-radius: 50%;
    -ms-border-radius: 50%;
    -webkit-border-radius: 50%;
    -o-border-radius: 50%;
    background-color: black;
    display: inline-block;
    margin-top: 2px;
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
    <table cellpadding="0" cellspacing="0" class="maintable maintable__v1 w200 marginauto">
        <tr>
            <td class="fullwidth">
                <table cellpadding="0" cellspacing="0" class="header__elemv9 w100">
                    <tr>
                        <td class="table-item1">
                            <table cellpadding="0" cellspacing="0" class="headercentertext__v3 w100">
                                <tr>
                                    <td class="topline red">HP STATE LEGAL SERVICES AUTHORITY SHIMIA-171009</td>
                                </tr>
                                <tr>
                                    <td class="middleline red">WRITTEN TEST FOR THE POST OF JUNIOR OFFICE ASSISTANT (IT) ON<br/>CONTRACT BASIS</td>
                                </tr>
                                <tr>
                                    <td class="bottomline">HALL TICKET / ADMIT CARD</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="header__elemv10 w100">
                    <tr>
                        <td class="table-item1">The Hall Ticket/ Admit Card will be presented by the Candidate at the beginning of the test/ exam to the invigilator in the test/exam Centre.</td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="tablebase tablebase__v10">
                    <tr>
                        <td class="table-item1">
                            <table class="datawithlable datawithlable__v1">
                                <tr>
                                    <td class="table-item1"><span class="label">Roll No.</span></td>
                                    <td class="table-item2 pfwb">: <?= $model['rollno'] ?></td>
                                </tr>
                                <tr>
                                    <td class="table-item1"><span class="label">Application No.</span></td>
                                    <td class="table-item2 pfwb">: <?= $model['application_no'] ?></td>
                                </tr>
                                <tr>
                                    <td class="table-item1"><span class="label">Name</span></td>
                                    <td class="table-item2">: <?= $model['name'] ?></td>
                                </tr>
                                <tr>
                                    <td class="table-item1"><span class="label">Father's/Husband's Name</span></td>
                                    <td class="table-item2">: <?= $model['father_name'] ?></td>
                                </tr>
                                <tr>
                                    <td class="table-item1"><span class="label">Date of Birth</span></td>
                                    <td class="table-item2">: <?= date('d.m.Y', strtotime($model['date_of_birth'])) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-item1"><span class="label">Category</span></td>
                                    <td class="table-item2">: <?= MstListType::getName($model['social_category_id']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-item1"><span class="label">Exam/Test Centre</span></td>
                                    <td class="table-item2">: <span
                                            style="color:red"><?= $model['exam_centre_name'] ?></span></td>
                                </tr>
                            </table>
                        </td>
                        <td class="table-item2">
                            <table class="mediawrap mediawrap__v1">
                                <tr>
                                    <td class="images-item1"><img
                                            src="<?= Media::getDocMediaUrl(['cdn_path' => $model['cdn_path']]); ?>"
                                            class="studentimg" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="table-item3">
                            <!--Blank-->
                        </td>
                        <td class="table-item4 txt-justify" style="line-height : 24px">
                            <p class="affix">Affix your passport size photograph which has been scanned and uploaded in the application form duly attested</p>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="dataview dataview__v15">
                    <tr>
                        <th class="table-item1 txt-center">Sr. No. </th>
                        <th class="table-item2 txt-center">Date & Time of Exam/Test</th>
                        <th class="table-item3 txt-center">Test/Examination</th>
                        <th class="table-item4 txt-center">Candidate’s<br />
                            Signature<br />
                            (at the time of<br />
                            test/ exam)</th>
                        <th class="table-item5 txt-center">Invigilator Signature</th>
                    </tr>
                    <tr>
                        <td class="table-item1 txt-center">1</td>
                        <td class="table-item2 txt-center red"><?= date('d.m.Y', strtotime($model['examdate'])) ?>
                            (<?= date('l', strtotime($model['examdate'])) ?>) <br /> <?= $model['examtime'] ?> </td>
                        <td class="table-item3 txt-center"><?= $model['examination'] ?></td>
                        <td class="table-item4 txt-center"></td>
                        <td class="table-item5 txt-center"></td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="">
                    <tr>
                        <td class="txt-justify">
                            <div class="normaltext3"><strong>You are required to report at the venue of <span class="red">Examination Centre</span> at least an hour before the scheduled timings of screening test a/w Admit Card. Your candidature shall only be allowed provisionally subject to production of requisite documents. You are also required to go through the <span class="red">COVID-19 INSTRUCTION/ GENERAL INSTRUCTIONS</span> etc. at the Website of HP State Legal Services Authority. The candidate shall have to bring their own stationery articles viz. Pencil, Blue Ball point pen, card board etc. for being used during the test/exams.</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="txt-justify">
                            <div class="normaltext3"><strong><u>DECLARATION :</u></strong> I hereby declare that I am an Indian National and statements made in this Admit Card/Documents are true, complete and correct to the best of my knowledge and belief. I undertake that in the event of any information being found false or incorrect at any stage, my candidature is liable to be cancelled. I also solemnly declare that I do not suffer from any of the disqualifications shown in the advertisement for the post and I am eligible in all respects according to eligibility criteria prescribed in the advertisement.</div>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" class="signaturewrap__v2">
                    <tr>
                        <td class="item1" style="text-align: center;"><strong>Signature of the Candidate</strong><br />
                            (at the time of test/exam)
                        </td>
                        <td class="item2"></td>
                        <td class="item3" style="text-align: left;"><strong>Signature of Centre Superintendent</strong>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>


    <div class="after"></div>
    <table cellpadding="0" cellspacing="0" class="maintable maintable__v1 w200 marginauto">
        <tr>
            <td class="fullwidth">
                <table cellpadding="0" cellspacing="0" class="bulletpoint bulletpoint__v1">
                    <tr>
                        <td colspan="2" class="bulletpoint__head txt-justify"><u>HP STATE LEGAL SERVICES AUTHORITY, SHIMLA, H.P.-171009</u></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="bulletpoint__head">:: IMPORTANT INSTRUCTIONS ::</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="bulletpoint__subhead txt-justify red">Written Test for the post of Junior Office Assistant (IT) on contract basis scheduled to be held on <strong>17th December, 2022</strong> (Saturday) from <strong>11:00 A.M. to 01.00 P.M. (2 hours)</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="bulletpoint__subhead"> <strong>COVID-19 INSTRUCTIONS</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="bullet">1.</td>
                        <td class="content txt-justify">Candidates are required to adhere to the relevant Rules, Regulations and Guidelines of HP State Legal Services Authority, Shimla and also to follow the protocols of Local Administration, State Government and Government of India especially related to COVID- 19 pandemic being issued from time to time.</td>
                    </tr>
                    <tr>
                        <td class="bullet">2.</td>
                        <td class="content txt-justify">Candidate should maintain proper social distancing by keeping reasonable distance with each other while appearing for the said test.</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="bulletpoint__subhead"> <strong>GENERAL INSTRUCTIONS TO THE CANDIDATES</strong> </td>
                    </tr>

                    <tr>
                        <td class="bullet">1.</td>
                        <td class="content txt-justify">The candidate must bring his admission letter/Admit Card to the examination hall failing which he will not be allowed to sit in the examination.</td>
                    </tr>

                    <tr>
                        <td class="bullet">2.</td>
                        <td class="content txt-justify">The candidate is required to paste a passport size latest <strong>attested photograph</strong> in the space provided on the Admit Card. No candidate will be allowed to appear in the examination without Admit Card and latest passport size attested photograph duly affixed on it.</td>
                    </tr>

                    <tr>
                        <td class="bullet">3.</td>
                        <td class="content txt-justify">If ineligibility is found at any stage before or after the written test or if the conditions prescribed in the Rules & instructions given in the Advt. notice for the test are not complied with or any additional information/ documents called for at any stage are not furnished within the time specified therein, his/her candidature will be liable to be cancelled.</td>
                    </tr>

                    <tr>
                        <td class="bullet">4.</td>
                        <td class="content txt-justify">The candidate shall be allowed to enter the examination hall/room thirty minutes before the scheduled time for the commencement of the examination.</td>
                    </tr>

                    <tr>
                        <td class="bullet">5.</td>
                        <td class="content txt-justify">Bags, Mobile phones, pagers, books or any other electronic equipment capable of being used as communication or calculation devices, etc. should not be allowed to be taken inside the Examination Centre. So, arrangement for their safe custody cannot be assured. The candidates will be allowed to take with them only the admit card, identity proof, pen, ball point pen, pencil and clip board.</td>
                    </tr>

                    <tr>
                        <td class="bullet">6.</td>
                        <td class="content txt-justify">The HP State Legal Services Authority will not supply any stationery articles except answer sheet.</td>
                    </tr>

                    <tr>
                        <td class="bullet">7.</td>
                        <td class="content txt-justify">No T.A. / D.A. will be paid by the HP State Legal Services Authority for taking this examination.</td>
                    </tr>

                    <tr>
                        <td class="bullet">8.</td>
                        <td class="content txt-justify">The Admit Card (along with latest passport size photo duly attested, affixed on the space provided for the purpose), should be handed over to invigilator on the commencement of examination.</td>
                    </tr>

                    <tr>
                        <td class="bullet">9.</td>
                        <td class="content txt-justify">In case candidate has any objection with respect to the conduct of exam or paper, he is advised to give a representation addressed to the Member Secretary, HP State Legal Services Authority, Shimla, in writing, through the Centre Supervisor immediately after the examination is over. No representation whatsoever will be entertained thereafter. However, the candidates shall not make any noise or create any unruly scene at the exam centre in this regard. If any candidate is found to have violated this instruction, then he/she may be held disqualified by the Recruitment Committee for that very exam and also debarred from taking any other examination to be conducted by the HP State Legal Services Authority in future. The detailed particulars of such candidates will be uploaded on the website of the HP State Legal Services Authority to blacklist such debarred candidates.</td>
                    </tr>

                    <tr>
                        <td class="bullet">10.</td>
                        <td class="content txt-justify">Re-checking/ re-evaluation, for the Answer Sheets will not be allowed in any case.</td>
                    </tr>

                    <tr>
                        <td class="bullet">11.</td>
                        <td class="content txt-justify">Attention of the candidates is invited to laws relating to prevention of use of unfair means. Use of unfair means is an offence. Any examinee found of using unfair means shall be dealt with under the provisions of relevant law in addition to debarring him from the present as well as future examinations.</td>
                    </tr>

                    <tr>
                        <td class="bullet" colspan="2" style="text-align: center;">*******</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</page>