var LogProfileController = (function ($) {
    return {
        createUpdate: function () {
            LogProfileController.CreateUpdate.init();
        },
    };
}(jQuery));

LogProfileController.CreateUpdate = (function ($) {
    var attachEvents = function () {
        
        $('.js-birthDate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate: new Date(dobStartDate),
            endDate: new Date(dobEndDate),
            maxDate: $.now()
        });
        
        uploadfiles();
        deleteMedia();
    };
    
    var uploadfiles = function () {

        $('a.uploadPhoto').uploadFile({
            cloudUpload: true,
            addRemoveLinks: false,
            maxImage: 1,
            acceptedFiles: ".jpg,.jpeg,.png",
            maxFilesize: 0.1,
            containerId: 'uploadPhoto',
            onSuccess: function (file, response) {
                if (response.success == "1") {
                    
//                    setTimeout(function () {
//                        $.get(baseHttpPath + '/api/upload-file/crop-media-modal?mediaId='+media.orig, function (data) {
//                            $('#cropImageModal').html(data).modal('show');
//                        });
//                    }, 500);
                    var media = response.media;

                    var list = ['jpg', 'jpeg', 'png'];
                    var image = (list.indexOf(response.extension) != -1) ? 1 : 0;
                    var pdf = (response.extension === "pdf") ? 1 : 0;
                    var doc = (response.extension === "doc") ? 1 : 0;
                    var docx = (response.extension === "docx") ? 1 : 0;
                    var xls = (response.extension === "xls") ? 1 : 0;
                    var source = $("#single-upload-media-template").html();
                    var template = Handlebars.compile(source);
                    var html = template({media: response.cdnPath, id: media.orig, guid: media.guid, file: response.fileName, image: image, pdf: pdf, doc: doc, docx: docx, xls: xls});

                    $('.uploadPhotoContainer').addClass('hide');
                    $('.uploadedPhotoContainer').removeClass('hide');
                    $('.uploadedPhotoContainer').html(html);
                    $('.inputPhoto').val(media.orig);
                    $('#uploadImageModal').modal('hide');
                    
                    deleteMedia();

                } else {
                    $().General_ShowErrorMessage({message: 'While save file in remote cdn error.'});
                }
            },
        });
    };
    
    var deleteMedia = function () {

        $('.trash').off('click').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var guid = elem.data('guid');
            var id = elem.data('id');
            var logProfileGuid = elem.data('log-profile-guid');
            if (typeof id === undefined || id === "" || typeof guid === undefined || guid === "" || typeof logProfileGuid === undefined || logProfileGuid === "") {
                $().General_ShowErrorMessage({message: 'Error: Invalid click.'});
                return false;
            }

            bootbox.confirm({
                closeButton: false,
                title: "Confirmation",
                message: "Do you want to delete?",
                className: "modal__wrapper",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button'
                    },
                    cancel: {
                        label: "Cancel",
                        className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button'
                    }
                },
                callback: function (result) {
                    if (result === true) {
                        $.ajax({
                            type: 'post',
                            url: baseHttpPath + '/api/registration/remove-otr-document',
                            dataType: 'json',
                            data: {id: id, guid: guid, logProfileGuid: logProfileGuid, _csrf: yii.getCsrfToken()},
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                    location.reload();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $().hideScreenLoader();
                                $().General_ShowErrorMessage({message: jqXHR.responseText});
                            },
                            beforeSend: function (jqXHR, settings) {
                                $().showScreenLoader();
                            },
                            complete: function (jqXHR, textStatus) {
                                $().hideScreenLoader();
                            }
                        });
                    }
                }
            });
        });
    }

    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));