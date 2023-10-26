<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?= Html::csrfMetaTags() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Cache-control" content="no-cache">
        <?php header( 'X-FRAME-OPTIONS: SAMEORIGIN' ); ?>
        <?= $this->render('partials/_style.php'); ?>
        <title><?= \Yii::$app->params['appName'] ?></title>
        <link rel="shortcut icon" href="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/favicon/favicon.ico" />
    </head>
    <body class="<?= isset($this->params['bodyClass']) ? $this->params['bodyClass'] : '' ?>">
        <?php $this->beginBody() ?>
        <?= $this->render('partials/_header.php'); ?>
        <?= $this->render('partials/_navigation.php'); ?>
        <div id="globalLoader" class="loading__wrapper theme3 d-print-none" style="display:none;">
            <div class="loading__spinner"> 
                <div class="loader"> 
                    <span><img width="24" height="24" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/images/loading.svg"></span> 
                    <span class="text">Please wait ...</span> 
                </div> 
            </div>
            <div class="loading__spinner-overlay"></div>
        </div>
        <?= $content; ?>
        <!----------------------otp modal--------------------->
        <div id="otpModal" data-email="" data-mobile="" class="modal o-modal o-modal-small" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="otpModal" aria-hidden="true"  role="dialog"></div>
        <div id="ShowModal" data-email="" data-mobile="" data-time="<?= 4 ?>"></div>
        <div id="uploadImageModal"  class="modal modal__wrapper fade" tabindex="-1" role="dialog"  aria-labelledby="gridSystemModalLabel"></div>
        <div class="modal modal__wrapper fade" id="cropImageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"></div>

        <?= $this->render('partials/_footer.php'); ?>
        <?= $this->render('partials/_script.php'); ?>
        <?= $this->render('partials/_templates.php') ?> 
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>