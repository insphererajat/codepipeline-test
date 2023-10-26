<?php
$messages = [
    'error.invalid.request' => 'Invalid Request !',

    'success.create' => '{title} created successfully.',
    'success.update' => '{title} updated successfully.',
    'success.delete' => '{title} deleted successfully.',
    'success.inactive' => "{title} deactivated successfully.",
    'success.active' => "{title} activated successfully.",

    'success.save' => '{title} saved successfully.',
   
    'last.login.attempt' => 'Last login attempt left. Make sure you login with correct credentials this time otherwise your account will get locked for 5 mins. You wont be able to login until your account gets unlocked.',
    'login.max.failed.attempts' => 'Your Account is currently Locked due to maximum failed attempts. Please try again in 5 mins.', 
];

return \yii\helpers\ArrayHelper::merge(require('error.php'), $messages);