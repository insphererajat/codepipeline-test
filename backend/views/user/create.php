

 <?php

use \yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$title = (isset($model->id) && $model->id > 0) ? 'Update' : 'Create';
$this->title = $title;

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Users', 'url' => Url::toRoute(['/user/index'])];
$this->params['breadcrumb'][] = ['label' => $title, 'class' => 'active'];

if( $model->id > 0)  {
   $this->params['breadcrumbMenu'][] = ['label' => 'Permissions', 'icon' => 'fa fa-cog', 'url' => Url::toRoute(['/user/permission', 'guid' => $model->guid])];
}

?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
 <?= \backend\widgets\alert\AlertWidget::widget() ?>
 <div class="col-md-12 col-sm-12 col-xs12 cmt-20">
 <?= $this->render('partials/_form.php', ['model' => $model]); ?>
 </div>
</div>