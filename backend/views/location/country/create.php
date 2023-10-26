<?php

use \yii\helpers\Url;

$title = (isset($model->id) && $model->id > 0) ? 'Edit Country' : 'Create Country';
$this->title = $title;
?>
<div class="page__bar">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="section">
                    <h2 class="section__heading"> <?= strtoupper($title) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <?= $this->render('/layouts/partials/flash-message.php') ?>
                <section class="widget__wrapper">
                    <?= $this->render('/location/partials/_country-form.php', ['model' => $model]) ?>
                </section>
            </div>
        </div>
    </div>
</div>