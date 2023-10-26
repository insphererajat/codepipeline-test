<?php

$domain = (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
$isHttps = (isset($_SERVER) && ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'))) ? TRUE : FALSE;
if (!$isHttps && isset($_SERVER) && isset($_SERVER['HTTP_CF_VISITOR']) && strpos($_SERVER['HTTP_CF_VISITOR'], 'https') !== false) {
    $isHttps = TRUE;
}

$httpProtocol = ($isHttps) ? 'https' : 'http';

return [
    'appName' => 'Himachal Pradesh State Legal Services Authority (HPSLSA)',
    'adminEmail' => 'support@servicecommission.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'file.extension' => '.doc,.docx,.xlss,.pdf',
    'allowed.extension' => ['jpg', 'png', 'jpeg'],
    'allowed.mime' => ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    'applicationEnv' => isset($_SERVER['APPLICATION_ENV']) ? $_SERVER['APPLICATION_ENV'] : 'PROD',
    'upload.dir' => dirname(__FILE__) . '/../../frontend/web/uploads',
    'upload.baseHttpPath' => $httpProtocol . '://' . $domain . '/uploads',
    'upload.baseHttpPath.relative' => '/uploads',
    'upload.dir.tempFolderName' => 'temp',
    'upload.deletelocalfile.afterUploadToS3' => TRUE,
    'upload.uploadToS3' => TRUE,
    'staticHttpPath' => $httpProtocol . '://' . $domain . '/static',
    'rootHttpPath' => $httpProtocol.'://'.$domain,
    'httpProtocol' => $httpProtocol,
    'master.password' => '@Password7',
    'paginationLimit' => 20,
    // failed login attempts and delay time
    'failedLoginAttempts' => 5, // 5 attempts
    'failedLoginDelayTime' => 300, // 5 mins
    'hashKey' => "27kozQaXwGuNJ35t",
    'aws.url.validity.minutes' => 15,
    'cacheBustingTimestamp' => '0033',
    'bob.alias' => 'HPHC',
    'payment.declaration' => 'I, the deponent, do hereby solemnly declare and verify that the contents of the above declaration are true to the best of my knowledge and belief, and nothing material has been concealed or suppressed therefrom. This electronic self declaration has been given with reference to IT Act 2000 and is admissible under The Indian Evidence Act 1872.',
    // your other params
    'reCAPTCHA.siteKey' => '6LfuQtEcAAAAAEsLvqYCuJGKrmZMFsA-HnNoG9Il',
    'reCAPTCHA.secretKey' => '6LfuQtEcAAAAADkG2y5s_sFhCeCYHwUvDpo1cpYI',
    'enable.csc' => true
];