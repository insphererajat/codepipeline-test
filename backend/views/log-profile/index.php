<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\LogProfile;
use common\models\ApplicantPost;
use common\models\ApplicantDetail;
use common\models\MstPost;
use common\models\caching\ModelCache;

$this->title = 'Applicant Log Manager';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'OTP', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
$this->registerJs("LogProfileController.createUpdate()");
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
                                'emptyTextOptions' => [ 'class' => 'empty text-center'],
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
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Status',
                                        'filter' => false,
                                        'contentOptions' => ['class' => 'js-applicationStatus'],
                                        'value' => function($model) {
                                            return LogProfile::statusDropdown($model->status);
                                        }
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'header' => 'Remarks',
                                        'filter' => false,
                                        'value' => function($model) {
                                            return $model->logProfileActivities[0]->remarks;
                                        }
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'header' => 'Mobile',
                                        'filter' => false,
                                        'value' => function($model) {
                                            return $model->applicant->mobile;
                                        }
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'header' => 'Old Name',
                                        'filter' => false,
                                        'value' => function($model) {
                                            if (!empty($model->old_value)) {
                                                $oldValue = \yii\helpers\Json::decode($model->old_value);
                                                $name = isset($oldValue['name']) ? $oldValue['name'] : '';
                                            }
                                            else {
                                                $name = $model->applicant->name;
                                            }
                                            return $name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'header' => 'Old Father Name',
                                        'filter' => false,
                                        'value' => function($model) {
                                            if (!empty($model->old_value)) {
                                                $oldValue = \yii\helpers\Json::decode($model->old_value);
                                                $data = isset($oldValue['father_name']) ? $oldValue['father_name'] : '';
                                            }
                                            else {
                                                $applicantPostModel = ApplicantPost::findByApplicantId($model->applicant_id, ['postId' => MstPost::MASTER_POST]);
                                                $applicantDetailModel = ApplicantDetail::findByApplicantPostId($applicantPostModel['id'], ['selectCols' => ['id', 'father_name', 'date_of_birth']]);
                                                $data = isset($applicantDetailModel['father_name']) ? $applicantDetailModel['father_name'] : '';
                                            }
                                            return $data;
                                        }
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'header' => 'Old DOB',
                                        'filter' => false,
                                        'value' => function($model) {
                                            if (!empty($model->old_value)) {
                                                $oldValue = \yii\helpers\Json::decode($model->old_value);
                                                $data = isset($oldValue['date_of_birth']) ? $oldValue['date_of_birth'] : '';
                                            }
                                            else {
                                                $applicantPostModel = ApplicantPost::findByApplicantId($model->applicant_id, ['postId' => MstPost::MASTER_POST]);
                                                $applicantDetailModel = ApplicantDetail::findByApplicantPostId($applicantPostModel['id'], ['selectCols' => ['id', 'father_name', 'date_of_birth']]);
                                                $data = isset($applicantDetailModel['date_of_birth']) ? $applicantDetailModel['date_of_birth'] : '';
                                            }
                                            return $data;
                                        }
                                    ],
                                    [
                                        'attribute' => 'new_value',
                                        'header' => 'Name',
                                        'filter' => false,
                                        'value' => function($model) {
                                            $newValue = \yii\helpers\Json::decode($model->new_value);
                                            return isset($newValue['name']) ? $newValue['name'] : '';
                                        }
                                    ],
                                    [
                                        'attribute' => 'new_value',
                                        'header' => 'Father Name',
                                        'filter' => false,
                                        'value' => function($model) {
                                            $newValue = \yii\helpers\Json::decode($model->new_value);
                                            return isset($newValue['father_name']) ? $newValue['father_name'] : '';
                                        }
                                    ],
                                    [
                                        'attribute' => 'new_value',
                                        'header' => 'DOB',
                                        'filter' => false,
                                        'value' => function($model) {
                                            $newValue = \yii\helpers\Json::decode($model->new_value);
                                            return isset($newValue['date_of_birth']) ? $newValue['date_of_birth'] : '';
                                        }
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'header' => 'Document',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            $media = common\models\LogProfileMedia::getMediaByLogProfileId($model->id);
                                            return ($media !== null) ? Html::a(Html::img(Yii::$app->amazons3->getPrivateMediaUrl($media['cdn_path']), ['height' => 30, 'width' => 50]), Yii::$app->amazons3->getPrivateMediaUrl($media['cdn_path']), ['class' => 'fancybox']) : '';
                                        }
                                    ],
                                    [
                                        'header' => \Yii::t('yii', 'Action'),
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<div class="action-bars">{change-status}</div>',
                                        'buttons' => [
                                            'change-status' => function ($url, $model, $key) {
                                                return ($model->status == LogProfile::STATUS_PENDING) ? Html::a('<em class="fa fa-edit"></em>', 'javascript:;', [
                                                    'data-guid' => $model->guid,
                                                    'data-id' => $model->id,
                                                    'title' => Yii::t('yii', \Yii::t('yii', 'Update Status')),
                                                    'class' => 'update action-bars__link js-changeStatus',
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