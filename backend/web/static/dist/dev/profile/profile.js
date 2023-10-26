var ProfileController = (function ($) {
    return {
        summary: function () {
            ProfileController.Summary.init();
        },
        encrypt: function(){
            
            $('#admin-profile-form').on('beforeSubmit', function (e) {
                e.preventDefault();

                if (typeof $('#userform-password').val() !== "undefined" && $('#userform-password').val() !== "") {
                    var valueee = $('#userform-password').val();
                    var newValue = CryptoJS.AES.encrypt(valueee, encriptionKey).toString();
                    $('#userform-password').val(newValue);
                }
                if (typeof $('#userform-verifypassword').val() !== "undefined" && $('#userform-verifypassword').val() !== "") {
                    var valueee = $('#userform-verifypassword').val();
                    var newValue = CryptoJS.AES.encrypt(valueee, encriptionKey).toString();
                    $('#userform-verifypassword').val(newValue);
                }

            }).on('submit', function (e) {
                
            });
            
        }
    };
}(jQuery));

ProfileController.Summary = (function ($) {
    var attachEvents = function () {
        ProfileController.encrypt();
    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));