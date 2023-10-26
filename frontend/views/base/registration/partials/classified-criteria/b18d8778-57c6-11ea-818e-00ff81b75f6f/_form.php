<?php

use yii\helpers\Html;
use common\models\caching\ModelCache;
use common\models\MstPost;
use common\models\MstQualification;
use common\models\MstUniversity;
use common\models\ApplicantDetail;
use yii\helpers\ArrayHelper;
use components\Helper;
use common\models\MstSubject;

$params = \Yii::$app->request->queryParams;
$classifiedModel = common\models\MstClassified::findByGuid($params['guid']);
$postList = \common\models\MstPost::getPostDropdown(['classifiedId' => $classifiedModel['id']]);
$ageParams = [
    'classifiedId' => $model->classifiedId,
    'applicantPostId' => $model->applicantPostId
];
echo Html::activeHiddenInput($model, 'applicant_post_criteria_id', ['value' => true]);
$flag = 0;
?>
<div class="c-sectionHeader c-sectionHeader-xs design3">
    <div class="c-sectionHeader__container">
        <div class="c-sectionHeader__label">
            <div class="c-sectionHeader__label__title fs16__medium">You can apply for the below posts.</div>
        </div>
    </div>
</div>
<div class="col-12 p-0">
    <!--Start Section-->
    <?php
    $strPost = "";
    foreach ($postList as $postId => $post):
        if (Helper::calculateAge(ArrayHelper::merge($ageParams, ['postId' => $postId]), $model->_applicantDetailModel)):
            $mstPostQualification = \common\models\MstPostQualification::findByPostId($postId, ['returnAll' => ModelCache::RETURN_ALL]);
            $options = $optionsSeq = [];
            foreach ($mstPostQualification as $key => $postQualification) {
                $optionsSeq[$postQualification['option_seq']][] = $postQualification;
            }
            ?>
            <div class="c-form c-form-xs u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mt-3">
                <div class="form-grider design1">
                    <div class="c-buttonset md radio-design1 c-permission__item">
                        <label class="u-flexed u-align-center">
                            <input type="checkbox" class="js-post" data-post="<?= $postId ?>" name="RegistrationForm[posts][<?= $postId ?>][post_id]" value="<?= $postId ?>" <?= isset($model->posts[$postId]['post_id']) ? 'checked' : '' ?>>
                            <span></span>
                            <span class="text-md ml-2">
                                <?= $post; ?>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="c-form c-form-xs col2 u-fieldRadius8 u-fieldShadow u-fieldBorderClr2 mt-3 section-<?= $postId ?> <?= isset($model->posts[$postId]['post_id']) ? '' : 'hide' ?>">
                <?php
                $c = 1;
                foreach ($optionsSeq as $key => $seq):
                    $options = [];
                    $class = "label-required";
                    foreach ($seq as $key => $pq) {
                        $qualification = !empty($pq['qualification_id']) ? MstQualification::getName($pq['qualification_id']) : '';
                        $university = !empty($pq['university_id']) ? ', (' . MstUniversity::getName($pq['university_id']) . ')' : '';
                        $field1 = '';
                        if (!empty($pq['field1']) && !empty($pq['qualification_id'])) {
                            $field1 = ', (' . ucfirst($pq['field1']) . ')';
                        } else {
                            $field1 = ucfirst($pq['field1']);
                        }
                        $options[$pq['qualification_id'] . '~' . $pq['university_id'] . '~' . trim($pq['field1'])] = $qualification . $university . $field1;
                        if (ArrayHelper::isIn($postId, [2]) && $c == 6) {
                            $class = "";
                        } else if (ArrayHelper::isIn($postId, [3, 7]) && $c == 2) {
                            $class = "";
                        }
                    }
                    ?>

                    <div class="form-grider design1">
                        <div class="head-wrapper">
                            <div class="head-wrapper__title <?= $class ?>">
                                <div class="head-wrapper__title-label fs14__medium"><?= Yii::t('app', 'Condition ' . $c); ?></div>
                            </div>
                        </div>
                        <?=
                                $form->field($model, 'posts[' . $postId . '][field' . $c . ']', [
                                    'template' => "<div class='cop-form--container'><div class='dropdowns-customized chosen fs14__regular u-fieldHeight48'>\n{input}\n{hint}\n{error}</div></div>",
                                    'labelOptions' => ['class' => 'is__form__label is__form__label__with-info']])
                                ->dropDownList($options, ['class' => 'chzn-select', 'prompt' => 'Select an Option'])
                                ->label(FALSE);
                        ?>
                    </div>

                    <?php
                    $c++;
                endforeach;
                ?>
            </div>
            <?php $flag = 1;
        endif; ?>
        <!--End Section-->
        <?php
    endforeach;
    if (!$flag):
        ?>
        <div class="c-sectionHeader c-sectionHeader-xs design3">
            <div class="c-sectionHeader__container">
                <div class="c-sectionHeader__label">
                    <div class="c-sectionHeader__label__title fs16__medium">Sorry! you are not eligible for this advertisement due to age, etc. Please contact with support team.</div>
                </div>
            </div>
        </div>
<?php endif; ?>
</div>