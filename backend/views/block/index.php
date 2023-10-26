<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;

$this->title = 'Block Manager';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>

<div class="clearfix"></div>
<div class="c-page-container c-page-container-md">
    <div class="clearfix"></div>
    <?= $this->render('/layouts/partials/flash-message.php') ?>
    
    <div class="o-pagecontent">
        <div class="o-pagecontent__head">
            <div class="o-pagecontent__head-title"><?= $this->title ?></div>
            <div class="o-pagecontent__head-actionwrap">
                <a href="<?= Url::toRoute(['block/create']) ?>" class="c-button c-button-uppertext c-button-withshadow c-button-white">
                    <i class="fa fa-plus icon" aria-hidden="true"></i>
                    New
                </a>
                <a href="javascript:;" class="c-button c-button-uppertext c-button-withshadow c-button-white js-filter">
                    <i class="fa fa-search icon" aria-hidden="true"></i>
                    Filter Search
                </a>
            </div>
        </div>
        <?= $this->render('partials/_search-form.php',  ['searchModel' => $searchModel]) ?>
        <div class="o-pagecontent__body o-pagecontent__body--whitebg">
            <div class="c-table__structure hover--table scrollable c-table__structure-lg c-table__structure-md c-table__structure-sm c-table__structure-xs">

                <?php
                $gridView = GridView::begin([
                            'tableOptions' => [
                                'class' => 'table'
                            ],
                            'dataProvider' => $dataProvider,
                            'summary' => "<div class='summary'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                            'layout' => "<div class='c-table__elements c-table__elements--withborder'><div class='c-table__elements-gridinfo'><div class='grid__results'><div class='grid__results-summary'>{summary}</div><div class='grid__results-pagination'><div class='c-pagination'>{pager}</div></div></div></div></div><div class='table-responsive table-responsive-xl js-table-scrolling $noDataClass'>{items}</div>",
                            'emptyTextOptions' => ['class' => 'empty text-center'],
                            'pager' => [
                                'prevPageLabel' => 'Previous',
                                'nextPageLabel' => 'Next',
                                'linkOptions' => ['class' => 'page-link'],
                                'linkContainerOptions' => ['class' => 'page-item']
                            ],
                            'columns' => [
                                [
                                    'attribute' => 'name',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort']
                                ],
                                [
                                    'attribute' => 'code',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort']
                                ],
                                [
                                    'attribute' => 'country_code',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                    'value' => 'districtCode.stateCode.countryCode.name'
                                ],
                                [
                                    'attribute' => 'state_code',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                    'value' => 'districtCode.stateCode.name'
                                ],
                                [
                                    'attribute' => 'district_code',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                    'value' => 'districtCode.name'
                                ],
                                [
                                    'attribute' => 'is_active',
                                    'label' => 'Status',
                                    'format' => 'html',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                    'value' => function ($data) {
                                        return (($data->is_active) == 1) ? "<div class='check-status'><span class='badge badge-success'>Active</span></div>" : "<div class='check-status'><span class='badge badge-danger'>Inactive</span></div>";
                                    },
                                    'headerOptions' => array(
                                        'width' => '10%'
                                    ),
                                    'filter' => array('1' => 'Active', '0' => 'Inactive'),
                                ],
                                [
                                    'header' => 'Action',
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-pencil"></i>', Url::toRoute(['block/update', 'guid' => $model->guid]), [
                                                        'title' => Yii::t('yii', 'Update'),
                                                        'data-pjax' => '0',
                                                        'class' => 'update'
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                                                        'title' => Yii::t('yii', 'Delete'),
                                                        'data-url' => Url::toRoute(['block/delete', 'guid' => $model->guid]),
                                                        'class' => 'delete deleteConfirmation'
                                            ]);
                                        },
                                    ],
                                    'headerOptions' => [
                                        'width' => '15%',
                                        'class' => 'scrolling__element head'
                                    ],
                                    'contentOptions' => [
                                        'class' => 'scrolling__element no-dropdown p-0'
                                    ]
                                ],
                            ],
                ]);

                $gridView->end();
                ?> 

            </div>
        </div>
        <!--Page content end-->
        <div class="clearfix"></div>
    </div>
</div>
