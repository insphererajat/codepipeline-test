<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FormFieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Form Fields';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-field-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Form Field', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'guid',
            'type',
            'title',
            'data:ntext',
            //'slug',
            //'meta_title',
            //'meta_keyword',
            //'meta_description:ntext',
            //'parent_id',
            //'form_id',
            //'is_active',
            //'is_deleted',
            //'created_by',
            //'created_on',
            //'modified_by',
            //'modified_on',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
