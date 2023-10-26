<?php


use \yii\helpers\Url;
$this->title = 'View Details';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'User Manager',   'url' => Url::toRoute(['user/index'])];
$this->params['breadcrumb'][] = ['label' => 'View details',  'class' => 'active'];
?>

<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <div class="u-generic__box design2">
        <div class="adm-c-readOnly__screen design1">
            <div class="adm-c-sectionHeader design4">
                <div class="adm-c-sectionHeader__container">
                    <div class="adm-c-sectionHeader__label">
                        <div class="adm-c-sectionHeader__label__title fs16__medium">User Details</div>
                    </div>
                </div>
            </div>
            <div class="adm-c-readOnly__screen__container">
                <ul class="adm-c-readOnly__screen__container__grid col2">
                <li class="adm-c-readOnly__screen__container__list">
                        <div class="field-label">Username</div>
                        <div class="field-value"><?= $model->firstname.' '.$model->lastname ?></div>
                    </li>
                    <li class="adm-c-readOnly__screen__container__list">
                        <div class="field-label">Username</div>
                        <div class="field-value"><?= $model->username ?></div>
                    </li>
                    <li class="adm-c-readOnly__screen__container__list ">
                        <div class="field-label">Email</div>
                        <div class="field-value"><?= components\Helper::emailConversion($model->email) ?></div>
                    </li>

                    <li class="adm-c-readOnly__screen__container__list">
                        <div class="field-label">Status</div>
                        <div class="field-value"><?= $model->status == 10 ? "Active" : "Inactive" ?></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>