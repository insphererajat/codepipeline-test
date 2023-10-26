<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Country Manager';
$this->params['breadcrumbs'][] = ['label' => 'Country'];
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data': '';
$this->registerJs('LocationController.createUpdate();');
?>
<div class="page__bar">
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="section">
                <h2 class="section__heading"><?= $this->title ?></h2>
            </div>
        </div>
    </div>
</div>
</div>
<div class="page-main-content">
    <section class="container" >
        <div class="content-wrap">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?= $this->render('/layouts/partials/flash-message.php') ?>
                    <section class="widget__wrapper">
                        <div class="table__structure table__structure-scrollable">
                            <div class="table__structure__head">
                                <div class="section-head">
                                    <div class="section-head--title"><?=$this->title;?></div>
                                    <div class="section-head__optionSets">
                                        <div class="section-head__optionSets--filter"><i class="fa fa-search"></i>&nbsp;&nbsp;Search <i class="icon fa fa-angle-down"></i></div>
                                        <div class="section-head__optionSets--addButton">
                                            <a class="" href="<?= yii\helpers\Url::toRoute(['location/add-country']) ?>"><i class="fa fa-plus"></i> New</a>
                                        </div>
                                    </div>
                                </div>
                                <?= $this->render('/location/partials/_search-form.php', ['model' => $searchModel,'url'=>  Url::toRoute(['location/country'])]) ?>
                            </div>
                            <?php Pjax::begin(['id' => 'DataList']); ?>
                            <?php
                            $gridView = GridView::begin([
                                        'tableOptions' => [
                                            'class' => 'table'
                                        ],
                                        'summary' => "<div class='summary'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                                        'layout' => "<div class='table-responsive scrolling $noDataClass'>{items}</div>\n<div class='table__bottom-section'><div class='summary'>{summary}</div>\n<div class='table__bottom-section--pagination'>{pager}</div></div>",
                                        'dataProvider' => $dataProvider,
                                        'filterSelector' => "input[name='CountrySearch[search]']",
                                        'emptyTextOptions' => ['class' => 'empty text-center'],
                                        'pager' => [
                                            'prevPageLabel' => 'Previous',
                                            'nextPageLabel' => 'Next',
                                        ],
                                        'columns' => [
                                            [
                                                'attribute' => 'code',
                                            ],
                                            [
                                                'attribute' => 'name',
                                                'value' => function ($model) {
                                                    return strtoupper($model->name);
                                                },
                                            ],
                                            [
                                                'attribute' => 'status',
                                                'header' => 'STATUS',
                                                'format' => 'html',
                                                'value' => function ($data) {
                                                    return (($data->is_active) == 1) ? "<div class='check-status'><span class='badge badge-success'>Active</span><i class='fa fa-spin fa-spinner hide'></i></div>" : "<div class='check-status'><span class='badge badge-danger'>Inactive</span><i class='fa fa-spin fa-spinner hide'></i></div>";
                                                },
                                                'headerOptions' => array(
                                                    'width' => '10%'
                                                ),
                                                'contentOptions' => function ($model) {
                                            return array(
                                                'align' => 'center',
                                                'class' => 'updateLocationStatus',
                                                'data-type' => 'country',
                                                'data-url' => Url::toRoute(['location/status', 'guid' => $model->guid,'type'=>'country'])
                                            );
                                        }
                                                ,
                                                'filter' => array('1' => 'Active', '0' => 'Inactive'),
                                            ],
                                            [
                                                'header' => 'ACTION',
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{update} {delete}',
                                                'buttons' => [
                                                    'update' => function ($url, $model, $key) {
                                                        return Html::a('<i class="fa fa-pencil"></i>', Url::toRoute(['location/update-country', 'guid' => $model->guid]), [
                                                                    'title' => Yii::t('yii', 'Update'),
                                                                    'data-pjax' => '0',
                                                                    'class' => 'update'
                                                        ]);
                                                    },
                                                            'delete' => function ($url, $model, $key) {
                                                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:;', [
                                                                    'title' => Yii::t('yii', 'Delete'),
                                                                    'data-url' => Url::toRoute(['location/delete', 'guid' => $model->guid ,'type'=>'country']),
                                                                    'class' => 'deleteRecord',
                                                                    'data-method' => 'post',
                                                                    'data-type' => 'country',
                                                                    'data-pjax' => '0',
                                                                    'class' => 'delete'
                                                        ]);
                                                    },
                                                        ],
                                                        'headerOptions' => [
                                                            'width' => '15%',
                                                            'class' => 'scrolling__element head'
                                                        ],
                                                        'contentOptions' => [
                                                            'class' => 'scrolling__element'
                                                        ]
                                                    ],
                                                ],
                                    ]);

                                    $gridView->end();
                                    ?>
                                    <?php Pjax::end() ?>
                        </div>
                    </section>
                </div> 
            </div>
        </div>
    </section>
</div>