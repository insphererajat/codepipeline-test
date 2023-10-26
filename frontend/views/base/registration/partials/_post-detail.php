<?php
$queryParams = \Yii::$app->request->queryParams;
if (isset($queryParams['pguid'])):
    $pguid = isset($queryParams['pguid']) ? $queryParams['pguid'] : \common\models\MstPost::MASTER_POST_GUID;
    $postModel = \common\models\MstPost::findByGuid($pguid, ['resultFormat' => common\models\caching\ModelCache::RETURN_TYPE_OBJECT]);
    echo '<div class="register__for">';
    echo "<div class='title-main'>Online Application for <?= \Yii::$app->params['appName'] ?></div>";
    echo "<div class='title-sub'>उच्च न्यायालय हिमाचल प्रदेश के लिए ऑनलाइन आवेदन</div>";
    echo "<div class='adv-no'>Advertisement Number {$postModel->code}</div>";
    echo "</div>";
endif;
?>