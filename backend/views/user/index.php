<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Permission;

$this->title = 'Users';

$noDataClass = ($dataProvider->getTotalCount() <= 0) ? ' no-data' : '';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Users',  'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
//if(Yii::$app->user->hasPermission(Permission::CREATE_UPDATE_USER)) {
  $this->params['breadcrumbMenu'][] = ['label' => 'Create', 'icon' => 'fa fa-plus', 'url' => Url::toRoute(['/user/create'])];
//}
?>


<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
<?= \frontend\widgets\alert\AlertWidget::widget() ?>
  <div class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
    <div class="adm-c-tableGrid__wrapper">
      <div class="adm-c-tableGrid__wrapper__head">
        <!-- <div class="adm-c-sectionHeader adm-c-sectionHeader-xs design3">
          <div class="adm-c-sectionHeader__container">
            <div class="adm-c-sectionHeader__label">
              <div class="adm-c-sectionHeader__label__title fs16__medium"><?= $this->title ?></div>
            </div>
            <a href="javascript:;" class="adm-c-sectionHeader__action fs14__regular js-formAccordian"><span class="fa fa-search"></span> Search</a>
          </div>
        </div> -->
        <?= $this->render('partials/_search-form', ['model' => $searchModel]) ?>
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
                    'header' => 'Name',
                    'format' => 'raw',
                    'value' => function ($model) {
                       return "<a href='".Url::toRoute(['/user/view', "guid" => $model->guid])."'>". $model->firstname.' '.$model->lastname."</a>";
                    },
                ],
                'username',
                [
                    'attribute' => 'email',
                    'filter' => false,
                    'sortLinkOptions' => ['class' => 'sort'],
                    'value' => function ($model) {
                        return components\Helper::emailConversion($model->email);
                    }
                ],
                // [
                //     'header' => 'Role',
                //     'value' => function ($model) {
                //       $userRoles = UserRole::findByUserId($model->id, [
                //         'selectCols' => ['role.name'],
                //         'joinRole' => true, 
                //         'notNullRoleId' => true, 
                //         'resultCount' => ModelCache::RETURN_ALL
                //       ]);
  
                //       if(!empty($userRoles)) {
                //         $rolesArr = ArrayHelper::getColumn($userRoles, 'name');
                //         return implode(",", $rolesArr);
                //       }
                //        return "";
                //     },
                // ],
                // [
                //     'header' => 'Domain Name',
                //     'value' => function ($model) {
                //        return !empty($model->network_id) ? Network::findById($model->network_id)['name'] : "";
                //     },
                // ],
              [
                'attribute' => 'is_active',
                'label' => 'Status',
                'format' => 'raw',
                'filter' => false,
                'sortLinkOptions' => ['class' => 'sort'],
                'value' => function ($model) {
                  if ($model->status) {
                    return "<span class='badge badge-success'><span class='title'>Active</span></span>";
                  } else {
                    return "<span class='badge badge-danger'><span class='title'>Inactive</span></span>";
                  }
                },
              ],
              [
                'header' => 'Action',
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="action-bars">{update} {delete}</div>',
                'visibleButtons' => [
                  'update' => Yii::$app->user->hasPermission(Permission::CREATE_UPDATE_USER),
                  'delete' => Yii::$app->user->hasPermission(Permission::DELETE_USER)
                ],
                'buttons' => [
                  'update' => function ($url, $model, $key) {
                    return Html::a('<em class="far fa-edit"></em>', Url::toRoute(['user/update', 'guid' => $model->guid]), [
                      'title' => Yii::t('yii', 'Update'),
                      'data-pjax' => '0',
                      'class' => 'update action-bars__link'
                    ]);
                  },
                  'delete' => function ($url, $model, $key) {
                    return Html::a('<em class="far fa-trash-alt"></em>', 'javascript:void(0);', [
                      'title' => Yii::t('yii', 'Delete'),
                      'data-url' => Url::toRoute(['user/delete', 'guid' => $model->guid]),
                      'class' => 'delete action-bars__link deleteConfirmation'
                    ]);
                  },
                ],
              ],
            ],
          ]);

          $gridView->end();
          ?>
      </div>
    </div>
  </div>
</div>




