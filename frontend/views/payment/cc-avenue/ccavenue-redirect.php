<?=
$this->render('/payment/base-payment/ccavenue-redirect.php', [
    'action' => $action,
    'encryptedData' => $encryptedData,
    'accessCode' => $accessCode
]);
?>

