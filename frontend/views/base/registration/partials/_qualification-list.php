<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
use components\Helper;
$this->registerJs("RegistrationV2Controller.deleteQualification();");
?>
<div class="col-12 p-0 <?= isset($class) && !empty($class) ? $class : ''; ?>">
    <div class="c-sectionHeader c-sectionHeader-xs design1 mt-3 mb-3">
        <div class="c-sectionHeader__container">
            <div class="c-sectionHeader__label">
                <div class="c-sectionHeader__label__title fs16__medium">Qualification List</div>
            </div>
        </div>
    </div>
</div>



<div class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
    <div class="adm-c-tableGrid__container">
        <div class="adm-c-tableGrid__box table-responsive">
            <?php
            Pjax::begin(['id' => 'listType']);
            $gridView = GridView::begin([
                        'tableOptions' => [
                            'class' => 'table'
                        ],
                        'dataProvider' => $qualifications,
                        'summary' => "<div class='summary mt-2'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                        'layout' => "{summary}\n{items}\n<div class='table-bottom table-bottom--posRight'>{pager}</div>",
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
                                'attribute' => 'qualification_type_id',
                                'label' => 'Qualification Type',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (isset($data->qualification_type_id)) ? $data->qualificationType->name : '';
                                }
                            ],
                            'qualification_year',
                            [
                                'attribute' => 'qualification_degree_id',
                                'label' => 'Name Of Course',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (!empty($data->course_name)) ? $data->course_name : $data->qualificationDegree->name;
                                }
                            ],
                            [
                                'attribute' => 'board_university',
                                'label' => 'Board/University',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (!empty($data->other_board)) ? $data->other_board : $data->boardUniversity->name;
                                }
                            ],
                            [
                                'attribute' => 'result_status',
                                'label' => 'Result Status',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (!empty($data->result_status)) ? 'PASSED' : '';
                                }
                            ],
                            [
                                'attribute' => 'percentage',
                                'label' => 'Percentage',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (!empty($data->percentage)) ? $data->percentage : '';
                                }
                            ],
                            [
                                'header' => \Yii::t('admin', 'action'),
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) use($guid) {
                                        return Html::a('<i class="fa fa-pencil-alt"></i>', Url::toRoute(Helper::stepsUrl('registration/qualification-details', \yii\helpers\ArrayHelper::merge(\Yii::$app->request->queryParams, ['id' => $model->id]))), [
                                                    'title' => Yii::t('yii', \Yii::t('admin', 'update')),
                                                    'class' => 'action-bars__link update',
                                                    'data-pjax' => 0
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="far fa-trash-alt"></i>', 'javascript:;', [
                                                    'title' => Yii::t('yii', \Yii::t('admin', 'delete')),
                                                    'data-id' => $model->id,
                                                    'class' => 'action-bars__link delete js-deleteQualification',
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
            <?php Pjax::end() ?>       
        </div>
    </div>
</div>

