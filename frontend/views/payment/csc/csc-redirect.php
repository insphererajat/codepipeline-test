<?=
    $this->render('/payment/base-payment/csc-redirect.php', [
        'encText' => $encText,
        'frac' => $frac,
        'url' => $url
    ]);
?>