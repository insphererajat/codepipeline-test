<div class="pt-2">
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message):
        ?>
        <div class="alert <?= ($key === 'success') ? 'alert-success' : 'alert-danger' ?> alert-dismissible in" role="alert" style="">
            <button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
            <p style="margin-bottom:0px;"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?= $message ?></p>
        </div>
    <?php endforeach; ?>
</div>
