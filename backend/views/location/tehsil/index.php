<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


$this->title = 'Tehsil Manager';

$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';

$this->registerJs('LocationController.tehsil();');
$this->registerJs('LocationController.getDistrict();');

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Tehsil',  'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
$this->params['breadcrumbMenu'][] = ['label' => 'Create', 'icon' => 'fa fa-plus', 'url' => Url::toRoute(['/location/tehsil/create'])];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <?= $this->render('../partials/_search-form.php', ['model' => $searchModel, 'url' => Url::toRoute(['location/tehsil/index'])]) ?>
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
                                'attribute' => 'state_code',
                                'header' => 'State',
                                'filter' => false,
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($model) {

                                    return !empty($model->state_code)  ? $model->stateCode->stateCode->name : '';
                                }
                            ],
                            [
                                'attribute' => 'district_code',
                                'header' => 'District',
                                'filter' => false,
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($model) {

                                    return !empty($model->district_code)  ? $model->stateCode->name : '';
                                }
                            ],
                            [
                                'attribute' => 'code',
                                'filter' => false,
                                'sortLinkOptions' => ['class' => 'sort'],
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
                                'attribute' => 'name',
                                'header' => 'No of Tehsil',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $tehsilModel = common\models\location\MstTehsil::getTehsilList(['stateCode' => $model->state_code, 'districtCode' => $model->code, 'countOnly' => TRUE]);
                                    return Html::a($tehsilModel, Url::toRoute(['location/tehsil/index']), [
                                        'title' => Yii::t('yii', \Yii::t('yii', 'update')),
                                        'class' => 'action-bars__link update',
                                        'data-pjax' => 0
                                    ]);
                                }
                            ],
                            [
                                'attribute' => 'is_active',
                                'format' => 'html',
                                'enableSorting' => false,
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
                                        return Html::a('<i class="fa fa-pencil-alt"></i>', Url::toRoute(['location/tehsil/update', 'guid' => $model->guid]), [
                                            'title' => Yii::t('yii', \Yii::t('yii', 'update')),
                                            'class' => 'action-bars__link',

                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="far fa-trash-alt"></i>', 'javascript:;', [
                                            'title' => Yii::t('yii', \Yii::t('yii', 'delete')),
                                            'data-url' => Url::toRoute(['location/tehsil/delete', 'guid' => $model->guid]),
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