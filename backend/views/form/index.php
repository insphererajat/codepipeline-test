<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Form', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'guid',
            'title',
            'description:ntext',
            'slug',
            //'meta_title',
            //'meta_keyword',
            //'meta_description:ntext',
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
