<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
 
$this->title = $name;
?>
<!-- Begin Error section -->
<div class="error__theme">
    <figure>
        <img src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/error.svg" alt="icon">
    </figure>
    <div class="error__theme__content">
        <h2><?= Html::encode($exception->statusCode) ?></h2>
        <p><?= nl2br(Html::encode($message)) ?></p>
        
    </div>
    
</div>
