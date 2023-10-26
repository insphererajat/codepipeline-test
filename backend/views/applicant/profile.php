<?php
$this->title = 'Applicant Manager';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>

<?=
    $this->render('/common/applicant/profile.php', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'title' => $this->title,
        'url' => 'applicant/index',
    ]);
?>

