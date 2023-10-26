<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\ApplicantPost;

$this->title = 'Post';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';

$this->registerJs("ApplicantController.createUpdate()");
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Applicant / Post', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <?= $this->render('partials/_search-form.php', ['model' => $searchModel, 'url' => ['applicant/post'], 'isPost' => true]) ?>
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
                                        'label' => 'Application Date',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function ($model) {
                                            return \components\Helper::convertNetworkTimeZone($model->created_on, 'd-m-Y', Yii::$app->timeZone, 'UTC');
                                        }
                                    ],
                                    [
                                        'attribute' => 'year',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function($model) {
                                            return !empty($model->classified->recruitment_year) && !empty($model->classified->recruitment_year) ? $model->classified->recruitment_year : '';
                                        }
                                    ],
                                    [
                                        'attribute' => 'adv_id',
                                        'label' => 'Advertisement',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function($model) {
                                            return !empty($model->classified_id) ? $model->classified->title : '';
                                        }
                                    ],
                                    /*[
                                        'attribute' => 'post_id',
                                        'label' => 'Post',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function($model) {
                                            return !empty($model->post) ? $model->post->code : '';
                                        }
                                    ],*/
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
                                        'attribute' => 'application_no',
                                        'label' => 'Application No',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort']
                                    ],
                                    [
                                        'attribute' => 'application_status',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'contentOptions' => ['class' => 'js-applicationStatus'],
                                        'value' => function($model) {
                                            return ApplicantPost::getApplicationStatus($model->application_status);
                                        }
                                    ],
                                    [
                                        'attribute' => 'payment_status',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function($model) {
                                            return ApplicantPost::getPaymentStatus($model->payment_status);
                                        }
                                    ],
//                                    
                                    [
                                        'header' => \Yii::t('yii', 'action'),
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<div class="action-bars">{archive} {view} {view-transaction} {reset-password} {cancel-post}</div>',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                if ($model->payment_status == ApplicantPost::STATUS_PAID && $model->application_status == ApplicantPost::APPLICATION_STATUS_SUBMITTED) {
                                                    return Html::a('<em class="far fa-eye"></em>', Url::toRoute(['applicant/preview', 'guid' => $model->guid]), [
                                                                'title' => Yii::t('yii', \Yii::t('yii', 'View Post Details')),
                                                                'class' => 'update action-bars__link',
                                                                'data-url' => Url::toRoute(['location/district/update', 'guid' => $model->guid]),
                                                                'data-pjax' => 0,
                                                                'target' => '_blank',
                                                    ]);
                                                }
                                            },
                                            'view-transaction' => function ($url, $model, $key) {
                                                return Html::a('<em class="fa fa-list"></em>', Url::toRoute(['transaction/index', 'TransactionSearch' => ['applicant_guid' => $model->applicant->guid]]), [
                                                            'title' => Yii::t('yii', \Yii::t('yii', 'View Transactions')),
                                                            'class' => 'update action-bars__link',
                                                            'data-pjax' => 0,
                                                            'target' => '_blank',
                                                ]);
                                            },
                                            'reset-password' => function ($url, $model, $key) {
                                                return Yii::$app->user->hasAdminRole() ? Html::a('<em class="fa fa-key"></em>', Url::toRoute(['applicant/reset-password', 'guid' => $model->applicant->guid]), [
                                                            'title' => Yii::t('yii', \Yii::t('yii', 'Reset Password')),
                                                            'class' => 'update action-bars__link globalModalButton',
                                                            'target' => '_blank',
                                                ]) : '';
                                            },
                                            'cancel-post' => function ($url, $model, $key) {
                                                return (ApplicantPost::checkStatusForCancel($model->id) === ApplicantPost::APPLICATION_STATUS_SUBMITTED && (Yii::$app->user->hasAdminRole())) ? Html::a('<em class="fa fa-times"></em>', 'javascript:;', [
                                                    'data-guid' => $model->guid,
                                                    'data-id' => $model->id,
                                                    'title' => Yii::t('yii', \Yii::t('yii', 'Cancel Post')),
                                                    'class' => 'update action-bars__link js-cancelPost',
                                                    'target' => '_blank',
                                                ]) : '';
                                            },
                                            'archive' => function ($url, $model, $key) {
                                                if ($model->application_status == ApplicantPost::APPLICATION_STATUS_ARCHIVE) {
                                                    $applicantDocument = common\models\ApplicantDocument::findByApplicantPostId($model->id, [
                                                                'selectCols' => [new \yii\db\Expression("media.cdn_path")],
                                                                'joinWithMedia' => 'innerJoin'
                                                    ]);
                                                    return Html::a('<em class="fa fa-print"></em>', Yii::$app->amazons3->getPrivateMediaUrl($applicantDocument['cdn_path']), [
                                                                'title' => Yii::t('yii', \Yii::t('yii', 'Print Application')),
                                                                'class' => 'update action-bars__link',
                                                                'target' => '_blank',
                                                    ]);
                                                }
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

