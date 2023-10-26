<?php

$html2pdf = new \Spipu\Html2Pdf\Html2Pdf();
$template = $this->render('print-content.php', [
    'model' => $model,
    'employments' => $employments,
    'qualifications' => $qualifications,
    'reviewFormModel' => $reviewFormModel,
    'isPaid' => $isPaid
]);

$html2pdf->writeHTML($template);
$html2pdf->output('certificate.pdf');
ob_flush();die;
?>











