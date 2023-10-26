<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Transaction;

$this->title = 'Transaction';
$statusArr = Transaction::getStatusDropdown();
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
$this->registerJs("ApplicantController.createUpdate()");
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Transaction', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
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
                                'summary' => "<div class='summary-result'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                                'layout' => "<div class='summary adm-u-flexed adm-u-align-center adm-u-justify-btw cmb-15'>{summary}<div class='summary-actions'>{pager}</div></div><div class='adm-c-tableGrid__box table-responsive withScroll'>\n{items}</div>",
                                'filterSelector' => "input[name='ListTypeSearch[name]'],input[name='ListTypeSearch[type]']",
                                'emptyTextOptions' => ['class' => 'empty text-center'],
                                'pager' => [
                                    'prevPageLabel' => 'Previous',
                                    'nextPageLabel' => 'Next',
                                    'linkContainerOptions' => ['class' => 'page-item'],
                                    'linkOptions' => ['class' => 'page-link'],
                                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link', 'href' => '#']
                                ],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'attribute' => 'created_on',
                                        'label' => 'Date',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function ($model) {
                                            return \components\Helper::convertNetworkTimeZone($model->created_on, 'd F Y', Yii::$app->timeZone, 'UTC');
                                        }
                                    ],
                                    [
                                        'attribute' => 'classified_id',
                                        'label' => 'Classified',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            return common\models\ApplicantFee::getClassifiedName($model->applicant_fee_id);
                                        }
                                    ],
                                    [
                                        'attribute' => 'type',
                                        'label' => 'Gateway',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                    ],
                                    [
                                        'attribute' => 'transaction_id',
                                        'label' => 'Transaction No.',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                    ],
                                    [
                                        'attribute' => 'gateway_id',
                                        'label' => 'Gateway Transaction No.',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                    ],
                                    [
                                        'attribute' => 'amount',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                    ],
                                    [
                                        'attribute' => 'aplicant_id',
                                        'label' => 'Applicant Name',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            return $model->applicant->name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'aplicant_id',
                                        'label' => 'Email',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            return $model->applicant->email;
                                        }
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Status',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                    ],
                                    [
                                        'attribute' => 'is_consumed',
                                        'label' => 'Consumed Status',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function ($model) {
                                            return !empty(Transaction::getIsconsumedStatus()[$model->is_consumed]) ? Transaction::getIsconsumedStatus()[$model->is_consumed] : '';
                                        }
                                    ],
                                    [
                                        'header' => \Yii::t('yii', 'Action'),
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'buttons' => [
                                            'update-transaction' => function ($url, $model, $key) {
                                                return (Yii::$app->user->hasAdminRole() && $model->status != Transaction::TYPE_STATUS_PAID) ? Html::a('<i class="fa fa-reply"></i>', Url::toRoute(['schedule', 'transactionId' => $model->transaction_id]), [
                                                    'title' => Yii::t('yii', \Yii::t('yii', 'Update Transaction')),
                                                    'class' => 'action-bars__link',
                                                    'data-pjax' => 0
                                                ]) : '';
                                            },
                                            'view' => function ($url, $model, $key) {
                                                $applicantPost = $model->applicantFee->applicantPost;
                                                if ($model->status == Transaction::TYPE_STATUS_PAID && $applicantPost->payment_status == \common\models\ApplicantPost::STATUS_PAID && $applicantPost->payment_status == \common\models\ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
                                                    return Html::a('<em class="far fa-eye"></em>', Url::toRoute(['applicant/preview', 'guid' => $applicantPost->guid]), [
                                                                'title' => Yii::t('yii', \Yii::t('yii', 'View Post Details')),
                                                                'class' => 'update action-bars__link',
                                                                'target' => '_blank',
                                                                'data-pjax' => 0
                                                    ]);
                                                }
                                            },
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