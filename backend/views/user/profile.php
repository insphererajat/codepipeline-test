<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerJs('UserController.summary();');
?>
<div class="c-page-container c-page-container-md">
    <div class="clearfix"></div>
    <!--Page content start-->
    <div class="o-pagecontent">
        <div class="o-pagecontent__head">
            <div class="o-pagecontent__head-title"><?= $this->title ?></div>
        </div>
        <div class="clearfix"></div>
        <div class="o-pagecontent__body o-pagecontent__body--whitebg">
            <?= $this->render('/layouts/partials/flash-message.php') ?>

            <?php
            $form = ActiveForm::begin([
                        'id' => 'userForm',
                        'options' => [
                            'autocomplete' => 'off'
                        ],
            ]);
            ?>
            <?= Html::activeHiddenInput($model, 'id') ?>
            <?= Html::activeHiddenInput($model, 'guid') ?>

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <?=
                    $form->field($model, 'firstname')->textInput([
                        'autofocus' => true,
                        'class' => 'form-control',
                        'placeholder' => 'First Name'
                    ])->label('First Name')
                    ?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <?=
                    $form->field($model, 'lastname')->textInput([
                        'autofocus' => true,
                        'class' => 'form-control',
                        'placeholder' => 'Last Name'
                    ])->label('Last Name')
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <?=
                    $form->field($model, 'password')->passwordInput([
                        'autofocus' => true,
                        'class' => 'form-control',
                        'placeholder' => 'Password'
                    ])->label('Password')
                    ?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <?=
                    $form->field($model, 'verifypassword')->passwordInput([
                        'autofocus' => true,
                        'class' => 'form-control',
                        'placeholder' => 'verifypassword'
                    ])->label('verifypassword')
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statescovered">Profile Photo</label>
                        <div class="c-uploadwrap uploadProfilePhoto  <?= $model->profile_media_id > 0 ? 'c-uploadwrap--borderradius c-uploadwrap--withthemeblue js-addimg' : '' ?> ">
                           <?php if (isset($model->profile_media_id) && $model->profile_media_id > 0): ?>
                                <?php
                                $mediaModel = common\models\Media::findById($model->profile_media_id);
                                ?>
                                <img class="profile-image" src="<?= $mediaModel['cdn_path'] ?>"  />
                            <?php endif; ?>
                            <?= Html::activeHiddenInput($model, 'profile_media_id') ?>
                            <div class="c-uploadwrap__textsection <?= $model->profile_media_id > 0 ? 'd-none': ''?> " >
                                <div class="icon"><i class="fa fa-arrow-up" aria-hidden="true"></i></div>
                                <div class="content"><span>Upload</span> your profile image </div>
                            </div>
                            <div class="c-uploadwrap_action <?= $model->profile_media_id <= 0 ? 'd-none' : '' ?> ">
                                <a href="javascript:;" class="c-uploadwrap_action-link withRedBg deleteUserMedia">
                                    <span class="fa fa-trash icon"></span>
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= Html::submitButton('Save', ['class' => 'c-button c-button-info', 'name' => 'user-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!--Page content end-->
    <div class="clearfix"></div>
</div>

