
<?php

use \yii\helpers\Url;
$title = (isset($model->id) && $model->id > 0) ? 'Update' : 'Create';

$this->title = $title;

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Qualifications', 'url' => Url::toRoute(['/qualification/index'])];
$this->params['breadcrumb'][] = ['label' => $title, 'class' => 'active'];

$this->registerJs('QualificationController.createUpdate();');
?>

<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
   <?= \backend\widgets\alert\AlertWidget::widget() ?>
   <div class="col-md-12 col-sm-12 col-xs12">
      <?= $this->render('partials/_form.php', ['model' => $model]); ?>
   </div>
</div>