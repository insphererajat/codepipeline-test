
<div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Resize Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="cropImageBlock" src="<?= $media['cdn_path'] ?>" class="img-thumbnail" style="max-width: 100%;">
                    <input type="hidden" id="inputCropMediaId" name="cropMediaId" value="<?= $media['id'] ?>"/>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="button red rotateReset"  title="Reset">
                    <i class="fa fa-refresh"></i> Reset
                </button>
                <button type="button" class="button grey rotateImage" title="Rotate">
                    <i class="fa fa-repeat"></i> Rotate
                </button>
                <button type="button" class="button blue btnSubmitCrop" title="Crop Image">
                    <i class="fa fa-save"></i> Save
                </button>
                <button type="button" class="button grey" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

