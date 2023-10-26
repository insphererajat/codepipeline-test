<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-key"></i>Change Password</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'reset-password-form',
                    'action' => \yii\helpers\Url::toRoute(['api/student/change-password', 'guid' => $guid]),
                    'enableAjaxValidation' => true,
                    'validationUrl' => \yii\helpers\Url::toRoute(['api/student/validate-password', 'guid' => $guid]),
                    'options' => [
                        'class' => 'widget__wrapper-searchFilter',
                        'autocomplete' => 'off'
        ]]);
        ?>
        <div class="modal-body">
            <?=
                    $form->field($model, 'password', [
                        'labelOptions' => ['class' => 'is__form__label is__form__label__required']])
                    ->passwordInput([
                        'autocomplete' => 'off',
                        'autofocus' => true,
                        'maxlength' => true,
                        'placeholder' => 'New Password',
                        'class' => 'form-control is__form__field disable-copy-paste'
                    ])
                    ->label('<label class="is__form__label">New Password</label>');
            ?>
            <?=
                $form->field($model, 'confirm_password', [
                    'labelOptions' => ['class' => 'is__form__label is__form__label__required']])
                ->passwordInput(['autocomplete' => 'off', 'autofocus' => true, 'maxlength' => true,'placeholder' => 'Confirm Password', 'class' => 'form-control is__form__field disable-copy-paste'])
                ->label('<label class="is__form__label">Confirm Password</label>');
            ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="c-button c-button-rounded c-button-inverse" data-dismiss="modal">Cancel</button>
            <?= Html::submitButton('Confirm', ['class' => 'c-button c-button-rounded c-button-info', 'name' => 'button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>