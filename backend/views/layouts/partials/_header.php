<?php

use yii\helpers\Url;
?>
<div class="adm-c-header__wrapper adm-c-header__wrapper-xs design2">
    <div class="adm-c-header__wrapper__ls">
        <a href="javascript:;" class="adm-c-header__wrapper__responsiveLogo">
            <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/logos/hpslda.png" class="img-fluid" alt="logo" />
        </a>
        <div class="adm-c-header__wrapper__brandingName"><?= \Yii::$app->params['appName'] ?></div>
    </div>
    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="adm-c-header__wrapper__rs">
            <a href="javascript:;" class="adm-c-header__wrapper__hamburgerIcon">
                <span class="fa fa-bars icon"></span>
            </a>
            <a href="javascript:;" class="adm-c-header__wrapper__home">
                <span class="fa fa-home icon"></span>
            </a>
            <!--<div class="adm-c-header__wrapper__notify dropdown">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="icon fa fa-bell"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="d-notificationWrapper">
                        <div class="d-notificationWrapper__header">
                            <div class="d-notificationWrapper__header__content">
                                <div class="d-notificationWrapper__header__content-icon"><span class="fa fa-bell"></span></div>
                                <div class="d-notificationWrapper__header__content-text">Notification</div>
                            </div>
                            <a href="javascript:;" class="d-notificationWrapper__header__link"><span class="">10</span>New</a>
                        </div>
                        <ul class="d-notificationWrapper__list">
                            <li class="d-notificationWrapper__list__item">
                                <a href="javascript:;" class="d-notificationWrapper__list__link">
                                    <div class="d-notificationWrapper__list__icon">
                                        <span class="fa fa-bell"></span>
                                    </div>
                                    <div class="d-notificationWrapper__list__text">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                                </a>
                            </li>
                            <li class="d-notificationWrapper__list__item">
                                <a href="javascript:;" class="d-notificationWrapper__list__link">
                                    <div class="d-notificationWrapper__list__icon">
                                        <span class="fa fa-bell"></span>
                                    </div>
                                    <div class="d-notificationWrapper__list__text">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                                </a>
                            </li>
                            <li class="d-notificationWrapper__list__item">
                                <a href="javascript:;" class="d-notificationWrapper__list__link">
                                    <div class="d-notificationWrapper__list__icon">
                                        <span class="fa fa-bell"></span>
                                    </div>
                                    <div class="d-notificationWrapper__list__text">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>-->
            <div class="adm-c-header__wrapper__userInfo dropdown">
                <a href="javascript:;" class="dropdown-toggle user-image u-radius50 image-covered" data-toggle="dropdown" style="background-image: url('<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/person.jpg')">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="user-info">
                        <div class="user-info__image u-radius50 image-covered" style="background-image: url('<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/person.jpg')"></div>
                        <div class="user-info__content">
                            <div class="user-info__content__label"><?= Yii::$app->user->identity->firstname . ' ' . Yii::$app->user->identity->lastname; ?></div>
                            <div class="user-info__content__description">User</div>

                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= Url::toRoute(['/profile/index']); ?>">View Profile</a>
                    <a class="dropdown-item logout" href="<?= Url::toRoute(['auth/logout']); ?>">Logout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>