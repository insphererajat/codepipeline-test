<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\search\report\PostWiseForm;
use common\models\MstClassified;
use common\models\MstPost;

$this->title = 'Post Wise Report';
$this->registerJs("ReportController.createUpdate()");
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => $this->title, 'class' => 'active'];

?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <?= $this->render('partials/_search-form', ['model' => $model]);?>
            </div>
            <div class="adm-c-tableGrid__container">
                <div class="adm-c-tableGrid__box table-responsive">
                    <div class="filters-wrapper adm-u-pad7_10">
                        <?php if(!empty($records)): ?>
                        
                            <?php if($model->type == PostWiseForm::TYPE_PAYMENT_STATUS): ?>
                                <?= $this->render('partials/_payment-wise.php', ['records' => $records])?>
                        
                            <?php elseif($model->type == PostWiseForm::TYPE_APPLICATION_STATUS):?>
                                <?= $this->render('partials/_application-status-wise.php', ['records' => $records])?>
                        
                            <?php endif;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>