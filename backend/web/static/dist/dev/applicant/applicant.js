var ApplicantController = (function ($) {
    return {
        createUpdate: function () {
            ApplicantController.CreateUpdate.init();
        },
        encrypt: function(){
            
            $('#ResetPasswordForm').on('beforeSubmit', function (e) {
                e.preventDefault();

                if (typeof $('#applicantresetpassword-new_password').val() !== "undefined" && $('#applicantresetpassword-new_password').val() !== "") {
                    var valueee = $('#applicantresetpassword-new_password').val();
                    var newValue = CryptoJS.AES.encrypt(valueee, encriptionKey).toString();
                    $('#applicantresetpassword-new_password').val(newValue);
                }
                if (typeof $('#applicantresetpassword-confirm_new_password').val() !== "undefined" && $('#applicantresetpassword-confirm_new_password').val() !== "") {
                    var valueee = $('#applicantresetpassword-confirm_new_password').val();
                    var newValue = CryptoJS.AES.encrypt(valueee, encriptionKey).toString();
                    $('#applicantresetpassword-confirm_new_password').val(newValue);
                }

            }).on('submit', function (e) {
                
            });
            
        },
        fromDatePicker: function () {
            $(".from__date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function (selected) {
                var startDate = new Date(selected.date.valueOf());
                $('.to__date').datepicker('setStartDate', startDate);
            }).on('clearDate', function (selected) {
                $('.to__date').datepicker('setStartDate', null);
            });
        },
        toDatePicker: function () {
            $(".to__date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function (selected) {
                var endDate = new Date(selected.date.valueOf());
                $('.from__date').datepicker('setEndDate', endDate);
            }).on('clearDate', function (selected) {
                $('.from__date').datepicker('setEndDate', null);
            });
        },
        resetPassword: function() {
            $('.globalModalButton').on('click', function(e){
                e.preventDefault();
                $('#globalModal').modal('show').find('.modal-body').load($(this).attr('href'));
            });
        },
        cancelPost: function () {
            $('.js-cancelPost').off('click').on('click', function (e) {

                e.preventDefault();
                var elem = $(this);
                var guid = elem.data('guid');
                var id = elem.data('id');
                if (typeof guid === undefined || guid === "" || typeof id === undefined || id === "") {
                    $().General_ShowErrorMessage({message: 'Error: Invalid click.'});
                    return false;
                }

                bootbox.confirm({
                    closeButton: false,
                    title: "Confirmation",
                    message: "Do you want to cancel this post?",
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
                                url: baseHttpPath + '/api/applicant-post/cancel-post',
                                dataType: 'json',
                                data: {id: id, guid: guid, _csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == "1") {
                                        $.fn.General_ShowNotification({message: 'Post cancel successfully.'});
                                        elem.parents('td.action-bars').siblings('td.js-applicationStatus').html('Canceled');
                                        elem.remove();
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
        },
    };
}(jQuery));

ApplicantController.CreateUpdate = (function ($) {
    var attachEvents = function () {
        ApplicantController.fromDatePicker();
        ApplicantController.toDatePicker();
        ApplicantController.resetPassword();
        ApplicantController.cancelPost();

    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));