<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<!-- Begin Error section -->
<div class="main-body">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="c-tankssection">

                    <div class="c-f-guidline__wrapper">
                        <!-- Begin Success section -->
                        <div class="msg__theme success">
                            <figure>
                                <img class="successimg" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/error.svg" alt="icon"/>
                            </figure>

                            <div class="msg__theme__content">
                                <h2><?= Html::encode($exception->statusCode) ?></h2>
                                <p><?= nl2br(Html::encode($message)) ?></p>
                            </div>
                            <div class="msg__theme__action">
                                <a href="/" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green"><i class="fa fa-home"></i> Back to Home</a>
                            </div>
                        </div>
                        <!-- End Success section -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>