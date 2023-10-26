<?php
use common\models\MstListType;
?>
<table cellspacing="0" style="width:100%;">
    <tr>
        <td style="padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><div style="height:5mm;">&nbsp;</div></td>
    </tr>
</table>

<table  cellspacing="0" style="width: 100%; border: solid 0.264583333mm #000000;">
    <tr>
        <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Criteria Details for All Applied Posts</strong></th>
    </tr>

    <tr>
        <td colspan="2" valign="top" style="width: 100%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php if ($applicantLtModel['qualification_id'] !== null): ?>
                    <tr>
                        <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Qualification</strong></td>
                        <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantLtModel['qualification_id']) ? \common\models\MstQualification::getName($applicantLtModel['qualification_id']) : ''; ?></td>
                    </tr>
                <?php endif; ?>
            </table>

        </td>
    </tr>
    <?php if ($applicantLtModel['additional_qualification_id'] !== null): ?>
        <tr>
            <td colspan="2" valign="top" style="width: 100%;  border-top: solid 0.264583333mm #000000; border-left: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <?php if ($applicantLtModel['additional_qualification_id'] !== null): ?>
                        <tr>
                            <td valign="top" style="width:40%;   padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"><strong>Teaching Professional Degree / Diploma</strong></td>
                            <td valign="top" style="width:58%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantLtModel['additional_qualification_id']) ? \common\models\MstQualification::getName($applicantLtModel['additional_qualification_id']) : ''; ?></td>
                        </tr>
                    <?php endif; ?>
                </table>

            </td>
        </tr>
    <?php endif; ?>
    <?php if ($applicantLtModel['university_id'] !== null && $applicantLtModel['university_id'] != common\models\MstUniversity::UNIVERSITY): ?>
        <tr>
            <td colspan="2" valign="top" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <?php if ($applicantLtModel['university_id'] !== null && $applicantLtModel['university_id'] != common\models\MstUniversity::UNIVERSITY): ?>
                        <tr>
                            <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>University</strong></td>
                            <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantLtModel['university_id']) ? common\models\MstUniversity::getName($applicantLtModel['university_id']) : ''; ?></td>
                        </tr>
                    <?php endif; ?>
                </table>

            </td>
        </tr>
    <?php endif; ?>
    <?php if ($applicantLtModel['additional_university_id'] !== null): ?>
        <tr>
            <td colspan="2" valign="top" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>University</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= !empty($applicantLtModel['additional_university_id']) ? common\models\MstUniversity::getName($applicantLtModel['additional_university_id']) : ''; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endif; ?>
    <?php if ($applicantLtModel['qualification_duration'] !== null): ?>
        <tr>
            <td colspan="2" valign="top" style="width: 100%; border-top: solid 0.264583333mm #000000;">
                <table width="100%" border="0" cellspacing="0" >
                    <tr>
                        <td  valign="top" style="width:34%;  padding-left:0mm; padding-right:0mm;  padding-top:0mm; padding-bottom:0mm;"><strong>Qualification Duration</strong></td>
                        <td valign="top" style="width:66%;  padding-left:0mm; padding-right:0mm; padding-top:0mm; padding-bottom:0mm;"> : <?= ($applicantLtModel['qualification_duration'] !== null) ? \common\models\ApplicantCriteria::getDurationArr($applicantLtModel['qualification_duration']) : ''; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endif; ?>
</table>

<?php if (isset($applicantLtDetailModel) && !empty($applicantLtDetailModel)): ?>
    <table cellspacing="0" style="width:100%;  border:solid 0.264583333mm #000000;">
        <tr>
            <th colspan="2" align="left" style=" width:100%; font-size:12pt; font-weight:bold;"><strong>Others Applied Posts Qualification Details</strong></th>
        </tr>

        <tr>
            <td  style=" width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Subject</td>
            <td  style=" width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; font-weight:bold;">Read</td>
        </tr>

        <?php
        $hindi = $eng = false;
        foreach ($applicantLtDetailModel as $key => $record):
            $status = !empty($record['status']) ? 'Yes' : 'No';
            $subject = \common\models\MstSubject::getName($record['subject_id']). ' as a subject in Graduation';
            if ($record['subject_id'] == common\models\MstSubject::ENGLISH) {
                $subject = \common\models\MstSubject::getName($record['subject_id']).' as a subject in Intermediate';
                if ($eng) {
                    continue;
                }
                $eng = true;
            }
            if ($record['subject_id'] == common\models\MstSubject::HINDI) {
                if ($hindi) {
                    continue;
                }
                $hindi = true;
            }
            if ($record['subject_id'] == common\models\MstSubject::COMPUTER) {
                $subject = 'CCC Certification (NIELIT)';
            }

            echo "<tr>";
            echo "<td style='width:20%; border-top: solid 0.264583333mm #000000; font-size:9pt;'>{$subject}</td>";
            echo "<td style='width:20%; border-left: solid 0.264583333mm #000000; border-top: solid 0.264583333mm #000000; font-size:9pt; '>{$status}</td>";
            echo "</tr>";
        endforeach;
        ?>

    </table>
<?php endif; ?>