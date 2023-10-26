<?php

use common\models\ApplicantExam;

if (!$model['is_downloaded']) {
    ApplicantExam::updateAll(['is_downloaded' => 1, 'downloaded_on' => time()], 'id=:id', [':id' => $model['id']]);
}
?>
<?= $this->render('_hall-ticket/' . $model['type'] . '/' . $applicantPostModel['classified_id'] . '.php', ['guid' => $guid, 'applicantPostModel' => $applicantPostModel, 'model' => $model]); ?>