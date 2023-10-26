<!-- /.modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><?= \yii::t('admin', 'upload.file') ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>              
        </div>
        <div class="modal-body">
            <form action="javascript:void(0);" id="upload-dropzone-file_<?= $id; ?>" class="dropzone">
                <input type="hidden" name="_csrf"  id="csrfToken" value="<?= \Yii::$app->request->getCsrfToken() ?>" />
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->