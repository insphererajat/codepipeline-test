<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;
use common\models\Permission;
use common\models\University;

$this->title = 'User Groups';
$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'User Groups',  'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Create', 'icon' => 'fa fa-plus', 'url' => Url::toRoute(['/admin/user-group/create'])];

$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';
?>


<?= \frontend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
<?= \frontend\widgets\alert\AlertWidget::widget() ?>
  <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
    <div class="adm-c-tableGrid__wrapper">
      <div class="adm-c-tableGrid__wrapper__head">
        <div class="adm-c-sectionHeader adm-c-sectionHeader-xs design3">
          <div class="adm-c-sectionHeader__container">
            <div class="adm-c-sectionHeader__label">
              <div class="adm-c-sectionHeader__label__title fs16__medium"><?= $this->title ?></div>
            </div>
          </div>
        </div>
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
            'pager' => [
              'prevPageLabel' => 'Previous',
              'nextPageLabel' => 'Next',
              'linkOptions' => ['class' => 'page-link'],
              'linkContainerOptions' => ['class' => 'page-item']
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'filter' => false,
                    'sortLinkOptions' => ['class' => 'sort'],
                    'value' => function ($model) {
                        return strtoupper($model->name);
                    },
                ],
                [
                  'attribute' => 'university_id',
                  'label' => 'University',
                  'filter' => false,
                  'sortLinkOptions' => ['class' => 'sort'],
                  'value' => function ($model) {
                    if(!empty($model->university_id)) {
                      $university = University::findById($model->university_id);
                      return !empty($university) ? $university['name'] : "";

                    }
                    return "";
                  },
              ],
                [
                    'attribute' => 'is_active',
                    'label' => 'Status',
                    'format' => 'html',
                    'filter' => false,
                    'sortLinkOptions' => ['class' => 'sort'],
                    'value' => function ($data) {
                        return (($data->is_active) == 1) ? "<div class='check-status'><span class='badge badge-success'>Active</span></div>" : "<div class='check-status'><span class='badge badge-danger'>Inactive</span></div>";
                    },
                    'headerOptions' => array(
                        'width' => '10%'
                    ),
                    'filter' => array('1' => 'Active', '0' => 'Inactive'),
                ],
                [
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'visibleButtons' => [
                        //'update' => Yii::$app->user->hasPermission(Permission::EDIT_USER),
                        //'delete' => Yii::$app->user->hasPermission(Permission::DELETE_USER)
                        ],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                              return Html::a('<em class="far fa-edit"></em>', Url::toRoute(['user-group/update', 'guid' => $model->guid]), [
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                                'class' => 'update action-bars__link'
                              ]);
                            },
                            'delete' => function ($url, $model, $key) {
                              return Html::a('<em class="far fa-trash-alt"></em>', 'javascript:void(0);', [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-url' => Url::toRoute(['user-group/delete', 'guid' => $model->guid]),
                                'class' => 'delete action-bars__link deleteConfirmation'
                              ]);
                            },
                          ],
                          'contentOptions' => [
                              'class' => 'action__column'
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





