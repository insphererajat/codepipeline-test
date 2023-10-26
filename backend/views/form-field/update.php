<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormField */

$this->title = 'Update Form Field: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Form Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="form-field-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
