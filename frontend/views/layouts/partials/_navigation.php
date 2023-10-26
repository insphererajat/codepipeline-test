<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->queryParams;
$loggedInClass = $redirect = '';
if (!\Yii::$app->applicant->isGuest) {
    $loggedInClass = 'loggedIn';
    $redirect = common\models\Applicant::getRedirectionBasedOnFormStep(Yii::$app->applicant->identity->id);
}
?>

<div class="c-f-navigation">
    <div class="container">
        <div class="c-f-navigation__wrapper">
            <div class="c-f-navigation__wrapper-item toggleMenu">
                <a href="javascript:;" class="js-openMenu">
                    Menu
                    <span class="fa fa-bars"></span>
                </a>
            </div>
            <div class="c-f-navigation__wrapper-item">
                <div class="c-f-navigation__wrapper-mainMenu js-MenuOpened">
                    <span class="fa fa-window-close closeMenu js-menuClose"></span>
                    <a href="/home/index" class="c-f-navigation__wrapper-mainMenu-link">Home</a>
<!--                    <a href="<?= Url::toRoute(['/home/about-us']) ?>" class="c-f-navigation__wrapper-mainMenu-link">About Us</a>
                    <a href="<?= Url::toRoute(['/home/advertisement']) ?>" class="c-f-navigation__wrapper-mainMenu-link">Advertisement</a>
                    <a href="javascript:;" class="c-f-navigation__wrapper-mainMenu-link">Latest News</a>
                    <a href="javascript:;" class="c-f-navigation__wrapper-mainMenu-link">Faq's</a>-->
                    <a href="<?= Url::toRoute(['/home/contact-us']) ?>" class="c-f-navigation__wrapper-mainMenu-link">Contact Us</a>
                    <a href="<?= Url::toRoute(['/auth/get-admit-card']) ?>" class="c-f-navigation__wrapper-mainMenu-link">Admit Card</a>
                </div>
            </div>
            <div class="c-f-navigation__wrapper-item">
                <div class="c-f-navigation__wrapper-loginArea <?= $loggedInClass; ?>">
                    <?php
                    if (\Yii::$app->applicant->isGuest):
                        if (!\components\Helper::checkCscConnect()):
                            ?>
                            <a href="<?= Url::toRoute(['/payment/csc/connect']) ?>" class="login--link login-single">CSC Connect Login</a>
                        <?php endif; ?>
                        <a href="<?= Url::toRoute(['/auth/login']) ?>" class="login--link login-single ml-2">Login</a>
                    <?php else: ?>
                        <div class="dropdown ml-2">
                            <button type="button" class="btn dropdown-toggle login--link" data-toggle="dropdown">
                                <?= strtoupper(\Yii::$app->applicant->identity->name); ?>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= !empty($redirect) ? \yii\helpers\Url::toRoute(ArrayHelper::merge([0 => $redirect], \Yii::$app->request->queryParams)) : 'javascript:;' ?>"><span class="fa fa-home"></span> Dashboard</a>
                                <a class="dropdown-item" href="<?= Url::toRoute('applicant/post') ?>"><span class="fa fa-list"></span> Posts</a>
                                <a class="dropdown-item" href="<?= Url::toRoute('auth/change-password') ?>"><span class="fa fa-key"></span> Change Password</a>
                                <a class="dropdown-item" href="<?= Url::toRoute('auth/logout') ?>"><span class="fa fa-power-off"></span> Logout</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>