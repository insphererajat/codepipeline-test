<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

$this->title = 'Application Received Report';

$this->params['breadcrumb'][] = ['label' => 'Dashboard', 'url' => Url::toRoute(['home/index'])];
$this->params['breadcrumb'][] = ['label' => 'Reports / Application Received Report', 'class' => 'active'];
$this->params['breadcrumbMenu'][] = ['label' => 'Search', 'icon' => 'fa fa-search', 'url' => "javascript:;", 'class' => 'js-searchBreadcrumb'];
?>
<?= \backend\widgets\breadcrumb\BreadcrumbWidget::widget() ?>
<div class="adm-c-mainContainer">
    <?= \backend\widgets\alert\AlertWidget::widget() ?>
    <section class="adm-c-tableGrid  adm-c-tableGrid-xs design2">
        <div class="adm-c-tableGrid__wrapper">
            <div class="adm-c-tableGrid__wrapper__head">
                <div class="filters-wrapper adm-u-pad7_10">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'ApplicantSearchForm',
                                'method' => 'GET',
                                'options' => [
                                    'class' => 'widget__wrapper-searchFilter',
                                    'autocomplete' => 'off'
                                ],
                    ]);
                    ?>
                    <div class="adm-c-form adm-c-form-xs adm-u-fieldRadius8 adm-u-fieldShadow adm-u-fieldBorderClr2 col4 col4--withButton">
                        <?=
                                $form->field($model, 'classified_id', [
                                    'options' => ['class' => 'form-grider cop-form design1'],
                                    'labelOptions' => ['class' => 'head-wrapper__title-label fs14__medium', 'tag' => 'div'],
                                    'errorOptions' => ['class' => ' cop-form--help-block'],
                                    'template' => "<div class='head-wrapper'><div class='head-wrapper__title'>{label}</div></div><div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular adm-u-fieldHeight48'>{input}</div></div>{hint}\n{error}"
                                ])
                                ->dropDownList(common\models\MstClassified::getClassifiedDropdown(['notInIds' => [common\models\MstClassified::MASTER_CLASSIFIED]]), ['class' => 'chzn-select', 'prompt' => 'Select Advertisement']
                                )->label(FALSE)
                        ?>
                    </div>
                    <div class="filters-wrapper__action">
                        <div class="adm-c-button cml-10 cmt-10">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3']); ?>
                            <input type="submit" class="btn btn-rounded adm-u-pad8_20 theme-button  btn-primary mb-3" value="Export" formaction="/report/export/application-received-export">
                            <a href="<?= Url::toRoute(['/report/export/application-received']) ?>"  class = 'btn btn-rounded adm-u-pad8_20 btn-dark mb-3'>Reset</a>
                        </div> 
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <!--<div class="adm-c-tableGrid__container">
                <div id="w0" class="grid-view"><div class="adm-c-tableGrid__box table-responsive withScroll">
                        <table class="table"><thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Advnumber</th>
                                    <th>Advt. Name</th>
                                    <th>Total Applications Received</th>
                                    <th>Paid Applications</th>
                                    <th>Unpaid Applications</th>
                                    <th>Cancelled Applications</th>
                                    <th>Reapply Applications</th>
                                    <th>Online Paid Consumed Payment</th>
                                    <th>Online Paid Unconsumed Payment</th>
                                    <th>Online Cancelled Consumed Payment</th>
                                    <th>Online Cancelled Unconsumed Payment</th>
                                    <th>Online Reapply Consumed Payment</th>
                                    <th>Online Reapply Unconsumed Payment</th>
                                    <th>Exempted</th>
                                    <th>Cancelled Exempted</th>
                                    <th>Exam Fees</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($records)):
                                    foreach ($records as $key => $record):
                                        echo "<tr data-key='1'>";
                                        echo "<td>" . ($key + 1) . "</td>";
                                        echo "<td>" . $record['advt_no'] . "</td>";
                                        echo "<td>" . $record['advt_name'] . "</td>";
                                        echo "<td>" . $record['total_applicants'] . "</td>";
                                        echo "<td>" . $record['paid'] . "</td>";
                                        echo "<td>" . $record['unpaid'] . "</td>";
                                        echo "<td>" . $record['cancelled'] . "</td>";
                                        echo "<td>" . $record['re_applied'] . "</td>";
                                        echo "<td>" . $record['paid_consumed_payment'] . "</td>";
                                        echo "<td>" . $record['paid_unconsumed_payment'] . "</td>";
                                        echo "<td>" . $record['cancelled_consumed_payment'] . "</td>";
                                        echo "<td>" . $record['cancelled_unconsumed_payment'] . "</td>";
                                        echo "<td>" . $record['reapplied_consumed_payment'] . "</td>";
                                        echo "<td>" . $record['reapplied_unconsumed_payment'] . "</td>";
                                        echo "<td>0</td>";
                                        echo "<td>0</td>";
                                        echo "<td>0</td>";
                                        echo "</tr>";
                                    endforeach;
                                else:
                                    echo "<tr><td colspan='17'>No records found!</td></tr>";
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>-->
        </div>
    </section>
</div>