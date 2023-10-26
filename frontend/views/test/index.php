<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Transaction;
use common\models\ApplicantFee;

?>
<?php
$form = ActiveForm::begin([
            'action' => ['payment/bob/application'],
            'id' => 'ReviewDetailForm',
            'options' => [
                'class' => 'widget__wrapper-searchFilter',
                'autocomplete' => 'off'
            ],
        ]);
?>
<div class="card-header is__accordion__header" id="headingFour">
    <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
            <span class="is__accordion__header--icon"><i class="fa fa-money"></i></span>
            <span class="is__accordion__header--title">Payment Mode</span>
        </button>
    </h5>
</div>
<div id="collapseFour" class="collapse show" aria-labelledby="headingFour" data-parent="#accordion">
    <div class="card-body">
        <div class="is__form white--bg">
            <ul class="is__review-section__payment-mode">
                <li class="is__form is__form__check-inc">
                    <div class="radio pull-left">
                        <label>
                            <input class="payu" name="paymentMethod" id="exampleRadios4" value="<?= Transaction::TYPE_CSC ?>" type="radio">
                            <span for="exampleRadios4">CSC WALLET</span>
                        </label>
                    </div>
                    <input class="payu" name="appModule" value="APPLICATION" type="hidden">
                </li>
                <li class="is__form is__form__check-inc">
                    <div class="radio pull-left">
                        <label>
                            <input class="payu" name="paymentMethod" id="exampleRadios4" value="<?= Transaction::TYPE_HDFC ?>" type="radio">
                            <span for="exampleRadios4">HDFC</span>
                        </label>
                    </div>
                </li>
                <li class="is__form is__form__check-inc">
                    <div class="radio pull-left">
                        <label>
                            <input class="payu" name="paymentMethod" id="exampleRadios4" value="<?= Transaction::TYPE_BOB ?>" type="radio">
                            <span for="exampleRadios4">BOB</span>
                        </label>
                    </div>
                </li>
                <input type="hidden" id="guid" name="feeId" value="<?= Yii::$app->security->hashData(1, Yii::$app->params['hashKey']); ?>">

            </ul>
            <?= Html::submitButton('Pay Now', ['class' => 'button button--right-res-full button--small button--radius blue', 'id' => 'payNow']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>