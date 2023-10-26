<?=
    $this->render('/payment/base-payment/thank-you.php', [
        'transactionId' => $transactionId,
        'studentModel' => $studentModel
    ]);
?>