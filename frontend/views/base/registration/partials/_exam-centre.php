<?php

use yii\helpers\Html;
use common\models\location\MstDistrict;
use common\models\location\MstState;

$districtDropDownList = MstDistrict::getDistrictDropdown([
            'stateCode' => MstState::STATE_CODE_UK
        ]);
?>
<div class="f-c__review-section">
    <div class="f-c__review-section--title"><span class="text">Exam Centre Preferences</span></div>
    <ul class="f-c__review-section__list">
        <li>
            <span class="f-c__review-section__list--field">Date</span>
            <span class="f-c__review-section__list--detail"><?= date('d-m-Y', strtotime($model->date)); ?></span>
        </li>
        <li>
            <span class="f-c__review-section__list--field">Place</span>
            <span class="f-c__review-section__list--detail"><?= $model->place; ?></span>
        </li>
        <li>
            <span class="f-c__review-section__list--field">Exam Centre Preference 1</span>
            <span class="f-c__review-section__list--detail"><?= !empty($districtDropDownList[$model->preference1]) ? $districtDropDownList[$model->preference1] : ''; ?></span>
        </li>
        <li>
            <span class="f-c__review-section__list--field">Exam Centre Preference 2</span>
            <span class="f-c__review-section__list--detail"><?= !empty($districtDropDownList[$model->preference2]) ? $districtDropDownList[$model->preference2] : ''; ?></span>
        </li>
    </ul>
</div>


