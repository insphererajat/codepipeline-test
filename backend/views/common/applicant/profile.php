<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Profile Summary';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';

$this->registerJs("ApplicantController.createUpdate()");
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Applicant / Profile',  'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <?= $this->render('partials/_search-profile-form.php', ['model' => $searchModel, 'url' => ['applicant/profile']]) ?>
            </div>
            <div class="adm-c-tableGrid__container">
                    <?php
                    $gridView = GridView::begin([
                                'tableOptions' => [
                                    'class' => 'table'
                                ],
                                'dataProvider' => $dataProvider,
                                'summary' => "<div class='summary-result'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                                'layout' => "<div class='summary adm-u-flexed adm-u-align-center adm-u-justify-btw cmb-15'>{summary}<div class='summary-actions'>{pager}</div></div><div class='adm-c-tableGrid__box table-responsive withScroll'>\n{items}</div>",
                                'filterSelector' => "input[name='ListTypeSearch[name]'],input[name='ListTypeSearch[type]']",
                                'emptyTextOptions' => [ 'class' => 'empty text-center'],
                                'pager' => [
                                    'prevPageLabel' => 'Previous',
                                    'nextPageLabel' => 'Next',
                                    'linkContainerOptions' => [ 'class' => 'page-item'],
                                    'linkOptions' => ['class' => 'page-link'],
                                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link', 'href' => '#']
                                ],
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    [
                                        'attribute' => 'created_on',
                                        'label' => 'Registration Date',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function ($model) {
                                            return \components\Helper::convertNetworkTimeZone($model->created_on, 'd-m-Y', Yii::$app->timeZone, 'UTC');
                                        }
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort']
                                    ],
                                    [
                                        'attribute' => 'email',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function ($model) {
                                            return components\Helper::emailConversion($model->email);
                                        }
                                    ],
                                    [
                                        'attribute' => 'mobile',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort']
                                    ],
                                    [
                                        'attribute' => 'is_active',
                                        'label' => 'Status',
                                        'format' => 'html',
                                        'enableSorting' => false,
                                        'contentOptions' => function ($model) {
                                            return [
                                                'class' => 'updateStatusGrid',
                                                'data-url' => Url::toRoute(['applicant/status', 'guid' => $model->guid
                                                        ]
                                            )];
                                        },
                                        'value' => function ($data) {
                                            return (($data->is_active) == 1) ? "<a href='javascript:void(0)' title='Active'><span class='badge badge-success'>" . \Yii::t('yii', 'active') . "</span><i class='fa fa-spin fa-spinner hide'></i></a>" : "<a href='javascript:void(0)' title='Inactive'><span class='badge badge-danger'>" . \Yii::t('yii', 'inactive') . "</span><i class='fa fa-spin fa-spinner hide'></i></a>";
                                        },
                                    ],
                                    [
                                        'header' => \Yii::t('yii', 'action'),
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<div class="action-bars">{view} {reset-password}</div>',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a('<em class="fa fa-eye"></em>', Url::toRoute(['applicant/view', 'guid' => $model->guid]), [
                                                            'title' => Yii::t('yii', \Yii::t('yii', 'View Post')),
                                                            'class' => 'update action-bars__link',
                                                            'data-pjax' => 0,
                                                            'target' => '_blank'
                                                ]);
                                            },
                                            'reset-password' => function ($url, $model, $key) {
                                                return (Yii::$app->user->hasAdminRole() || Yii::$app->user->hasHelpdeskRole()) ? Html::a('<em class="fa fa-key"></em>', Url::toRoute(['applicant/reset-password', 'guid' => $model->guid, 'redirect' => Yii::$app->request->url]), [
                                                            'title' => Yii::t('yii', \Yii::t('yii', 'Reset Password')),
                                                            'class' => 'update action-bars__link globalModalButton',
                                                            'target' => '_blank',
                                                ]) : '';
                                            }
                                        ],
                                        'contentOptions' => [
                                            'class' => 'action-bars'
                                        ]
                                    ],
                                ],
                    ]);
                    $gridView->end();
                    ?> 
            </div>
        </div>
    </section>
</div>
<?= $this->render('partials/_modal.php'); ?>

