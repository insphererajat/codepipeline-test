<?php

use yii\helpers\Html;
use common\models\Transaction;
use common\models\ApplicantFee;

$module = ApplicantFee::MODULE_APPLICATION;
if ($model->is_eservice) {
    $module = ApplicantFee::MODULE_ESERVICE;
}
?>
<div class="f-c__review-section">
    <div class="f-c__review-section--title"><span class="text">Payment Gateways</span> </div>
    <div class="col-12">
        <div class="row">
            <div class="alert alert-warning fade show">
                Note: These are payment gateways, you can select from multiple banks and multiple mode of payments(Debit
                Card, Credit Card, Net Banking etc) after selecting one from these payment gateways.
            </div>
        </div>
        <div class="row">
            <?php if (!\components\Helper::checkCscConnect()): ?>
            <div class="col-md-3">
                <div class="c-buttonset xsm radio-design2 cmb-5">
                    <label class="adm-u-flexed adm-u-align-center">
                        <input name="paymentMethod" id="exampleRadios1" value="<?= Transaction::TYPE_HDFC ?>"
                            type="radio" class="" checked>
                        <span></span>
                        <span class="text-md small ml-2">HDFC</span>
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="c-buttonset xsm radio-design2 cmb-5">
                    <label class="adm-u-flexed adm-u-align-center">
                        <input name="paymentMethod" id="exampleRadios1" value="<?= Transaction::TYPE_RAZORPAY ?>"
                            type="radio" class="">
                        <span></span>
                        <span class="text-md small ml-2">Razor Pay</span>
                    </label>
                </div>
            </div>
            <?php endif; ?>
            <?php if (\components\Helper::checkCscConnect()): ?>
            <div class="col-md-3">
                <div class="c-buttonset xsm radio-design2 cmb-5">
                    <label class="adm-u-flexed adm-u-align-center">
                        <input name="paymentMethod" id="exampleRadios2" value="<?= Transaction::TYPE_CSC ?>"
                            type="radio" checked>
                        <span></span>
                        <span class="text-md small ml-2">CSC Wallet</span>
                    </label>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <input type="hidden" id="guid" name="feeId" value="<?= $isPaid['feeId']; ?>">
        <input type="hidden" id="module" name="appModule" value="<?= ApplicantFee::MODULE_APPLICATION; ?>">
    </div>
</div>