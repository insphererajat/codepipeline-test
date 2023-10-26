<?php

use yii\helpers\Url;
use common\models\MstPost;
use common\models\ApplicantCriteria;
use common\models\MstQualification;
use common\models\MstSubject;
use common\models\MstUniversity;

$applicantCriteria = ApplicantCriteria::findByApplicantPostId($applicantPostModel['id'], ['selectCols' => ['applicant_criteria.*', 'applicant_post_detail.post_id'], 'joinWithApplicantPostDetail' => 'innerJoin', 'resultCount' => \common\models\caching\ModelCache::RETURN_ALL]);
$posts = MstPost::getPostDropdown(['classifiedId' => $applicantPostModel['classified_id']]);
$data = [];
foreach ($applicantCriteria as $key => $value) {
    $data[$value['post_id']][] = $value;
}
?>
<div class="reviewrow col2">
    <?php if (isset($data) && !empty($data)): ?>
        <div class="tablewrap">
            <table>

                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Post</th>
                    <th><?= Yii::t('app', 'Condition 1'); ?></th>
                    <th><?= Yii::t('app', 'Condition 2'); ?></th>
                    <th><?= Yii::t('app', 'Condition 3'); ?></th>
                    <th><?= Yii::t('app', 'Condition 4'); ?></th>
                    <th><?= Yii::t('app', 'Condition 5'); ?></th>
                    <th><?= Yii::t('app', 'Condition 6'); ?></th>                
                </tr>
                <?php
                $count = 1;
                foreach ($data as $key => $record):
                    echo "<tr>";
                    echo "<td scope='col'>" . ($count++) . "</td>";
                    echo "<td scope='col'>";
                    echo isset($posts[$key]) ? $posts[$key] : '';
                    echo "</td>";
                    $c = 1;
                    foreach ($record as $exq) {
                        $str = "";
                        $str .= (isset($exq['field1']) && !empty($exq['field1'])) ? MstQualification::getName($exq['field1']) : '';
                        $str .= (isset($exq['field2']) && !empty($exq['field2'])) ? ', (' . MstUniversity::getName($exq['field2']) . ')' : '';
                        if (isset($exq['field3']) && !empty($exq['field3']) && !empty($exq['field1'])) {
                            $str .= ', (' . ucfirst($exq['field3']) . ')';
                        } else {
                            $str .= ucfirst($exq['field3']);
                        }
                        echo "<td scope='col'>";
                        echo $str;
                        echo "</td>";
                        $c++;
                    }

                    for ($i = $c; $i <= 6; $i++) {
                        echo "<td scope='col'></td>";
                    }

                    echo "</tr>";
                endforeach;
                ?>

            </table>
        </div>
    <?php endif; ?>
</div>