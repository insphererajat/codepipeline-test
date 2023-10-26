<div class="adm-c-alert__customized">
<?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
    <div class="alert <?=($key === 'success') ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
    </div>
<?php endforeach; ?>
</div>