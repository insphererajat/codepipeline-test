
 <?php

use \yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$title = (isset($model->id) && $model->id > 0) ? 'Edit Group' : 'Create Group';
$this->title = $title;

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/admin/home/index'])];
$this->params['breadcrumb'][] = ['label' => 'User Groups', 'url' => Url::toRoute(['/admin/user-group/index'])];
$this->params['breadcrumb'][] = ['label' => $title, 'class' => 'active'];


?>
<?= \frontend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
 <?= \frontend\widgets\alert\AlertWidget::widget() ?>
 <div class="col-md-12 col-sm-12 col-xs12 cmt-20">
    <?= $this->render('partials/_form.php', ['model' => $model]); ?>
 </div>
</div>