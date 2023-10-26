<div class="adm-c-sideBar adm-c-sideBar-xs">
    <div class="adm-c-sideBar__overlay"></div>
    <div class="adm-c-sideBar__container">
        <div class="close-icon">
            <a class="icon--responsive" href="javascript:;">
                <span class="fa fa-times"></span>
            </a>
            <a class="icon--expanded" href="javascript:;">
                <span class="fa fa-bars"></span>
            </a>
            <a class="icon--colapsed" href="javascript:;">
                <span class="icon fas fa-times"></span>
            </a>
        </div>
        <div class="adm-c-sideBar__header">
            <a href="javascript:;" class="adm-c-sideBar__header__logo">
                <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda.png" class="img-fluid logo-large" alt="logo" />
                <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda.png" class="img-fluid logo-small" alt="logo" />
            </a>
        </div>
        <!-------------sidebar widget------------------>
        <?= \backend\widgets\sidebar\AdminSidebarWidget::widget() ?>
    </div>
</div>