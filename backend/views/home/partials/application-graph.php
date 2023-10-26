<?php

use common\models\ApplicantAddress;
use common\models\caching\ModelCache;
use common\models\location\MstState;
use common\models\MstPost;
use common\models\ApplicantPost;
use common\models\MstListType;

$color = ['#f0b220', '#2087f0', '#999999', '#2087f0', '#5647f1', '#1256a0'];

//State wise registration
$records = MstState::findByKeys([
    'selectCols' => [new \yii\db\Expression("mst_state.name as state, count(DISTINCT applicant_address.applicant_post_id) as `count`")],
    'isActive' => ModelCache::IS_ACTIVE_YES,
    'isDeleted' => ModelCache::IS_DELETED_NO,
    'joinWithApplicantAddress' => 'leftJoin',
    'joinWithApplicantPost' => 'innerJoin',
    'postId' => MstPost::MASTER_POST,
    'addressType' => ApplicantAddress::PERMANENT_ADDRESS,
    'groupBy' => ['mst_state.code'],
    'returnAll' => true,
    'orderBy' => ['mst_state.name' => SORT_ASC],
    'forceCache' => true,
    'cacheTime' => 14400
]);

$graphData = [];
foreach ($records as $key => $model):
    $graphData['state'][] = $model['state'];
    $graphData['count'][] = (int)$model['count'];
endforeach;

if (isset($graphData['state']) && isset($graphData['count'])) {
    $this->registerJs("DashboardController.stateWiseRegistraion(" . json_encode($graphData['state']) . "," . json_encode($graphData['count']) . ")");
}

//State wise profile complete
$records = MstState::findByKeys([
    'selectCols' => [new \yii\db\Expression("mst_state.name as state, count(applicant_post.id) as `count`")],
    'isActive' => ModelCache::IS_ACTIVE_YES,
    'isDeleted' => ModelCache::IS_DELETED_NO,
    'joinWithApplicantAddress' => 'leftJoin',
    'joinWithApplicantPost' => 'innerJoin',
    'notPostId' => MstPost::MASTER_POST,
    'inApplicationStatus' => [ApplicantPost::APPLICATION_STATUS_SUBMITTED, ApplicantPost::APPLICATION_STATUS_CANCELED, ApplicantPost::APPLICATION_STATUS_REAPPLIED],
    'addressType' => ApplicantAddress::PERMANENT_ADDRESS,
    'groupBy' => ['mst_state.code'],
    'returnAll' => true,
    'orderBy' => ['mst_state.name' => SORT_ASC],
    'forceCache' => true,
    'cacheTime' => 14400
]);

$graphData = [];
foreach ($records as $key => $model):
    $graphData['state'][] = $model['state'];
    $graphData['count'][] = (int)$model['count'];
endforeach;
//echo '<pre>';print_r($graphData);die;
if (isset($graphData['state']) && isset($graphData['count'])) {
    $this->registerJs("DashboardController.stateWiseProfileComplete(" . json_encode($graphData['state']) . "," . json_encode($graphData['count']) . ")");
}

//Gender wise graph
$records = ApplicantPost::findByKeys([
    'selectCols' => [new \yii\db\Expression("applicant_detail.gender as gender, count(applicant_post.id) as `count`")],
    'joinWithApplicantDetail' => 'innerJoin',
    'postId' => MstPost::MASTER_POST,
    'groupBy' => ['applicant_detail.gender'],
    'returnAll' => true,
    'forceCache' => true,
    'cacheTime' => 14400
]);
$sum=0;
foreach ($records as $key => $model):
    $sum += (int)$model['count'];
endforeach;
$graphData = [];
foreach ($records as $key => $model):
    $graphData[$key]['name'] = $model['gender'];
    $graphData[$key]['y'] = (float) round(($model['count']/$sum)*100);
    if ($color[$key]) {
        $graphData[$key]['color'] = $color[$key];
    }
    if ($key == 0) {
        $graphData[$key]['sliced'] = true;
        $graphData[$key]['selected'] = true;
    }
endforeach;
if (!empty($graphData)) {
    $this->registerJs("DashboardController.genderWiseRegistraion(" . json_encode($graphData) . ")");
}

//Social Category wisegraph
$records = ApplicantPost::findByKeys([
    'selectCols' => [new \yii\db\Expression("applicant_detail.social_category_id as social_category_id, count(applicant_post.id) as `count`")],
    'joinWithApplicantDetail' => 'innerJoin',
    'postId' => MstPost::MASTER_POST,
    'groupBy' => ['applicant_detail.social_category_id'],
    'returnAll' => true,
    'forceCache' => true,
    'cacheTime' => 14400
]);
$sum=0;
foreach ($records as $key => $model):
    $sum += (int)$model['count'];
endforeach;
$graphData = [];
foreach ($records as $key => $model):
    $graphData[$key]['name'] = MstListType::getName($model['social_category_id'], ['forceCache' => true]);
    $graphData[$key]['y'] = (float) round(($model['count']/$sum)*100);
    if ($color[$key]) {
        $graphData[$key]['color'] = $color[$key];
    }
    if ($key == 0) {
        $graphData[$key]['sliced'] = true;
        $graphData[$key]['selected'] = true;
    }
endforeach;
if (!empty($graphData)) {
    $this->registerJs("DashboardController.categoryWiseRegistraion(" . json_encode($graphData) . ")");
}
?>
<div class="adm-c-graphView">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="adm-basicBlock white adm-u-pad20_25 cmb-30">
                <div id="an-g1"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 cmb-30">
            <div class="adm-basicBlock white adm-u-pad20_25">
                <div id="GenderWise"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 cmb-30">
            <div class="adm-basicBlock white adm-u-pad20_25">
                <div id="SocialWise"></div>
            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="adm-basicBlock white adm-u-pad20_25 cmb-30">
                <div id="an-g2"></div>
            </div>
        </div>
    </div>
</div>