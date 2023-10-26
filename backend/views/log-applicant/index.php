<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Applicant Log Manager';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'OTP', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
$this->registerJs("ApplicantController.createUpdate()");
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
                                    [
                                        'class' => 'yii\grid\SerialColumn'
                                    ],
                                    [
                                        'attribute' => 'created_on',
                                        'header' => 'Date',
                                        'filter' => false,
                                        'sortLinkOptions' => ['class' => 'sort'],
                                        'value' => function($model) {
                                            return \components\Helper::convertNetworkTimeZone($model->created_on, 'd-m-Y', Yii::$app->timeZone, 'UTC');
                                        }
                                    ],
                                    'type',
                                    'old_value',
                                    'new_value',
                                    'device_type',
                                    'ip_address',
                                    'useragent'
                                ],
                    ]);

                    $gridView->end();
                    ?>

            </div>
        </div>
    </section>
</div>