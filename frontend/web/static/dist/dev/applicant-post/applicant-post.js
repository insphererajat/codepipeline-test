var ApplicantPostController = (function ($) {
    return {
        summary: function () {
            ApplicantPostController.Summary.init();
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
                                url: baseHttpPath + '/api/applicant-post/send-otp',
                                method: 'post',
                                async: false,
                                data: {type: elem.data('otp-type'), _csrf: yii.getCsrfToken()},
                                success: function (data) {
                                    if (data.status == 1) {
                                        var $modal = $('#otpModal');
                                        $('#globalLoader').addClass('hide');
                                        $modal.html(data.template);
                                        $modal.modal('show');
                                        
                                        var time_in_minutes = $('#collapseOne').data('time');
                                        var time_in_miliseconds = time_in_minutes * 60 * 1000;
                                        setTimeout(function () {
                                            $("#resendMobile").removeClass('d-none');
                                        }, time_in_miliseconds);
                                        ApplicantPostController.otpClock();
                                        $.fn.formSanitization();
                        
                                        $('#verifyotpform').on('beforeSubmit', function (e) {
                                            var message = 'Post cancel successfully.';
                                            ApplicantPostController.sectionAjaxForm($(this), $modal, message, id, guid);
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
                        }
                    }
                });
            });
            
            ApplicantPostController.resendOtp();
        },
        eservicePost: function () {
            $('.js-eservicePost').off('click').on('click', function (e) {
                
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
                    message: "Do you want to update profile?",
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
                                url: baseHttpPath + '/api/applicant-post/validate-eservice',
                                method: 'post',
                                async: false,
                                data: {id: id, guid: guid, _csrf: yii.getCsrfToken()},
                                success: function (data) {
                                    if (data.status == 1) {
                                        bootbox.confirm({
                                            closeButton: false,
                                            title: "Confirmation",
                                            message: "Do you want to discard previous chagnes?",
                                            className: "modal__wrapper",
                                            buttons: {
                                                confirm: {
                                                    label: "Yes",
                                                    className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button'
                                                },
                                                cancel: {
                                                    label: "No",
                                                    className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button'
                                                }
                                            },
                                            callback: function (result) {
                                                if (result === true) {
                                                    
                                                    $.ajax({
                                                        url: baseHttpPath + '/api/applicant-post/discard-eservice',
                                                        method: 'post',
                                                        async: false,
                                                        data: {id: id, guid: guid, _csrf: yii.getCsrfToken()},
                                                        success: function (data) {
                                                            if (data.status == 1) {
                                                                sendOtp();
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
                                                else {
                                                    sendOtp();
                                                }
                                            }
                                        });
                                    }
                                    else {
                                        sendOtp();
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
                            
                            function sendOtp() {

                                $.ajax({
                                    url: baseHttpPath + '/api/applicant-post/send-otp',
                                    method: 'post',
                                    async: false,
                                    data: {type: elem.data('otp-type'), _csrf: yii.getCsrfToken()},
                                    success: function (data) {
                                        if (data.status == 1) {
                                            var $modal = $('#otpModal');
                                            $('#globalLoader').addClass('hide');
                                            $modal.html(data.template);
                                            $modal.modal('show');

                                            var time_in_minutes = $('#collapseOne').data('time');
                                            var time_in_miliseconds = time_in_minutes * 60 * 1000;
                                            setTimeout(function () {
                                                $("#resendMobile").removeClass('d-none');
                                            }, time_in_miliseconds);
                                            ApplicantPostController.otpClock();
                                            $.fn.formSanitization();

                                            $('#verifyotpform').on('beforeSubmit', function (e) {
                                                var message = '';
                                                ApplicantPostController.sectionAjaxForm($(this), $modal, message, id, guid);
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
                            }
                        }
                    }
                });
            });
            
            ApplicantPostController.resendOtp();
        },
        sectionAjaxForm: function (elem, $modal, message, id, guid) {

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
                        $('.resend__otp').addClass('d-none');
                        $modal.modal('hide');
                        
                        $.ajax({
                            type: 'post',
                            url: data.url,
                            dataType: 'json',
                            data: {id: id, guid: guid, _csrf: yii.getCsrfToken()},
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                    $.fn.General_ShowNotification({message: message});
                                    if (typeof data.url !== undefined && data.url !== "") {
                                        window.location.replace(data.url);
                                    } else {
                                        location.reload();
                                    }
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
        resendOtp: function () {
            $('#otpModal').off('click').on('click', '.resend__otp', function () {
                
                var otp_type = $(this).data('otp-type');
                $.ajax({
                    url: baseHttpPath + '/api/applicant-post/re-send-otp',
                    method: 'post',
                    data: {type: otp_type, _csrf: yii.getCsrfToken()},
                    success: function (data) {
                        if (data.status == 1) {
                            if (data.mobileOtpId) {
                                $('#mobileOtpId').val(data.mobileOtpId);
                                $('#mobileOtp').closest('div.form-group').removeClass('has-error').addClass('has-success');
                                $('#mobileOtp').val('');
                                $('#mobileMessage').removeClass('help-block-error').html('OTP has been resended successfully');
                                $("#resendMobile").addClass('d-none');
                                
                                var time_in_minutes = $('#collapseOne').data('time');
                                var time_in_miliseconds = time_in_minutes * 60 * 1000;
                                setTimeout(function () {
                                    $("#resendMobile").removeClass('d-none');
                                }, time_in_miliseconds);
                            }
                            ApplicantPostController.otpClock();
                            $.fn.formSanitization();                            
                            $.fn.General_ShowNotification({message: 'OTP resent successfully.'});
                        }
                        else {
                            $().General_ShowErrorMessage({type: 'error', message: data.message});
                        }
                    }
                });

            });
        },
        otpClock: function(){
            if ($('#clockdiv').length > 0) {
                // 10 minutes from now
                var time_in_minutes = $('#collapseOne').data('time');
                var current_time = Date.parse(new Date());
                var deadline = new Date(current_time + time_in_minutes*60*1000);
                function time_remaining(endtime){
                        var t = Date.parse(endtime) - Date.parse(new Date());
                        var seconds = Math.floor( (t/1000) % 60 );
                        var minutes = Math.floor( (t/1000/60) % 60 );
                        var hours = Math.floor( (t/(1000*60*60)) % 24 );
                        var days = Math.floor( t/(1000*60*60*24) );
                        return {'total':t, 'days':days, 'hours':hours, 'minutes':minutes, 'seconds':seconds};
                }
                function run_clock(id,endtime){
                        var clock = document.getElementById(id);
                        function update_clock(){
                                var t = time_remaining(endtime);
                                clock.innerHTML = t.minutes+':'+t.seconds + ' minutes';
                                if(t.total<=0){ clearInterval(timeinterval); }
                        }
                        update_clock(); // run function once at first to avoid delay
                        var timeinterval = setInterval(update_clock,1000);
                }
                run_clock('clockdiv',deadline);
            }
        },
    };
}(jQuery));
ApplicantPostController.Summary = (function ($) {
    var attachEvents = function () {
        ApplicantPostController.cancelPost();
        ApplicantPostController.eservicePost();
    };

    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));