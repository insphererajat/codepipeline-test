<?php
use common\models\Service;


$title = (isset($model->id) && $model->id > 0) ? 'Edit Service' : 'Add Service';
$this->title = $title;

$pages = Service::getDropdown([
    'idNotEqualsTo'=>(!$model->getIsNewRecord()) ? $model->id : NULL,
    'parentIdIsNull'=>true
    ]);


$this->registerJs('ServiceController.createUpdate();');



?>
<div class="page__bar">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="section">
                    <h2 class="section__heading"> <?= $title ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <?= $this->render('/layouts/partials/flash-message.php') ?>
                <section class="widget__wrapper">

                    <?php

                    use yii\helpers\Url;
                    use yii\helpers\Html;
                    use yii\bootstrap\ActiveForm;

$hide = (isset($model->id) && ($model->id) > 0 ) ? 'hide' : '';
                    ?>
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'countryForm',
                                'options' => [
                                    'class' => 'widget__wrapper-searchFilter',
                                    'autocomplete' => 'off'
                                ],
                    ]);
                    ?>
                    <div class="row">                        
                        <div class="col-md-6 col-xs-12">
                            <?=
                            $form->field($model, 'parent_id')->dropDownList(                            
                                $pages                        
                            ,[
                                'class'=>'chzn-select',
                                'prompt'=>'Select'
                            ])->label('Parent Service')
                            ?>

                        </div>
                        
                        <div class="col-md-6 col-xs-12">
                            <?=
                            $form->field($model, 'type')->dropDownList(
                            
                            Service::getServiceTypes()
                            
                            ,[
                                'class'=>'chzn-select',
                                'prompt'=>'Select'
                            ])->label('Service Type')
                            ?>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <?=
                            $form->field($model, 'title')->textInput([
                                'autofocus' => true,
                                'class' => 'c9n-ippt',
                                'placeholder' => 'Name'
                            ])->label('Name')
                            ?>

                        </div>
                        
                        <div class="col-md-12 col-xs-12">
                            <?=
                            $form->field($model, 'content')->textarea([
                                'id' => 'editor',
                                'placeholder' => 'Content'
                            ]);
                            ?> 
                        </div>
                    </div>
                    
                    <div class="row">    
                        <div class="col-md-12 col-xs-12">
                            <div class="sectionHead__wrapper">
                                <ul class="upper">
                                    <li class="active"><a href="javascript:;">Eligibility Criteria</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <?php
                            $val = range(10, 60);
                                    echo $form->field($model, 'min_age', [
                                        'template' => "{label}\n{input}\n{hint}\n{error}",
                                    ])
                                    ->dropDownList(
                                            ['' => 'Select'] + array_combine($val, $val), ['class' => 'chzn-select']
                                    )->label('Minimum Age')
                            ?>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <?=
                                    $form->field($model, 'min_education', [
                                        'template' => "{label}\n{input}\n{hint}\n{error}",
                                    ])
                                    ->dropDownList(
                                        ['' => 'Select'] + [
                                            'Any Literate' => 'Any Literate',
                                            'Post Graduate' => 'Post Graduate',
                                            'Graduate' => 'Graduate',
                                            'Diploma' => 'Diploma',
                                            '12th' => '12th',
                                            '10th' => '10th',
                                            '8th' => '8th'
                                        ], ['class' => 'chzn-select']
                                    )->label('Education')
                            ?>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12 col-xs-12">
                            <?=
                                    $form->field($model, 'status', [
                                        'template' => "{label}\n{input}\n{hint}\n{error}",
                                    ])
                                    ->dropDownList(
                                        ['1' => 'Active', '0' => 'Inactive'], ['class' => 'chzn-select']
                                    )->label('Status')
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="grouping equal-button grouping__leftAligned">
                                    <?= Html::submitButton((!isset($model->id) || $model->id < 0) ? 'Create' : 'Update', ['class' => 'button blue small', 'name' => 'button']) ?>
                                    <a href="<?= Url::toRoute(['index']) ?>" class="button grey small">Cancel</a>            
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </section>
            </div>
        </div>
    </div>
</div>