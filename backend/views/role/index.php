<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Role Manager';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>
<div class="clearfix"></div>
<div class="c-page-container c-page-container-md">
    <div class="clearfix"></div>
    <!--Page content start-->
    <div class="o-pagecontent">
        <div class="o-pagecontent__head">
            <div class="o-pagecontent__head-title"><?= $this->title ?></div>
            <div class="o-pagecontent__head-actionwrap">
                <a href="javascript:;" class="c-button c-button-uppertext c-button-withshadow c-button-white js-filter">
                    <i class="fa fa-plus icon" aria-hidden="true"></i>
                    New Role
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
        <?= $this->render('_new-role.php', ['model' => $model]) ?>
        <div class="clearfix"></div>
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
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'id',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                ],
                                [
                                    'attribute' => 'name',
                                    'filter' => false,
                                    'sortLinkOptions' => ['class' => 'sort'],
                                ],
                                [
                                    'header' => 'Action',
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                                                        'title' => Yii::t('yii', 'Delete'),
                                                        'data-url' => Url::toRoute(['role/delete', 'id' => $model->id]),
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
