<?php

use \yii\helpers\Url;
$title = (isset($model->code) && $model->code > 0) ? 'Update' : 'Create';

$this->title = $title;

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/home/index'])];
$this->params['breadcrumb'][] = ['label' => 'District', 'url' => Url::toRoute(['/location/district/index'])];
$this->params['breadcrumb'][] = ['label' => $title, 'class' => 'active'];
?>

<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
   <?= \backend\widgets\alert\AlertWidget::widget() ?>
   <div class="col-md-12 col-sm-12 col-xs12">
      <?= $this->render('partials/_form.php', ['model' => $model]); ?>
   </div>
</div>