<?php
$params = [
    'resultCount' => \common\models\caching\ModelCache::RETURN_ALL
];
$docReasonList = \common\models\MessageTemplate::findByType(\common\models\MessageTemplate::TEMPLATE_DOC_REASON, $params);

?>
<div id="notificationModal"  class="modal modal__wrapper fade" tabindex="-1" role="dialog"  aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-bell"></i>Message</h4>
            </div>
            <div class="modal-body">
                <p>Choose a reason for disapprove this document.</p>
                <div class="form-group">
                    <select class="chzn-select document--reason">
                        <option value="">Select Reason</option>
                        <?php foreach ($docReasonList as $doc): ?>
                            <option value="<?= $doc['code'] ?>"><?= $doc['template'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <textarea class="form-conrol reason" placeholder="Write a reason..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button grey small" data-dismiss="modal">Cancel</button>
                <button type="button" class="button blue small confirmMessage">Confirm</button>
            </div>
        </div>
    </div>
</div>