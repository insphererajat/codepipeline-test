<?php

use common\models\ApplicantAddress;
use common\models\caching\ModelCache;
use common\models\location\MstState;
use common\models\MstPost;
use common\models\ApplicantPost;
use common\models\MstListType;

$color = ['#f0b220', '#2087f0', '#999999', '#2087f0', '#5647f1', '#1256a0'];

if (isset($data['advt']['active']) && isset($data['advt']['completed'])) {
    $sum = (int)$data['advt']['active']+(int)$data['advt']['completed'];
    $graphData = [];
    $graphData[0]['name'] = 'Active Advertisement';
    $graphData[0]['y'] = ($data['advt']['active']) ? (float) round(($data['advt']['active']/$sum)*100) : 0;
    $graphData[0]['color'] = $color[0];
    $graphData[0]['sliced'] = true;
    $graphData[0]['selected'] = true;

    $graphData[1]['name'] = 'Total Advertisement';
    $graphData[1]['y'] = ($data['advt']['completed']) ? (float) round(($data['advt']['completed']/$sum)*100) : 0;
    $graphData[1]['color'] = $color[1];
    
    if (!empty($graphData)) {
        $this->registerJs("DashboardController.adTotalActive(" . json_encode($graphData) . ")");
    }
}

//Advt & application status wise counter
$records = ApplicantPost::findByKeys([
    'selectCols' => [new \yii\db\Expression("CONCAT(mst_classified.title,'-',mst_classified.code) as advt, count(applicant_post.id) as total, SUM(CASE WHEN application_status = " . ApplicantPost::APPLICATION_STATUS_PENDING . " THEN 1 ELSE 0 END) as pending, SUM(CASE WHEN application_status = " . ApplicantPost::APPLICATION_STATUS_SUBMITTED . " THEN 1 ELSE 0 END) as submit, SUM(CASE WHEN application_status = " . ApplicantPost::APPLICATION_STATUS_REAPPLIED . " THEN 1 ELSE 0 END) as reapply, SUM(CASE WHEN application_status = " . ApplicantPost::APPLICATION_STATUS_CANCELED . " THEN 1 ELSE 0 END) as canceled")],
    'joinWithApplicantDetail' => 'innerJoin',
    'joinWithMstClassified' => 'innerJoin',
    'notPostId' => MstPost::MASTER_POST,
    'groupBy' => ['applicant_post.classified_id'],
    'orderBy' => ['applicant_post.classified_id' => SORT_ASC],
    'returnAll' => true,
    'forceCache' => true,
    'cacheTime' => 14400
]);

$graphData = [];
foreach ($records as $pkey => $model):
    $graphData['advt'][] = $model['advt'];
    foreach ($model as $key => $count) {
        if($key != 'advt') {
            $graphData['data'][$key][] = (int)$count;
        }
    }    
endforeach;
if (isset($graphData['data'])) {
    $i = 0;
    foreach ($graphData['data'] as $key => $model):
    $graphData['series'][$i]['name'] = ucfirst($key);
    if (isset($color[$i])) {
        $graphData['series'][$i]['color'] = $color[$i];
    }
    $graphData['series'][$i]['data'] = $model;
    $i++;
    endforeach;
    unset($graphData['data']);
    if (isset($graphData['advt']) && isset($graphData['series'])) {
        $this->registerJs("DashboardController.advertismentWiseCount(" . json_encode($graphData['advt']) . "," . json_encode($graphData['series']) . ")");
    }
}
?>
<div class="adm-c-graphView">
    <div class="row">
        <div class="col-12 col-md-6 cmb-30">
            <div class="adm-basicBlock white adm-u-pad20_25">
                <div id="adTotalActive"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 cmb-30">
            <div class="adm-basicBlock white adm-u-pad20_25 cmb-30">
                <div id="AdvertismentWiseCount"></div>
            </div>
        </div>
    </div>
</div>