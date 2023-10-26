var AuthController = (function ($) {
    return {
        authenticate: function () {
            AuthController.Authenticate.init();
        },
        encrypt: function () {

            $('.js-saveAndContinueAA').on('click', function (e) {
                e.preventDefault();
                if ($('#personalDetailsForm').find('.has-error').length) {
                    return false;
                }

                if (typeof $('#registrationform-identity_certificate_no').val() !== "undefined" || $('#registrationform-identity_certificate_no').val() !== "") {
                    var valueee = $('#registrationform-identity_certificate_no').val();
                    var newValue = CryptoJS.AES.encrypt(valueee, encriptionKey).toString();
                    $('#registrationform-identity_certificate_no').val(newValue);
                }

                $('#personalDetailsForm').submit();
            });

            $('#admin-login-form').on('beforeSubmit', function (e) {

                var email = $('#loginform-email').val();
                var password = $('#loginform-password').val();

                /*var encryptedUsername = CryptoJS.AES.encrypt(email, encriptionKey).toString();
                 var encryptedPassword = CryptoJS.AES.encrypt(password, encriptionKey).toString();
                 $('#loginform-email').val(encryptedUsername);
                 $('#loginform-password').val(encryptedPassword);*/

                var elem = $(this);
                var url = elem.attr("action");
                var postData = elem.serializeArray();
                var formId = elem.attr("id");

                $(elem).addClass('loginProcess');
                if (elem.find('.has-error').length) {
                    return false;
                }

                postData[1].value = CryptoJS.AES.encrypt(postData[1].value, encriptionKey).toString();
                postData[2].value = CryptoJS.AES.encrypt(postData[2].value, encriptionKey).toString();

                var timeKey = Math.floor(Date.now() / 1000);

                postData[2].value = postData[2].value + '||' + timeKey;
                $.ajax({
                    url: url,
                    type: "POST",
                    data: postData,
                    dataType: 'json',
                    success: function (data) {
                        if(!$(elem).hasClass('loginProcess')) {                            
                            window.location = '/admin/auth/logout';
                        }
                        else {
                            if (data.success == '1') {
                                window.location.replace(data.redirectUrl);
                            } else {
                                $.each(data.errors, function (key, val) {
                                    $(".field-loginform-" + key).addClass('has-error').find('p').text(val);
                                });
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
                        $('button[name="login-button"]').html('Login');
                        $(elem).removeClass('loginProcess');
                    },
                    beforeSend: function (jqXHR, settings) {
                        $('button[name="login-button"]').html('Please Wait...');
                        $().showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $(elem).removeClass('loginProcess');
                        $('button[name="login-button"]').html('Login');
                        $().hideScreenLoader();
                    }
                });
            }).on('submit', function (e) {
                e.preventDefault();
            });

        },
        changePasswordEncrypt: function () {

            $('#change-password-form').on('beforeSubmit', function (e) {

                var elem = $(this);
                var url = elem.attr("action");
                var postData = elem.serializeArray();
                var formId = elem.attr("id");

                if (elem.find('.has-error').length) {
                    return false;
                }


                postData[1].value = CryptoJS.AES.encrypt(postData[1].value, encriptionKey).toString();
                postData[2].value = CryptoJS.AES.encrypt(postData[2].value, encriptionKey).toString();
                postData[3].value = CryptoJS.AES.encrypt(postData[3].value, encriptionKey).toString();
                $.ajax({
                    url: url,
                    type: "POST",
                    data: postData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success == '1') {
                            location.reload();
                        } else {
                            $.each(data.errors, function (key, val) {
                                $(".field-changepasswordform-" + key).addClass('has-error').find('p').text(val);
                            });
                            CaptchaController.autoRefresh();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
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
        resetPasswordEncrypt: function () {

            $('#reset-password-form').on('beforeSubmit', function (e) {

                var elem = $(this);
                var url = elem.attr("action");
                var postData = elem.serializeArray();
                var formId = elem.attr("id");

                if (elem.find('.has-error').length) {
                    return false;
                }


                postData[1].value = CryptoJS.AES.encrypt(postData[1].value, encriptionKey).toString();
                postData[2].value = CryptoJS.AES.encrypt(postData[2].value, encriptionKey).toString();
                postData[3].value = CryptoJS.AES.encrypt(postData[3].value, encriptionKey).toString();
                $.ajax({
                    url: url,
                    type: "POST",
                    data: postData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success == '1') {
                            window.location.replace(data.redirectUrl);
                        } else {
                            $.each(data.errors, function (key, val) {
                                $(".field-resetpasswordform-" + key).addClass('has-error').find('p').text(val);
                            });
                            CaptchaController.autoRefresh();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
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

        }
    };
}(jQuery));

AuthController.Authenticate = (function ($) {
    var attachEvents = function () {
        AuthController.encrypt();
        AuthController.changePasswordEncrypt();
        AuthController.resetPasswordEncrypt();
    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));


