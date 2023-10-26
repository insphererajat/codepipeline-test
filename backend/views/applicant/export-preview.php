<?php
$this->title = 'Applicant Post';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>

<?=
    $this->render('/common/applicant/export-preview.php', [
        'dataProvider' => $dataProvider
    ]);
?>
