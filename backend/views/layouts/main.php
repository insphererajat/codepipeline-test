<?php

use yii\helpers\Html;

$this->beginPage();
?>
<!DOCTYPE html>
<html class="no-js"  lang="<?= Yii::$app->language ?>">
    <head>
    <meta charset="<?= Yii::$app->charset ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Cache-control" content="no-cache">
        <?php header( 'X-FRAME-OPTIONS: SAMEORIGIN' ); ?>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?> | <?= \Yii::$app->params['appName'] ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/favicon/favicon.ico" sizes="any" type="image/svg+xml" />

        <?php if (isset(\Yii::$app->params['applicationEnv']) && \Yii::$app->params['applicationEnv'] === 'PROD'): ?>
            <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/deploy/app.min.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" />
        <?php else: ?>
            <link rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/css/vendors.css">
            <link rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/css/default.css">
            <link rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
            <link rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/fancybox-2.1.7/source/jquery.fancybox.css">
        <?php endif; ?>
        <?php $this->head() ?>
    </head>
    <body class="themeOption1 <?= isset($this->params['bodyClass']) ? $this->params['bodyClass'] : '' ?>">
        <?php $this->beginBody() ?>
        <div class="<?= (!\Yii::$app->user->isGuest) ? "adm-c-pageContainer adm-c-pageContainer-xs" : ''; ?>">
            <!-- sidebar section -->
            <?= (!\Yii::$app->user->isGuest) ? $this->render('partials/_sidebar-navigation.php') : '' ?>  
            <div class="adm-c-pageContainer__wrapper">
                <?= $this->render('partials/_header.php') ?> 
                <?= $content ?>
            </div>
        </div>
        <div id="themeModal" class="modal o-modal size-1000 u_pad20_20 fade show" tabindex="-1" role="dialog"></div>
        <?= $this->render('partials/_javascript.php') ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>