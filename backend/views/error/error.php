<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */


$this->title = $name;
$exceptionMessage = $exception->getMessage();
$messageToDisplay = empty($exceptionMessage) ? $message : $exceptionMessage;


$this->params['breadcrumb'][] = ['label' => $name, 'class' => 'active'];
?>

<?= backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <div class="adm-c-emptyScreen adm-c-emptyScreen__viewport adm-c-emptyScreen-xs design2">
        <div class="adm-c-emptyScreen__content">
            <div class="adm-c-emptyScreen__content__label">Oh snap! You got an error!</div>
            <div class="adm-c-emptyScreen__content__description"><?= nl2br($messageToDisplay) ?></div>
            <div class="buttons-multiple buttons-multiple-sm cmt-20">
                <a href="/auth/logout" class="btn btn-rounded mb-3 btn-danger mb-3 text-light adm-u-pad9_14">Back to Home</a>
            </div>
        </div>
        <div class="adm-c-emptyScreen__image">
            <img src="<?= Yii::$app->params['staticHttpPath'] ?>/dist/images/icons/error.svg" alt="empty image" />
        </div>
    </div>
</div>
