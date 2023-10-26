<?php
$permissions = \common\models\Permission::getUniversityPermissionArr();
?>
<div class="col-md-4 col-sm-4">
    <div class="adm-basicBlock white adm-u-pad20_25 cmt-20">
        <div class="adm-c-sectionHeader design3">
            <div class="adm-c-sectionHeader__container p-0">
                <div class="adm-c-sectionHeader__label">
                    <div class="adm-c-sectionHeader__label__title fs16__medium"> University</div>
                </div>
            </div>
        </div>
        <div class="adm-c-form adm-c-form-xs">
            <div class="adm-u-flexed adm-u-justify-btw cmb-20">
                <div class="adm-c-buttonset md">
                    <?php foreach ($permissions as $key => $permission) : ?>
                        <div class="c-buttonset cmb-5">
                            <label class="adm-u-flexed adm-u-align-center">
                                <input type="checkbox" name="permissions[]" class="permission-checkbox" value="<?= $key ?>" <?= \yii\helpers\ArrayHelper::isIn($key, $selectedPermission) ? "checked='checked'" : '' ?>>
                                <span></span>
                                <span class="text-md small ml-2"><?= $permission ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>