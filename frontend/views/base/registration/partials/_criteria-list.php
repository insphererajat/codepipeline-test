<?php 
use yii\helpers\Url;
use common\models\MstPost;
use common\models\MstListType;
use yii\helpers\ArrayHelper;

$classified = \common\models\MstClassified::findById($model->classifiedId, ['selectCols' => ['id', 'folder_name']]);
$params = \Yii::$app->request->queryParams;
$qr = [];
foreach ($params as $key => $value) {
    if (!empty($value)) {
        $qr[$key] = $value;
    }
}
?>
<div class="f-c__review-section">
    <div class="f-c__review-section--title"><span class="text">Criteria Details for All Applied Posts</span> <a class="icon" href="<?= Url::toRoute(ArrayHelper::merge([0 => '/registration/criteria-details'], $qr)); ?>"><span class="fa fa-edit"></span></a></div>
    <?= $this->render('classified-criteria/' . $classified['folder_name'] . '/_preview.php', ['model' => $model]); ?>
</div>