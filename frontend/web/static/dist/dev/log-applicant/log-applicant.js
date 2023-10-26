var LogApplicantController = (function ($) {
    return {
        createUpdate: function () {
            LogApplicantController.CreateUpdate.init();
        },
        generateOtp: function () {

            $('#log-applicant-form').on('beforeSubmit', function (e) {

                var elem = $(this);
                var url = elem.attr("action");
                var postData = elem.serializeArray();
                var formId = elem.attr("id");

                if (elem.find('.has-error').length) {
                    return false;
                }

                $.ajax({
                    url: url,
                    method: 'post',
                    async: true,
                    data: postData,
                    success: function (response) {

                        if (response.success == 1) {
                            var $modal = $('#otpModal');
                            $modal.html(response.template);
                            $modal.modal('show');
                            $.fn.formSanitization();

                            $('#change-request-form').on('beforeSubmit', function (e) {                                
                                var elem = $(this);                                
                                var type = $('#verifyotpform-type').val();
                                var applicantId = $('#verifyotpform-applicant_id').val();
                                var email = $('#verifyotpform-email').val();
                                var mobile = $('#verifyotpform-mobile').val();
                                var formId = elem.attr("id");

                                if (elem.find('.has-error').length) {
                                    return false;
                                }
                
                                $.ajax({
                                    url: baseHttpPath + '/api/log-activity/send-otp',
                                    method: 'post',
                                    async: false,
                                    data: {applicantId: applicantId, email: email, mobile: mobile, type: type, _csrf: yii.getCsrfToken()},
                                    success: function (data) {
                                        if (data.status == 1) {
                                            var $modal = $('#otpModal');
                                            $modal.html(data.template);
                                            $modal.modal('show');
                                            RegistrationV2Controller.otpClock();

                                            $('#verifyotpform').on('beforeSubmit', function (e) {
                                                var message = '';
                                                LogApplicantController.sectionAjaxForm($(this), $modal, message);
                                            }).on('submit', function (e) {
                                                e.preventDefault();
                                            });

                                            $.fn.General_ShowNotification({message: 'OTP sent on your email and mobile.'});
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        $().General_ShowErrorMessage({message: jqXHR.responseText});
                                    },
                                    beforeSend: function (jqXHR, settings) {
                                        $().showScreenLoader();
                                    },
                                    complete: function (jqXHR, textStatus) {
                                        $().hideScreenLoader();
                                    }
                                });
                            }).on('submit', function (e) {
                                e.preventDefault();
                            });

                        } else if (response.success == 2) {
                            $().General_ShowErrorMessage({message: response.errors});
                        } else {
                            $.each(response.errors, function (key, val) {
                                $(".field-logactivityform-" + key).addClass('has-error').find('p').text(val);
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $().General_ShowErrorMessage({message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $().showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $().hideScreenLoader();
                    }
                });
            }).on('submit', function (e) {
                e.preventDefault();
            });

        },
        sectionAjaxForm: function (elem, $modal, message) {

            var url = elem.attr("action");
            var postData = elem.serialize();
            var formId = elem.attr("id");

            if (elem.find('.has-error').length) {
                return false;
            }

            $.ajax({
                url: url,
                type: "POST",
                data: postData,
                dataType: 'json',
                success: function (data) {
                    if (data.success == '1') {
                        $modal.modal('hide');
                        $.fn.General_ShowNotification({message: data.message});
                        
                        setTimeout(function () {
                            window.location.replace(baseHttpPath);
                        }, 1000);
                    } else {
                        $.each(data.errors, function (key, val) {
                            $(".field-" + formId + "-" + key).addClass('has-error').find('p').text(val);
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.submitClass').prop('disabled', false).html('Submit');
                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    $('.submitClass').prop('disabled', true).html('Please Wait...');
                    $().showScreenLoader();
                },
                complete: function (jqXHR, textStatus) {
                    $('.submitClass').prop('disabled', false).html('Submit');
                    $().hideScreenLoader();

                }
            });
        },
    };
}(jQuery));

LogApplicantController.CreateUpdate = (function ($) {
    var attachEvents = function () {
        
        LogApplicantController.generateOtp();
        $('.js-birthDate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate: '-58y',
            endDate: '-18y',
            maxDate: $.now()
        });
    };

    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));