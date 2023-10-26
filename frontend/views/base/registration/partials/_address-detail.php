<?php

use yii\helpers\Url;

$stateDropDownList = common\models\location\MstState::getStateDropdown();
$this->registerJs("RegistrationV2Controller.tehsilCascade();");
?>

<div class="c-sectionHeader c-sectionHeader-xs design3 mt-3 cmb-30">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">Identity/Adress Proof (POI/POA)</div>
        </div>
    </div>
</div>
<div class="row">
    <?= $this->render('address/_present-address.php', ['form' => $form, 'model' => $model, 'formCls' => '', 'stateDropDownList' => $stateDropDownList]) ?>
    <?= $this->render('address/_permanent-address.php', ['form' => $form, 'model' => $model, 'formCls' => '', 'stateDropDownList' => $stateDropDownList]) ?>

</div>
