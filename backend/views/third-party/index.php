<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Third Party Manager';

$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';


$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Third Party', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
$this->params['breadcrumbMenu'][] = ['label' => 'Create', 'icon' => 'fa fa-plus', 'url' => Url::toRoute(['create'])];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
            </div>
            <div class="adm-c-tableGrid__container">
                <?php
                $gridView = GridView::begin([
                            'tableOptions' => [
                                'class' => 'table'
                            ],
                            'dataProvider' => $dataProvider,
                            'summary' => "<div class='summary'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                            'layout' => "<div class='summary adm-u-flexed adm-u-align-center adm-u-justify-btw cmb-15'>{summary}<div class='summary-actions'>{pager}</div></div><div class='adm-c-tableGrid__box table-responsive withScroll'>\n{items}</div>",
                            'emptyTextOptions' => ['class' => 'empty text-center'],
                            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                            'pager' => [
                                'options' => ['class' => 'pagination  cmr-10'],
                                'prevPageLabel' => 'Previous',
                                'nextPageLabel' => 'Next',
                                'linkOptions' => ['class' => 'page-link'],
                                'linkContainerOptions' => ['class' => 'page-item']
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'type',
                                    'filter' => false,
                                    'format' => 'raw',
                                    'sortLinkOptions' => ['class' => 'sort'],
                                    'value' => function ($model) {
                                        return strtoupper($model->type);
                                    },
                                ],
                                [
                                    'attribute' => 'is_active',
                                    'label' => 'Status',
                                    'format' => 'raw',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                    'value' => function ($model) {
                                        if ($model->is_active) {
                                            return "<span class='badge badge-success'><span class='title'>Active</span>";
                                        } else {
                                            return "<span class='badge badge-danger'><span class='title'>Inactive</span>";
                                        }
                                    },
                                ],
                                [
                                    'header' => 'Action',
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<div class="action-bars">{update} {delete}</div>',
                                    'visibleButtons' => [
                                        'update' => true,
                                        'delete' => false
                                    ],
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<em class="far fa-edit"></em>', Url::toRoute(['third-party/update', 'guid' => $model->guid]), [
                                                        'title' => Yii::t('yii', 'Update'),
                                                        'data-pjax' => '0',
                                                        'class' => 'update action-bars__link'
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<em class="far fa-trash-alt"></em>', 'javascript:void(0);', [
                                                        'title' => Yii::t('yii', 'Delete'),
                                                        'data-url' => Url::toRoute(['third-party/delete', 'id' => $model->id]),
                                                        'class' => 'delete action-bars__link deleteConfirmation'
                                            ]);
                                        },
                                    ],
                                ],
                            ],
                ]);

                $gridView->end();
                ?>
            </div>
        </div>
    </section>
</div>