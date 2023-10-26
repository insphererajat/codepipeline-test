<?php

use common\models\Role;
use common\models\Network;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$searchParams = Yii::$app->request->queryParams;
$showSearchCls = (isset($searchParams['UserSearch']) && !empty($searchParams['UserSearch'])) ? "" : "hide";

?>

<div class="filters-wrapper adm-u-pad7_10 <?= $showSearchCls?>">
    <?php
    $form = ActiveForm::begin([
        'id' => 'userSearch',
        'method' => 'GET',
        'action' => Url::toRoute(['/user/index']),
        'options' => [
            'class' => 'widget__wrapper-searchFilter',
            'autocomplete' => 'off'
        ],
    ]);
    ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
    <div class="form-grider design1">
            <div class="cop-form--container">
                <?=
                $form->field($model, 'search')->textInput([
                    'autocomplete' => 'off',
                    'autofocus' => true,
                    'maxlength' => true,
                    'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 alpha-numeric-with-special disable-copy-paste',
                    'placeholder' => 'Search'
                ])->label(FALSE)
                ?>
            </div>
        </div>
        <div class="form-grider design1">
            <div class="cop-form--container">
                <?=
                $form->field($model, 'email')->textInput([
                    'autocomplete' => 'off',
                    'autofocus' => true,
                    'maxlength' => true,
                    'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48 disable-copy-paste',
                    'placeholder' => 'Email'
                ])->label(FALSE)
                ?>
            </div>
        </div>
        <?php if(false && Yii::$app->user->hasAdminRole()) :?>
            <div class="form-grider design1">
            <div class="form-group dropdowns-customized chosen fs14__regular adm-u-fieldHeight48">
                <?=
                    $form->field($model, 'role_id')->dropDownList(
                        Role::getAllRoles(),
                        ['class' => 'chzn-select', 'prompt' => "Select Role"]
                    )->label(false)
                ?>
            </div>
        </div>
        <?php endif;?>
       
        <div class="filters-wrapper__action">
            <div class="adm-c-button cml-10">
                <?= Html::submitButton('Search', ['class' => 'btn theme-button adm-u-pad15_22 text-light mb-1', 'name' => 'button']) ?>
                <a href="<?= Url::toRoute(['/admin/user/index']) ?>"  class = 'btn btn-dark  adm-u-pad15_22 mb-1'>Reset</a>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>