<?php

use yii\helpers\Url;
use common\models\MstPost;
use common\models\ApplicantCriteria;
use common\models\MstQualification;

$applicantCriteria = ApplicantCriteria::findByApplicantPostId($applicantPostModel['id'], ['selectCols' => ['applicant_criteria.*', 'applicant_post_detail.post_id'], 'joinWithApplicantPostDetail' => 'innerJoin']);
?>
<div class="reviewrow col2">
    <?php if (isset($applicantCriteria) && !empty($applicantCriteria)): ?>
        <div class="tablewrap">
            <table>

                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Post</th>
                    <th><?= Yii::t('app', 'Condition 1'); ?></th>
                    <th><?= Yii::t('app', 'Condition 2'); ?></th>
                </tr>
                <?php
                echo "<tr>";
                echo "<td scope='col'>1</td>";
                echo "<td scope='col'>";
                echo!empty($applicantCriteria['post_id']) ? MstPost::getTitle($applicantCriteria['post_id']) : '';
                echo "</td>";
                echo "<td scope='col'>";
                echo!empty($applicantCriteria['field1']) ? MstQualification::getName($applicantCriteria['field1']) : '';
                echo "</td>";
                echo "<td scope='col'>";
                echo $applicantCriteria['field2'];
                echo "</td>";

                echo "</tr>";
                ?>
            </table>
        </div>
    <?php endif; ?>
</div>