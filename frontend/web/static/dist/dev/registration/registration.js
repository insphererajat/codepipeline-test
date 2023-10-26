
var RegistrationController = (function ($) {
    return {
        createUpdate: function () {
            RegistrationController.CreateUpdate.init();
        },
        checkStudyCenter: function () {
            $('#basicdetailform-study_center_code').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var studyCenterCode = $(elem).val();

                if (studyCenterCode !== "") {
                    $.ajax({
                        url: baseHttpPath + '/api/registration/study-center',
                        method: 'post',
                        async: false,
                        data: {studyCenterCode: studyCenterCode},
                        success: function (data) {
                            if (data.success == "1") {
                                $('#basicdetailform-name').val(data.studyCenter['name']);
                                $('#basicdetailform-centre_address').val(data.studyCenter['address1'] + ',' + data.studyCenter['address2']);
                                $('#basicdetailform-centre_country').val(data.studyCenter['country_code']);
                                $('#basicdetailform-centre_state').val(data.studyCenter['state_code']);
                                $('#basicdetailform-centre_district').val(data.studyCenter['district_code']);
                                $('#basicdetailform-centre_pin_code').val(data.studyCenter['pincode']);

                                $('.chznSearchSingle').chosen().trigger("chosen:updated");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#basicdetailform-study_center_code').val('');
                            $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
                        },
                        beforeSend: function (jqXHR, settings) {
                            $().showScreenLoader();
                        },
                        complete: function (jqXHR, textStatus) {
                            $().hideScreenLoader();
                        }
                    });
                }
            });
        },
        sendOtp: function () {
            $('#generateOTP').on('click', function () {

                $.ajax({
                    url: baseHttpPath + '/registration/validate',
                    method: 'post',
                    async: true,
                    data: $('#basicDetailsForm').serialize(),
                    success: function (data) {
                        var error = false;
                        $.each(data, function (index, obj) {
                            $('.field-' + index).addClass('has-error').removeClass('has-success');
                            $('.field-' + index).find('.help-block').addClass('help-block-error');
                            $('.field-' + index).find('.help-block').html(obj[0]);
                            error = true;
                        });
                        if (!error) {
                            var name = $('#basicdetailform-superintendent_name').val();
                            var mobile = $('#basicdetailform-superintendent_mobile').val();
                            var email = $('#basicdetailform-superintendent_email').val();

                            $.ajax({
                                url: baseHttpPath + '/api/registration/send-otp',
                                method: 'post',
                                async: false,
                                data: {name: name, email: email, mobile: mobile},
                                success: function (data) {
                                    if (data.status == 1) {
                                        $('#globalLoader').addClass('hide');
                                        $('#otpModal').html(data.modal);
                                        $('#otpModal').data('email', data.emailOtpId);
                                        $('#otpModal').data('mobile', data.mobileOtpId);
                                        $("#otpModal").modal();
                                    }
                                }
                            });
                        }
                    }
                });
            });

            RegistrationController.validateOtp();
            RegistrationController.resendOtp();
        },
        validateOtp: function () {
            $('#otpModal').on('click', '#ModalSubmit', function (e) {
                e.preventDefault();
                var emailOtp = $('#emailOtp').val();
                var mobileOtp = $('#mobileOtp').val();
                var emailOtpId = $('#otpModal').data('email');
                var mobileOtpId = $('#otpModal').data('mobile');

                if (!emailOtp && !mobileOtp) {
                    return;
                }

                $.ajax({
                    url: baseHttpPath + '/api/registration/validate-otp',
                    method: 'post',
                    data: {emailOtp: emailOtp, emailOtpId: emailOtpId, mobileOtp: mobileOtp, mobileOtpId: mobileOtpId},
                    success: function (data) {
                        if (data.emailValidated == 1) {
                            $('#emailOtp').closest('div.form-group').addClass('disabled');
                            $('#basicdetailform-superintendent_email').closest('div.form-group').addClass('disabled');
                            $('#basicdetailform-is_email_verified').val(1);
                            $('#resendEmail').addClass('d-none');
                        } else {
                            $('#emailOtp').closest('div.form-group').removeClass('has-success').addClass('has-error');
                            $('#emailMessage').addClass('help-block-error').html('Email OTP validated Failed .');
                        }

                        if (data.mobileValidated == 1) {
                            $('#mobileOtp').closest('div.form-group').addClass('disabled');
                            $('#basicdetailform-superintendent_mobile').closest('div.form-group').addClass('disabled');
                            $('#basicdetailform-is_mobile_verified').val(1);
                            $('#resendMobile').addClass('d-none');
                        } else {
                            $('#mobileOtp').closest('div.form-group').removeClass('has-success').addClass('has-error');
                            $('#mobileMessage').addClass('help-block-error').html('Mobile OTP validated Failed.');
                        }

                        if ($('#emailOtp').closest('div.form-group').hasClass('disabled') && $('#mobileOtp').closest('div.form-group').hasClass('disabled')) {
                            $("#otpModal").modal('hide');
                            $("#generateOTP").closest('.button--block').addClass('d-none');
                            $('#submitButton').html('Next');
                        }
                        $('.form-group.disabled').children('input').attr('readonly', 'true');
                    }
                });
            });
        },
        resendOtp: function () {
            $('#otpModal').on('click', '.resend__otp', function () {
                var data = {};
                if ($('#basicdetailform-email_verified').val() != 1) {
                    var email = $('#basicdetailform-superintendent_email').val();
                    data.email = email;
                }

                if ($('#basicdetailform-mobile_verified').val() != 1) {
                    var mobile = $('#basicdetailform-superintendent_mobile').val();
                    data.mobile = mobile;
                }
                $.ajax({
                    url: baseHttpPath + '/api/registration/re-send-otp',
                    method: 'post',
                    data: data,
                    success: function (data) {
                        if (data.status == 1) {
                            if (data.emailOtpId) {
                                $('#otpModal').data('email', data.emailOtpId);
                                $('#emailOtp').closest('div.form-group').removeClass('has-error').addClass('has-success');
                                $('#emailOtp').val('');
                                $('#emailMessage').removeClass('help-block-error').html('OTP has been resended successfully');
                            }
                            if (data.mobileOtpId) {
                                $('#otpModal').data('mobile', data.mobileOtpId);
                                $('#mobileOtp').closest('div.form-group').removeClass('has-error').addClass('has-success');
                                $('#mobileOtp').val('');
                                $('#mobileMessage').removeClass('help-block-error').html('OTP has been resended successfully');
                            }
                            RegistrationController.otpClock();
                        }
                        else {
                            $().General_ShowErrorMessage({type: 'error', message: data.message});
                        }
                    }
                });

            });
        },
        otpClock: function () {
            if ($('#clockdiv').length > 0) {
                // 10 minutes from now
                var time_in_minutes = $('#collapseOne').data('time');
                var current_time = Date.parse(new Date());
                var deadline = new Date(current_time + time_in_minutes * 60 * 1000);
                function time_remaining(endtime) {
                    var t = Date.parse(endtime) - Date.parse(new Date());
                    var seconds = Math.floor((t / 1000) % 60);
                    var minutes = Math.floor((t / 1000 / 60) % 60);
                    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
                    var days = Math.floor(t / (1000 * 60 * 60 * 24));
                    return {'total': t, 'days': days, 'hours': hours, 'minutes': minutes, 'seconds': seconds};
                }
                function run_clock(id, endtime) {
                    var clock = document.getElementById(id);
                    function update_clock() {
                        var t = time_remaining(endtime);
                        clock.innerHTML = t.minutes + ':' + t.seconds + ' minutes';
                        if (t.total <= 0) {
                            clearInterval(timeinterval);
                        }
                    }
                    update_clock(); // run function once at first to avoid delay
                    var timeinterval = setInterval(update_clock, 1000);
                }
                ('clockdiv', deadline);
            }
        },
        getState: function (prefix) {
            $('.' + prefix + 'country').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var countrycode = elem.val();
                if (countrycode === "") {
                    $('.' + prefix + 'district, .' + prefix + 'state').val('');
                    $('.' + prefix + 'district, .' + prefix + 'state').trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-state',
                    dataType: 'json',
                    data: {countrycode: countrycode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'state').html(data.template);
                            $('.' + prefix + 'state').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $().ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $().showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $().hideScreenLoader();
                    }
                });
            });
        },
        getDistrict: function (prefix) {
            $('.' + prefix + 'state').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var statecode = elem.val();
                if (statecode === "") {
                    $('.' + prefix + 'district').val('');
                    $('.' + prefix + 'district').trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-district',
                    dataType: 'json',
                    data: {statecode: statecode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'district').html(data.template);
                            $('.' + prefix + 'district').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $.fn.showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $.fn.hideScreenLoader();
                    }
                });
            });
        },
        geoLocation: function () {
            $('#captureGeoLocation').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                var geoAddress = "";
                var address = $('#basicdetailform-centre_address').val();
                if (typeof address !== "undefined" && address !== "") {
                    geoAddress += address;
                }
                var country = $('#basicdetailform-centre_country :selected').text();
                if (typeof country !== "undefined" && country !== "") {
                    geoAddress += ' ' + country;
                }
                var state = $('#basicdetailform-centre_state :selected').text();
                if (typeof state !== "undefined" && state !== "") {
                    geoAddress += ' ' + state;
                }
                var district = $('#basicdetailform-centre_district :selected').text();
                if (typeof district !== "undefined" && district !== "") {
                    geoAddress += ' ' + district;
                }
                var postal = $('#basicdetailform-centre_pin_code').val();
                if (typeof postal !== "undefined" && postal !== "") {
                    geoAddress += ' ' + postal;
                }

                if (typeof geoAddress == "undefined" || geoAddress === "") {
                    $().General_ShowErrorMessage({message: 'Oops! look like address can not be empty.'});
                    return;
                }

                $(elem).html('Please Wait...');
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'address': geoAddress
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        $('#basicdetailform-latitude').val(results[0].geometry.location.lat());
                        $('#basicdetailform-longitude').val(results[0].geometry.location.lng());
                        $(elem).html('GEO LOCATE');
                    } else {
                        $().General_ShowErrorMessage({message: 'Latitude & Longitude not found. Please change address and try again.'});
                        $(elem).html('GEO LOCATE');
                    }
                });
            });
        },
        finalSaveAttendance: function () {
            $('.finalSaveAttendance').on('click', function () {
                var message;
                message = "Do you really want to final save student(s) attendance ?";
                bootbox.confirm({
                    title: "Confirm",
                    message: message,
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'button blue small'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'button grey small'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            $.ajax({
                                type: 'POST',
                                url: baseHttpPath + '/exam/attendance/freeze-attendance',
                                dataType: 'json',
                                data: {_csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == "1") {
                                        window.location = window.location;
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
                        }
                    }
                });
            });
        },
    };
}(jQuery));
RegistrationController.CreateUpdate = (function ($) {
    var attachEvents = function () {

        RegistrationController.getState('centre');
        RegistrationController.getDistrict('centre');

        RegistrationController.getState('sup');
        RegistrationController.getDistrict('sup');

        RegistrationController.getState('postoffice');
        RegistrationController.getDistrict('postoffice');

        RegistrationController.getState('');
        RegistrationController.getDistrict('');

        RegistrationController.sendOtp();

        RegistrationController.checkStudyCenter();
        RegistrationController.geoLocation();
        RegistrationController.finalSaveAttendance();

        $('#registrationform-hall').on('change', function (e) {
            e.preventDefault();
            var elem = $(this);
            if (elem.val() == "1") {
                $('.hallSizeBlock').removeClass('d-none');
            } else {
                $('.hallSizeBlock').addClass('d-none');
            }

        });
        $(".exam__date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });



    };

    return {
        init: function () {
            attachEvents();

        }
    };
}(jQuery));