<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use common\models\LogProfile;
use common\models\ApplicantPost;
use common\models\MstPost;
use common\models\ApplicantDetail;

$this->title = 'OTR Update';
$this->params['bodyClass'] = 'frame__body';
$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>
<?= $this->render('/layouts/partials/flash-message.php'); ?>
<div class="main-body">
    <div class="register__wrapper">
        <div class="c-tabbing c-tabbing-xs design4">
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'logProfileForm',
                            'enableClientValidation' => false,
                            'enableAjaxValidation' => false,
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter js-formCls',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= Html::hiddenInput('LogProfileForm[media_id]', $model->media_id, ['class' => 'inputPhoto']); ?>
                <?= Html::activeHiddenInput($model, 'id') ?>
                <?= Html::activeHiddenInput($model, 'guid') ?>
                <?= $this->render('partials/_update-form.php', ['model' => $model, 'form' => $form]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-12">

                <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2 withshadow" id="collapseOne" data-time="<?= common\models\LogOtp::VALIDATION_TIME ?>">
                    <div class="adm-c-tableGrid__wrapper">
                        <div class="adm-c-tableGrid__container adm-c-tableGrid__container--scrolling">
                            <div class="adm-c-tableGrid__box table-responsive withScroll">
                                <?php
                                $gridView = GridView::begin([
                                            'tableOptions' => [
                                                'class' => 'table'
                                            ],
                                            'dataProvider' => $dataProvider,
                                            'summary' => "<div class='summary'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                                            'layout' => "<div class='c-table__elements-gridinfo'><div class='grid__results'><div class='grid__results-summary'>{summary}</div><div class='grid__results-pagination'><div class='c-pagination'>{pager}</div></div></div></div><div class='adm-c-tableGrid__box table-responsive withScroll'><div class='c-table__elements c-table__elements--withborder'></div><div class='$noDataClass'>{items}</div></div>",
                                            'emptyTextOptions' => ['class' => 'empty text-center'],
                                            'pager' => [
                                                'prevPageLabel' => 'Previous',
                                                'nextPageLabel' => 'Next',
                                                'linkOptions' => ['class' => 'page-link'],
                                                'linkContainerOptions' => ['class' => 'page-item']
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
                                                    'attribute' => 'id',
                                                    'header' => 'Old Name',
                                                    'filter' => false,
                                                    'value' => function($model) {
                                                        if (!empty($model->old_value)) {
                                                            $oldValue = \yii\helpers\Json::decode($model->old_value);
                                                            $name = isset($oldValue['name']) ? $oldValue['name'] : '';
                                                        } else {
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
                                                        } else {
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
                                                        } else {
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
                                                ]
                                            ],
                                ]);
                                $gridView->end();
                                ?> 
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>
</div>