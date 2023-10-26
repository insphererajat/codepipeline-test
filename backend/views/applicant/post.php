<?php
$this->title = 'Applicant Post';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>

<?=
    $this->render('/common/applicant/post.php', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'title' => $this->title,
        'url' => 'applicant/post',
    ]);
?>
