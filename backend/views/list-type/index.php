<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


$this->title = 'List Type Manager';

$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';


$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'List Type Manager',  'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
$this->params['breadcrumbMenu'][] = ['label' => 'Create', 'icon' => 'fa fa-plus', 'url' => Url::toRoute(['/list-type/create'])];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <?= $this->render('partials/_search-form.php', ['model' => $searchModel]) ?>
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
                            'options' => ['class' =>  'pagination  cmr-10'],
                            'prevPageLabel' => 'Previous',
                            'nextPageLabel' => 'Next',
                            'linkOptions' => ['class' => 'page-link'],
                            'linkContainerOptions' => ['class' => 'page-item']
                        ],
                        'columns' => [
                            [
                                'attribute' => 'parent_id',
                                'header' => 'Parent',
                                'filter' => false,
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function($model) {
                                     return !empty($model->parent_id) ? $model->parent->name : '-';
                                 }
                            ],
                            [
                                'attribute' => 'name',
                                'filter' => false,
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($model) {
                                    return strtoupper($model->name);
                                },
                            ],
                            [
                                    'attribute' => 'is_active',
                                    'label' => "Status",
                                    'format' => 'html',
                                    'enableSorting' => false,
                                    'contentOptions' => function ($model) {
                                        return [
                                            'class' => 'updateStatusGrid',
                                            'data-url' => Url::toRoute(['list-type/status', 'guid' => $model->guid])
                                        ];
                                    },
                                    'value' => function ($data) {
                                        return (($data->is_active) == 1) ? "<a href='javascript:void(0)' title='Active'><span class='badge badge-success'>" . \Yii::t('yii', 'active') . "</span><i class='fa fa-spin fa-spinner hide'></i></a>" : "<a href='javascript:void(0)' title='Inactive'><span class='badge badge-danger'>" . \Yii::t('yii', 'inactive') . "</span><i class='fa fa-spin fa-spinner hide'></i></a>";
                                    },
                                ],
                                    [
                                        'header' => \Yii::t('yii', 'action'),
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {delete}',
                                        'buttons' => [
                                            'update' => function ($url, $model, $key) {
                                                return Html::a('<i class="fa fa-edit"></i>', Url::toRoute(['list-type/update', 'guid' => $model->guid]), [
                                                            'title' => Yii::t('yii', \Yii::t('yii', 'update')),
                                                            'class' => 'action-bars__link',
                                                            'data-pjax' => 0
                                                ]);
                                            },
                                                    'delete' => function ($url, $model, $key) {
                                                return Html::a('<i class="far fa-trash-alt"></i>', 'javascript:;', [
                                                            'title' => Yii::t('yii', \Yii::t('yii', 'delete')),
                                                            'data-url' => Url::toRoute(['list-type/delete', 'guid' => $model->guid]),
                                                            'class' => 'action-bars__link delete deleteConfirmation',
                                                ]);
                                            }
                                                ],
                                                'contentOptions' => [
                                                    'class' => 'action-bars'
                                                ]
                                            ]
                        ],
                    ]);

                    $gridView->end();
                    ?>
            </div>
        </div>
    </section>
</div>