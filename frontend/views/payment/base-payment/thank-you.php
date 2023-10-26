<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
?>
<div class="main-body">
    <div class="container">
    <div class="row">
    <div class="col-lg-12">
    <div class="c-tankssection">
    
        <div class="c-f-guidline__wrapper">
            <!-- Begin Success section -->
            <div class="msg__theme success">
                <figure>
                    <img class="successimg" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/success.svg" alt="icon"/>
              </figure>
                
                <div class="msg__theme__content">
                    <h2>Success</h2>
                    <p>Your payment has been submitted successfully </p>
                </div>
                <div class="msg__theme__action">
                    <a href="<?= \yii\helpers\Url::toRoute(['/applicant/post']) ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green"><i class="fa fa-home"></i> Back to Home</a>
                    <a href="<?= \yii\helpers\Url::toRoute(['/applicant/preview', 'guid' => $model['guid']]) ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green" target="_blank"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            <!-- End Success section -->
            </div>
           </div>
           </div>
            
        </div>
    </div>
</div>