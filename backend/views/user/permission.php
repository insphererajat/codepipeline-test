

 <?php

use \yii\helpers\Url;

$title = "Permission";

$selectedPermission = [];
if ($model->id > 0) {
    $groupPermission = common\models\UserPermission::findByUserId($model->id, ['resultCount' => common\models\caching\ModelCache::RETURN_ALL]);
    if (!empty($groupPermission)) {
        $selectedPermission = \yii\helpers\ArrayHelper::getColumn($groupPermission, 'permission_name');
    }
}

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['/home/index'])];
$this->params['breadcrumb'][] = ['label' => 'User', 'url' => Url::toRoute(['/user/index'])];
$this->params['breadcrumb'][] = ['label' => $title, 'class' => 'active'];

$this->registerJs('UserController.individualPermission();');
$this->registerJs('UserController.addUserTaxonomy();');
$this->registerJs('UserController.deleteUserTaxonomy();');
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <div class="col-md-12 col-sm-12 col-xs12 cmt-20">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
       <div class="adm-basicBlock white adm-u-pad20_25">  
       <input type="hidden" id="userform-guid" value="<?= $model->guid ?>">
            <div class="row">
                    <?= $this->render('/permission/partials/_user-permission.php', ['selectedPermission' => $selectedPermission]) ?>
                    <?= $this->render('/permission/partials/_post-permission.php', ['selectedPermission' =>  $selectedPermission]) ?>
                    <?= $this->render('/permission/partials/_page-permission.php', ['selectedPermission' => $selectedPermission]) ?>
                    <?= $this->render('/permission/partials/_media-permission.php', ['selectedPermission' =>  $selectedPermission]) ?>
                    <?= $this->render('/permission/partials/_menu-permission.php', ['selectedPermission' =>  $selectedPermission]) ?>
            </div>
        </div>
    </div>
</div>