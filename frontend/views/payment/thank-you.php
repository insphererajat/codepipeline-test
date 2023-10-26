<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
?>
<!-- Begin Success section -->
<div class="msg__theme success">
    <figure>
        <img src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/success.svg" alt="icon">
    </figure>
    <div class="msg__theme__content">
        <h2>Success</h2>
        <p>Your payment has been submitted successfully </p>
    </div>
    <div class="msg__theme__action">
        <a href="<?= Yii::$app->homeUrl; ?>">Back to Home</a>
    </div>
</div>
<!-- End Success section -->