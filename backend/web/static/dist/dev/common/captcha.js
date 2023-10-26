var CaptchaController = (function ($) {
    return {
        summary: function () {
            CaptchaController.Summary.init();
        },
        autoRefresh: function () {
            $('#captcha-image').yiiCaptcha('refresh');
            $(".captcha-textbox").val('');
        }
    };
}(jQuery));

CaptchaController.Summary = (function ($) {
    var attachEvents = function () {
        CaptchaController.autoRefresh();
        $('#refresh-captcha').on('click', function (e) {
            e.preventDefault();
            $('#captcha-image').yiiCaptcha('refresh');
            $(".captcha-textbox").val('');
        });
        
        $(".captcha-textbox").on("focus", function () {
            $(this).closest('div.form-group').removeClass('has-error');
            $(this).closest('div.form-grider').removeClass('has-error');
        });
        
        $(".captcha-textbox").on("blur", function () {
            var timer = setInterval(function () {
                if ($(".captcha-textbox").closest('div.form-group').hasClass('has-error')) {
                    CaptchaController.autoRefresh();
                    clearTimeout(timer);
                }
            }, 100);
        });
        
        $('.show-password').click(function () {

            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr('toggle'));
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));