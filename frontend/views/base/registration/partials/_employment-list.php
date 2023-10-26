<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
$this->registerJs("RegistrationV2Controller.deleteEmployment();");
?>
<div class="col-12 p-0 <?= isset($class) && !empty($class) ? $class : ''; ?>">
    <div class="c-sectionHeader c-sectionHeader-xs design1 mt-3 mb-3">
        <div class="c-sectionHeader__container">
            <div class="c-sectionHeader__label">
                <div class="c-sectionHeader__label__title fs16__medium">Employment List</div>
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
                        'dataProvider' => $employments,
                        'summary' => "<div class='summary'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items.</div>",
                        'layout' => "<div class='summary'>{summary}</div>\n{items}\n<div class='table-bottom table-bottom--posRight'>{pager}</div>",
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
                                'attribute' => 'employment_type_id',
                                'label' => 'Employment Type',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (!empty($data->employment_type_id)) ? \common\models\MstListType::getName($data->employment_type_id) : '';
                                }
                            ],
                            [
                                'attribute' => 'experience_type_id',
                                'label' => 'Experience Type',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (isset($data->experience_type_id) && $data->experience_type_id > 0) ? $data->experienceType->name : '-';
                                }
                            ],
                            [
                                'attribute' => 'employer',
                                'label' => 'Employer',
                                'sortLinkOptions' => ['class' => 'sort']
                            ],
                            [
                                'attribute' => 'employment_type_id',
                                'label' => 'Nature of Employment',
                                'sortLinkOptions' => ['class' => 'sort'],
                                'value' => function ($data) {
                                    return (!empty($data->employment_nature_id)) ? \common\models\MstListType::getName($data->employment_nature_id) : '';
                                }
                            ],
                            [
                                'attribute' => 'office_name',
                                'label' => 'Institution / Department / Organisation',
                                'sortLinkOptions' => ['class' => 'sort']
                            ],
                            [
                                'attribute' => 'designation',
                            ],                            
                            [
                                'attribute' => 'start_date',
                                'value' => function ($data) {
                                    return (!empty($data->start_date)) ? date('d-m-Y', strtotime($data->start_date)) : '';
                                }
                            ],
                            [
                                'attribute' => 'end_date',
                                'value' => function ($data) {
                                    return (!empty($data->end_date)) ? date('d-m-Y', strtotime($data->end_date)) : 'till today';
                                }
                            ],
                            [
                                'attribute' => 'end_date',
                                'label' => 'Duration',
                                'value' => function ($data) {
                                    $end = empty($data->end_date) ? date('Y-m-d') : $data->end_date;
                                    $endDate = new \DateTime($end . "T00:00:00");
                                    $startDate = new \DateTime($data->start_date);
                                    $diff = $endDate->diff($startDate);
                                    return $diff->y . " Years, " . $diff->m . " Month, " . $diff->d . " Day";
                                }
                            ],
                            [
                                'header' => \Yii::t('admin', 'action'),
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) use($guid) {
                                        return Html::a('<i class="fa fa-pencil-alt"></i>', Url::toRoute(['registration/employment-details', 'guid' => $guid, 'id' => $model->id]), [
                                                    'title' => Yii::t('yii', \Yii::t('admin', 'update')),
                                                    'class' => 'action-bars__link update',
                                                    'data-pjax' => 0
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="far fa-trash-alt"></i>', 'javascript:;', [
                                                    'title' => Yii::t('yii', \Yii::t('admin', 'delete')),
                                                    'data-id' => $model->id,
                                                    'class' => 'action-bars__link delete js-deleteEmployment',
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

