<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$params = [
    'notAdminRole' => common\models\Role::SUPER_ADMIN
];
if( Yii::$app->user->hasUniversityModeratorRole()) {
    $params['universityId'] = Yii::$app->user->getUniversityId();
    $params['notAdminRole'] = [common\models\Role::SUPER_ADMIN, common\models\Role::UNIVERSITY_ADMIN];
}
$users = \common\models\User::getUserDropdown($params);

$selectedPermission = [];
if ($model->id > 0) {
    $groupPermission = common\models\GroupPermission::findByGroupId($model->id, ['resultCount' => common\models\caching\ModelCache::RETURN_ALL]);
    if (!empty($groupPermission)) {
        $selectedPermission = \yii\helpers\ArrayHelper::getColumn($groupPermission, 'permission_name');
    }
}

$this->registerJs('UserController.permission();');
?>
<div class="adm-basicBlock white adm-u-pad20_25">
    <?php
    $form = ActiveForm::begin([
        'id' => 'userGroupForm',
        'options' => [
            'autocomplete' => 'off'
        ],
    ]);
    ?>
    <?= Html::activeHiddenInput($model, 'guid') ?>
    <?= Html::activeHiddenInput($model, 'university_id') ?>
    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col2">
        <?=
            $form->field($model, 'name', [
                'options' => ['class' => 'form-grider cop-form design1'],
                'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                'errorOptions' => ['class' => ' cop-form--help-block'],
                'template' => "<div class='head-wrapper label-required'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'>{input}</div>{hint}\n{error}"
            ])->textInput([
                'autofocus' => true,
                'class' => 'cop-form--container-field fs14__regular adm-u-fieldHeight48',
                'autocomplete' => 'off',
                'placeholder' => 'name'
            ])->label('Name');
        ?>

        <?=
            $form->field($model, 'is_active', [
                'options' => ['class' => 'form-grider cop-form design1'],
                'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                'errorOptions' => ['class' => ' cop-form--help-block'],
                'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
            ])
                ->dropDownList(
                    ['1' => 'Active', '0' => 'Inactive'],
                    ['class' => 'chzn-select']
                )->label('Status')
        ?>
         <?php if ($model->id > 0) : ?>
        <?=
            $form->field($model, 'users', [
                'options' => ['class' => 'form-grider cop-form design1 col-fullwidth'],
                'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium'],
                'errorOptions' => ['class' => ' cop-form--help-block'],
                'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized sumo fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
            ])
                ->dropDownList(
                    $users,
                    ['class' => 'js-mulitUsers', "multiple" => "multiple", "placehoder" => "Select Users"]
                )->label('Users')
        ?>
        <?php endif;?>

    </div>
    <?php if ($model->id > 0) : ?>
        <?php
        $userGroups = common\models\UserConnection::findByGroupId($model->id, [
            'selectCols' => [
                'user_connection.*',
                'user.firstname', 'user.lastname','user.username'
            ],
            'resultCount' => common\models\caching\ModelCache::RETURN_ALL,
            'joinUser' => 'innerJoin'
        ]);
        ?>
        <div class="row">
            <div class="col-sm-12 col-md-6 adm-c-badge userData">
                <?php if (!empty($userGroups)) : ?>
                    <?php foreach ($userGroups as $user) : ?>
                        <a href="javascript:;" class="deleteUserGroup" data-id="<?= $user['user_id'] ?>">
                        <span class="badge  badge-secondary " >
                            <?= $user['firstname'] . ' ' . $user['lastname'] ?> - (<?= $user['username'] ?>)  <span class="red" style="padding:5px;">x</span>
                        </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>              
        <div class="row">
            <?= $this->render('_user-permission.php', ['selectedPermission' => $selectedPermission]) ?>
            <?= $this->render('_university-permission.php', ['selectedPermission' =>  $selectedPermission]) ?>
           <?php if( !Yii::$app->user->hasUniversityModeratorRole()) :?>
            <?= $this->render('_location-permission.php', ['selectedPermission' => $selectedPermission]) ?>
            <?= $this->render('_academic-year-permission.php', ['selectedPermission' =>  $selectedPermission]) ?>
            <?= $this->render('_category-permission.php', ['selectedPermission' =>  $selectedPermission]) ?>
           <?php endif;?>
        </div>
    <?php endif; ?>
    <div class="adm-u-flexed adm-u-justify-start cmt-20">
        <?= Html::submitButton($model->id <= 0 ? 'Create' : 'Update', ['class' => 'btn btn-primary adm-u-pad10_30 theme-button mb-3', 'name' => 'user-button']) ?>
        <a href="<?= Url::toRoute(['/admin/user-group/index']) ?>" class="btn  adm-u-pad10_30 ml-3 mb-3 btn-secondary ">Cancel</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>