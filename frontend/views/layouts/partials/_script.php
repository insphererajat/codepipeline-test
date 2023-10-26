<?php if (isset(\Yii::$app->params['applicationEnv']) && \Yii::$app->params['applicationEnv'] === 'PROD'): ?>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/deploy/app.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
<?php else: ?>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/jquery/jquery-3.5.1.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-4.5.3/js/popper.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-4.5.3/js/bootstrap.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-datetimepicker/moment.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/chosen-select/chosen.jquery.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/sumo-select/jquery.sumoselect.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/switchery/switchery.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootstrap-notify-master/bootstrap-notify.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/dropzone/dropzone.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/bootbox/bootbox.min.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/vendors/handlebars/js/handlebars.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/vendors/cropper/jquery-cropper.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/vendors/crypto-js/crypto-js.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.activeForm.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.gridView.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.validation.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/jquery.pjax.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= \Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/yii.captcha.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/uploadfile.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/auth.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/captcha.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/common.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/form-sanitization.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/function.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/validation.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/location.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/crop.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/common/general.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/registration/registrationv2.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/applicant-post/applicant-post.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/log-applicant/log-applicant.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/log-applicant/log-profile.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
    <script type="text/javascript" src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/dev/classified-criteria/classified-criteria.js?rel=<?= Yii::$app->params['cacheBustingTimestamp'] ?>"></script>
<?php endif; ?>
<script type="text/javascript">
    var baseHttpPath = '<?= \yii\helpers\Url::base(\Yii::$app->params['httpProtocol']) ?>';
    var staticPath = '<?= \Yii::$app->params['staticHttpPath'] ?>';
    var encriptionKey = '<?= \Yii::$app->params['hashKey'] ?>';
    var dobStartDate = '<?= date('Y-m-d', strtotime(common\models\MstClassified::AGE_CALCULATE_DATE.' -58 year')); ?>';
    var dobEndDate = '<?= date('Y-m-d', strtotime(common\models\MstClassified::AGE_CALCULATE_DATE.' -18 year')); ?>';
</script>
<script type="text/javascript">

    $.fn.inputTextBox({
        required: {
            success: function () {
                $.fn.pincode({});
            }
        }
    });

</script>