<?php

$baseConrtoller = new \common\controllers\BaseApplicantController('baseapplicant', 'backend');
foreach ($dataProvider->getModels() as $key => $record) {
    //echo $record->guid;die;
    $last = ($key == (count($dataProvider->getModels()) - 1)) ? 0 : 1;
    $first = ($key != 0) ? 1 : 0;
    echo $baseConrtoller->actionPreview($record->guid, $first, $last);
}
?>