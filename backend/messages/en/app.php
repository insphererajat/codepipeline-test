<?php
/**
 * @link http://www.ideoris.com.au
 * @copyright Copyright (c) 2016 Ideoris Pty Ltd.
 * @license http://www.yiiframework.com/license/
 * @version app.php $17-05-2016 16:52:00$
 * 
 * @author Pawan Kumar <info@ideoris.com.au>
 */

$messages = [
    
    'invalid.request' => "Invalid request",
];

return \yii\helpers\ArrayHelper::merge(require('error.php'), $messages);