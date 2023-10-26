<?php if (isset(\Yii::$app->params['applicationEnv']) && \Yii::$app->params['applicationEnv'] === 'PROD'): ?>
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/deploy/app.min.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" />
<?php else: ?>
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/css/project-vendors.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>">
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/css/project.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>">
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/dropzone/css/dropzone.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" media="screen" />
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/cropper/css/cropper.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" media="screen" />
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" media="screen" />
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/admin/css/default.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>">    
    <link type="text/css" rel="stylesheet" href="<?= Yii::$app->params['staticHttpPath'] ?>/admin/css/reset.css?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>">
<?php endif; ?>