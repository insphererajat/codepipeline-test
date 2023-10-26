<?php

use yii\helpers\Url;

$params = \Yii::$app->request->queryParams;
$classifiedModel = common\models\MstClassified::findByGuid($params['guid']);
$jobList = \common\models\MstPost::classifiedList(['classfiedId' => $classifiedModel['id']]);
//echo '<pre>'; print_r($classifiedList);die;
?>
<?php
if (!empty($jobList)):
    foreach ($jobList as $job):
        ?>
        <div class="c-f-job__list-item">
            <div class="c-f-job__list-item-content">
                <div class="job-postTitle"><?= $job['title']; ?></div>
                <div class="job-advNo">Advertisement Number <?= $job['code']; ?><span class="job-lastDate">(Last Date of Submission - 30 Oct, 2020)</span></div>
            </div>
        </div>
    <?php endforeach; ?>

    <a href="<?= Url::toRoute(['/login']); ?>" class="c-f-job__list-item-action">
        Apply Here
    </a>
<?php endif; ?>
