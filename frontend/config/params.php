<?php

$domain = (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
return [
    'adminEmail' => 'admin@example.com',
    'bodyClass' => 'themeClr-1',
    'username-cookie-key' => 'sc-frontend-applicant',
    'login-rememberme-cookie-key' => 'sc-frontend-applicant-rememberme',
];
