<?php $classified = \common\models\MstClassified::findById($applicantPostModel['classified_id'], ['selectCols' => ['id', 'folder_name']]);
if (isset($classified['folder_name']) && !empty($classified['folder_name'])): ?>
    <section class="c-reviewdatamain__sectionwrap">
        <div class="sectionhead">Criteria Details for All Applied Posts</div>
    <?= $this->render('classified/' . $classified['folder_name'] . '/_preview.php', ['applicantDetailModel' => $applicantDetailModel, 'applicantCriteriaModel' => $applicantCriteriaModel, 'applicantPostModel' => $applicantPostModel]); ?>
    </section>
<?php endif; ?>