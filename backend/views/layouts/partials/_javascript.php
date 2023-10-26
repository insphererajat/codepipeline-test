<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<?php if (isset(\Yii::$app->params['applicationEnv']) && \Yii::$app->params['applicationEnv'] === 'PROD'): ?>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/deploy/app.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" ></script>
<?php else: ?>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/jquery/jquery-3.5.1.min.js" ></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-4.5.3/js/popper.min.js" ></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-4.5.3/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-datetimepicker/moment.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootbox/bootbox.all.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/chosen-select/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/sumo-select/jquery.sumoselect.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/switchery/switchery.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/tabs-scroll/tabs-scroll.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/owl-carousel/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/bootstrap-notify-master/bootstrap-notify.min.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/crypto-js/crypto-js.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/fancybox-2.1.7/source/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/vendors/highchart/highcharts.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/js/theme.js"></script>

<script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.js" ></script>
<script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.activeForm.js" ></script>
<script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.gridView.js" ></script>
<script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.validation.js" ></script>
<script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/jquery.pjax.js" ></script>
<script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.captcha.js" ></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/common.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/location.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/auth.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/captcha.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/form-sanitization.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/general.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/qualification/qualification.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/applicant/applicant.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/report/report.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/user/user.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/log-profile/log-profile.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/dashboard/dashboard.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/profile/profile.js"></script>
<script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/export/export.js"></script>
<?php endif; ?>
<script type="text/javascript">
    var baseHttpPath = '<?= \yii\helpers\Url::base(\Yii::$app->params['httpProtocol']) ?>';
    var staticPath = '<?= \Yii::$app->params['staticHttpPath'] ?>';
    var encriptionKey = '<?= \Yii::$app->params['hashKey'] ?>';
</script>
