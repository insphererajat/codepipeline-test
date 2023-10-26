<?php

use yii\helpers\Url;

$classifiedList = common\models\MstClassified::classifiedList();

$url = '/auth/login?guid=';
if (!Yii::$app->applicant->isGuest) {
    $url = '/registration/personal-details?guid=';
}
?>
<?php
$flag = 0;
if (!empty($classifiedList)):
    foreach ($classifiedList as $job):
        if ($job['id'] == common\models\MstClassified::MASTER_CLASSIFIED)
            continue;
        if (!\common\models\MstClassified::checkClassifiedActiveStatus($job['id'])) {
            continue;
        } else {
            $flag = 1;
        }
        ?>
        <div class="c-f-job__list-item">
            <div class="c-f-job__list-item-content">
                <div class="job-postTitle">
                    <!--<?= $job['title']; ?>-->
                    <strong><?= $job['description']; ?></strong>
                <!--<a href="javascript:;" class="u-link" data-toggle="modal" data-target="#advtModal"><span class="fas fa-file-pdf u-icon"></span><span class="value">View Details</span></a>-->
                    <a target="_blank" href="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/pdf/<?= $job['folder_name'] ?>.pdf" class="u-link"><span class="fas fa-file-pdf u-icon"></span><span class="value">View Details</span></a>
                </div>
                <div class="job-advNo">
                    Advertisement Number <?= $job['code']; ?>
                    <span class="job-lastDate">(Last Date of Submission - <?= date('d M, Y', strtotime($job['extended_date'])) ?>)</span>
                </div>
            </div>
            <a href="<?= $url . $job['guid']; ?>" class="c-f-job__list-item-action">
                Apply Here
            </a>
        </div>
        <?php
    endforeach;
endif;
if (!$flag):
    ?>
    <div class="c-f-job__list-item">
        <div class="c-f-job__list-item-content">
            <div class="job-postTitle">There is no active advertisement now.</div>
        </div>
    </div>
<?php endif; ?>