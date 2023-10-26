<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use components\Helper;

$this->title = 'Apply For Exam Centre';
$this->params['bodyClass'] = 'frame__body';
$classified = \common\models\MstClassified::findById($model->classifiedId, ['selectCols' => ['id', 'folder_name']]);
$this->registerJs("classifiedCriteriaController.criteria();");
?>
<?= $this->render('/layouts/partials/flash-message.php') ?>
<div class="main-body">
    <div class="register__wrapper">
        <?= $this->render('partials/_post-detail.php') ?>
        <div class="c-tabbing c-tabbing-xs design4">
            <?= $this->render('step', ['step6' => TRUE, 'formstep' => $step]); ?>
            <div class="c-tabing__container register__wrapper-container">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'criteriaForm',
                            'options' => [
                                'class' => 'widget__wrapper-searchFilter',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?php
                if (!$model->is_eservice):
                    if (!empty($model->classifiedId)) {
                        if(isset ($classified['folder_name']) && !empty ($classified['folder_name'])){
                            echo $this->render('partials/classified-criteria/' . $classified['folder_name'] . '/_form.php', ['form' => $form, 'model' => $model, 'formCls' => '']);
                        }
                    }
                    ?>
                    <div class="u-flexed u-justify-btw mt-4"> 
                        <a href="<?= Url::toRoute(Helper::stepsUrl('registration/document-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                        <?= yii\helpers\Html::submitButton('Save & Next', ['class' => 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button']) ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        You can not update post details.
                    </div>
                    <div class="u-flexed u-justify-btw mt-4"> 
                        <a href="<?= Url::toRoute(Helper::stepsUrl('registration/document-details', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey">Previous</a>
                        <a href="<?= Url::toRoute(Helper::stepsUrl('registration/review', \Yii::$app->request->queryParams)); ?>" class="button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button">Next</a>
                    </div>
                <?php endif; ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>