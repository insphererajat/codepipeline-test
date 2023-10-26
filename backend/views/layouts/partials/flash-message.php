<?php
/**
 * @link http://www.ideoris.com.au
 * @copyright Copyright (c) 2016 Ideoris Pty Ltd.
 * @license http://www.yiiframework.com/license/
 * @version flash-message.php $26-04-2016 12:35:53$
 * 
 * @author Pawan Kumar <info@ideoris.com.au>
 */
?>
 
                        

<div class="pt-2">
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message):
        ?>
        <div class="alert <?= ($key === 'success') ? 'alert-success' : 'alert-danger' ?> alert-dismissible in" role="alert" style="width: 94%;margin-left: 35px;">
            <button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
            <p style="margin-bottom:0px;"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?= $message ?></p>
        </div>
    <?php endforeach; ?>
</div>



