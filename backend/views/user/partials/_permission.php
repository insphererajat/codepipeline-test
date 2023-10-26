<?php
$corePermission = \common\models\Permission::coreModules();
$permissionList = array_chunk($corePermission, 7);
$userPermissions = \common\models\UserPermission::findByUserId($model->id, ['resultCount' => common\models\caching\ModelCache::RETURN_ALL]);
$assignedPermission =yii\helpers\ArrayHelper::getColumn($userPermissions, 'permission_name');
?>

<div class="permission-section__managers-list--category">
    <form method="post" action="<?= yii\helpers\Url::toRoute(['user/permission']) ?>">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <input type="hidden" name="PermissionForm[guid]" value="<?= $model->guid ?>" />
        <div class="row">
            <?php foreach ($permissionList as $corePermission): ?> 
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="table__structure">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Modules</th>
                                    <th>Permission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($corePermission as $permissionKey => $permissionName): ?>
                                    <tr class="permission__block">
                                        <td><span><i class="text"><?= $permissionName ?></i></span></td>
                                        <td><input type="checkbox" class="permission" name="PermissionForm[permission][]" value="<?= $permissionKey ?>" <?= (yii\helpers\ArrayHelper::isIn($permissionKey, $assignedPermission)) ? 'checked="checked"' : '' ?>/></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <div class="grouping equal-button grouping__leftAligned">
                <button class="button blue small" type="submit">Save</button>
             </div>
        </div>
    </form>
</div>