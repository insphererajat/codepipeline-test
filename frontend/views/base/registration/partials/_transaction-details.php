<?php

use common\models\caching\ModelCache;

$transactionModels = common\models\Transaction::findByApplicantId($model->id, [
            'isConsumed' => common\models\Transaction::IS_CONSUMED_YES,
            'payStatus' => common\models\Transaction::TYPE_STATUS_PAID,
            'resultCount' => ModelCache::RETURN_ALL
        ]);
?>
<div class="f-c__review-section">
    <div class="f-c__review-section--title"><span class="text">Transaction Details</span></div>
<table class="table">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th class="text-center" scope="col">Date</th>
                <th class="text-center" scope="col">Gateway</th>
                <th class="text-center" scope="col">Transaction Id</th>
                <th class="text-center" scope="col">Gateway Transaction Id</th>
                <th class="text-center" scope="col">Amount</th>                
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($transactionModels) && !empty($transactionModels)):
                foreach ($transactionModels as $key => $transactionModel):
                    ?>
                    <tr>
                        <td scope="col"><?= ($key + 1) ?></td>
                        <td class="text-center" scope="col"><?= date('d-m-Y H:i:s', $transactionModel['modified_on']); ?></td>
                        <td class="text-center" scope="col"><?= $transactionModel['type']; ?></td>
                        <td class="text-center" scope="col"><?= $transactionModel['transaction_id']; ?></td>
                        <td class="text-center" scope="col"><?= $transactionModel['gateway_id']; ?></td>                        
                        <td class="text-center" scope="col"><?= $transactionModel['amount']; ?></td>                        
                    </tr>
                    <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
</div>

