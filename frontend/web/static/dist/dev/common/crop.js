var cropImageModal = $('#cropImageModal');
var CropController = (function ($) {
    return {
        crop: function () {
            CropController.CropImage.init();
        }
    };
}(jQuery));


CropController.CropImage = (function ($) {
    var attachEvents = function () {
        var cropper;
        $('.croppingImages').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var elem = $(this);

            var mediaId = elem.data('id');
            if (typeof mediaId === "undefined" || mediaId === "") {
                return;
            }

            var studentGuid = elem.data('studentguid');
            if (typeof studentGuid === "undefined" || studentGuid === "") {
                return;
            }

            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
                    '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                    '<div class="loader">' +
                    '<span><img width="24" height="24" src="' + staticPath + '/dist/images/loading.svg"></span>' +
                    '<span class="text">please wait...</span>' +
                    '</div>' +
                    '</div>';


            $('body').modalmanager('loading');

            setTimeout(function () {
                $.get(baseHttpPath + '/api/crop-media-modal', {mediaId: mediaId, studentGuid: studentGuid, _csrf: yii.getCsrfToken()}, function (data) {
                    $('#cropImageModal').html(data).modal('show');
                    var image = document.querySelector('#cropImageBlock');
                    cropper = new Cropper(image, {
                        movable: false,
                        zoomable: false,
                        scalable: false
                    });
                });
            }, 500);
        });

        cropImageModal.on('hidden.bs.modal', function () {
            cropper.destroy();
        });


        cropImageModal.off('click', '.rotateImage');
        cropImageModal.on('click', '.rotateImage', function (e) {
            cropper.rotate(90);
        });

        cropImageModal.on('click', '.rotateReset', function (e) {
            cropper.reset();
        });

        var btnClicked = false;
        cropImageModal.off('click', 'button.btnSubmitCrop').on('click', 'button.btnSubmitCrop', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (btnClicked) {
                return;
            }

            btnClicked = true;
            var btnObj = $(this);

            var mediaId = $('#inputCropMediaId').val();
            if (typeof mediaId === "undefined" || mediaId === "") {
                return;
            }

            var data = cropper.getData();
            data.mediaId = mediaId;
            data._csrf = yii.getCsrfToken();

            $.ajax({
                url: baseHttpPath + '/api/crop-media',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (data) {
                    btnClicked = false;
                    if (data.success == "1") {
                        window.location = window.location;
                    }
                    $('#ModalCropImage').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    $(btnObj).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
                },
                complete: function (jqXHR, textStatus) {
                    $(btnObj).html('<i class="fa fa-save"></i> Save');
                    btnClicked = false;
                }
            });
        });


    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));

