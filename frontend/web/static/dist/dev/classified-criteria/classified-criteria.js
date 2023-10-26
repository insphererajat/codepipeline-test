var classifiedCriteriaController = (function ($) {
    return {
        summary: function () {
            classifiedCriteriaController.Summary.init();
        },
        criteria: function () {
            
            $('.js-post').on('change', function () {
                var post = $(this).data("post");
                $('.section-' + post).addClass('hide');
                if (this.checked) {
                    $('.section-' + post).removeClass('hide');
                }
            });

            var errorMsg = "Sorry, you are not eligible for this post.";

            $('#criteriaForm').on('beforeSubmit', function (e) {

                var flag = true;
                var elem = $(this);

                if (elem.find('.has-error').length) {
                    $.fn.General_ShowNotification({message: "Fill your form.", type: "danger"});
                    return false;
                }

                if (flag == false) {
                    $.fn.General_ShowErrorMessage({message: errorMsg});
                    return false;
                }

                var elem = $('#criteriaForm');
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
                            window.location.href = data.url;
                        } else {
                            $.each(data.errors, function (key, val) {
                                $.fn.General_ShowNotification({message: val, type: "danger"});
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
            }).on('submit', function (e) {
                e.preventDefault();
            });

        },
        rakshak: function () {
            var hill = $("#registrationform-is_hill_certificate").val();
            var height = $("#registrationform-height").val();
            var gender = $("#registrationform-gender").val();
            var social_category = $("#registrationform-social_category_id").val();
            var applied_category = $("#registrationform-is_applied_category").val();

            if (typeof height === "undefined" || height === "" || height === "000.00") {
                if (gender == 'MALE') {
                    if (hill == 1) {
                        $("#registrationform-height").val('160.00');
                    } else if (hill == 2) {
                        if (social_category == 14 && applied_category == 0) {
                            $("#registrationform-height").val('157.50');
                        } else {
                            $("#registrationform-height").val('165.00');
                        }
                    }
                }
                else {
                    if (hill == 1) {
                        $("#registrationform-height").val('147.00');
                    } else if (hill == 2) {
                        if (social_category == 14 && applied_category == 0) {
                            $("#registrationform-height").val('147.00');
                        } else {
                            $("#registrationform-height").val('152.00');
                        }
                    }
                }
            }
            
            $("#registrationform-is_hill_certificate").on('change', function () {
                var hill = $(this).val();
                var height = $("#registrationform-height").val();
                var gender = $("#registrationform-gender").val();
                var social_category = $("#registrationform-social_category_id").val();
                var applied_category = $("#registrationform-is_applied_category").val();
                if (gender == 'MALE') {
                    if (hill == 1) {
                        $("#registrationform-height").val('160.00');
                    } else if (hill == 2) {
                        if (social_category == 14 && applied_category == 0) {
                            $("#registrationform-height").val('157.50');
                        } else {
                            $("#registrationform-height").val('165.00');
                        }
                    }
                } else {
                    if (hill == 1) {
                        $("#registrationform-height").val('147.00');
                    } else if (hill == 2) {
                        if (social_category == 14 && applied_category == 0) {
                            $("#registrationform-height").val('147.00');
                        } else {
                            $("#registrationform-height").val('152.00');
                        }
                    }
                }
            });
        },
    };
}(jQuery));
classifiedCriteriaController.Summary = (function ($) {
    var attachEvents = function () {
        
    };

    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));