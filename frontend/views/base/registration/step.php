<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$qr = \Yii::$app->request->queryParams;
$url = [];
foreach ($qr as $key => $value) {
    if (!empty($value)) {
        $url[$key] = $value;
    }
}
?>

<div class="container">
<div class="row">
<div class="col-lg-12">
<ul class="gsi-step-indicator triangle gsi-style-1 gsi-transition">
                        <li class="<?= isset($step1) && $step1 ? 'active' : ($formstep >= 1 ? 'completed' : ''); ?>" data-target="step-1">
                            <a href="<?= ($formstep >= 1) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/personal-details'], $url)) : 'javascript:;' ?>">
                                <span class="number"></span>
                                <span class="desc">Step 1</span>
                                <span class="label">Personal Information</span>
                            </a>
                        </li>
                        <li class="<?= isset($step2) && $step2 ? 'active' : ($formstep >= 2 ? 'completed' : ''); ?>">
                            <a href="<?= ($formstep >= 1) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/address-details'], $url)) : 'javascript:;' ?>">
                                 <span class="number"></span>
                                <span class="desc">Step 2</span>
                                <span class="label">Identity/Adress Proof (POI/POA)</span>
                            </a>
                        </li>
                        <li  class="<?= isset($step3) && $step3 ? 'active' : ($formstep >= 3 ? 'completed' : ''); ?>">
                            <a href="<?= ($formstep >= 2) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/qualification-details'], $url)) : 'javascript:;' ?>">
                                 <span class="number"></span>
                                <span class="desc">Step 3</span>
                                <span class="label">Qualifications Details</span>
                            </a>
                        </li>
                        <li  class="<?= isset($step4) && $step4 ? 'active' : ($formstep >= 4 ? 'completed' : ''); ?>">
                            <a href="<?= ($formstep >= 5) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/employment-details'], $url)) : 'javascript:;' ?>">
                                 <span class="number"></span>
                                <span class="desc">Step 4</span>
                                <span class="label">Work Experience Details</span>
                            </a>
                        </li>
                        
                        <li  class="<?= isset($step5) && $step5 ? 'active' : ($formstep >= 5 ? 'completed' : ''); ?>">
                            <a href="<?= ($formstep >= 6) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/document-details'], $url)) : 'javascript:;' ?>">
                                  <span class="number"></span>
                                <span class="desc">Step 5</span>
                                <span class="label">Certificate/Documents Upload</span>
                            </a>
                        </li>
                        
                        <li class="<?= isset($step6) && $step6 ? 'active' : ($formstep >= 6 ? 'completed' : ''); ?>">
                            <a href="<?= ($formstep >= 5 && isset($qr['guid'])) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/criteria-details'], $url)) : 'javascript:;' ?>">
                                  <span class="number"></span>
                                <span class="desc">Step 6</span>
                                <span class="label">Criteria Details</span>
                            </a>
                        </li>
                        
                        
                        <li class="<?= isset($step7) && $step7 ? 'active' : ($formstep >= 6 ? 'completed' : ''); ?>">
                            <a href="<?= ($formstep >= 6 && isset($qr['guid'])) ? Url::toRoute(ArrayHelper::merge([0 => '/registration/review'], $url)) : 'javascript:;' ?>">
                                 <span class="number"></span>
                                <span class="desc">Step 7</span>
                                <span class="label">Review</span>
                            </a>
                        </li>
                        
                    </ul>
                    </div>
                    </div>
                    </div>

