<?php

use \yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Profile';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/admin/home/index'])];
$this->params['breadcrumb'][] = ['label' => $this->title, 'class' => 'active'];

?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
 <?= \backend\widgets\alert\AlertWidget::widget() ?>
 <div class="col-md-12 col-sm-12 col-xs12 cmt-20">
    <?= $this->render('partials/_form.php', ['model' => $model]); ?>
 </div>
</div>