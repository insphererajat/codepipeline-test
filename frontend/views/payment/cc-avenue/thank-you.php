<?=
    $this->render('/payment/base-payment/thank-you.php', [
        'transactionId' => $transactionId,
        'applicantModel' => $applicantModel
    ]);
?>