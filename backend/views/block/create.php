<?php
$title = (isset($model->guid) && !empty($model->guid)) ? 'Edit Block' : 'Create Block';
$this->title = $title;
$this->registerJs('LocationController.getState();');
$this->registerJs('LocationController.getDistrict();');
?>
<div class="clearfix"></div>
<div class="c-page-container c-page-container-md">
    <div class="clearfix"></div>
    <!--Page content start-->
    <div class="o-pagecontent">
        <div class="o-pagecontent__head">
            <div class="o-pagecontent__head-title"><?= $this->title?></div>
        </div>
        <div class="clearfix"></div>
        <div class="o-pagecontent__body o-pagecontent__body--whitebg">
            <?= $this->render('partials/_form.php', ['model' => $model]) ?>
        </div>
    </div>
    <!--Page content end-->
    <div class="clearfix"></div>
</div>
