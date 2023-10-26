(function ($) {

 
    //Show Error Message
    $.fn.General_ShowErrorMessage = function (options) {
        var defaults = {
            message: '',
            type: 'error',
            timeout: 2000,
            title: 'Error',
            eventCallback: function () {
            }
        };

        var opts = $.extend({}, defaults, options);

        bootbox.alert({
            title: opts.title,
            message: opts.message,
            className: "modal__wrapper",
            buttons: {
                ok: {
                    label: 'Ok',
                    className: 'c-button c-button-rounded c-button-inverse'
                }
            },
            callback: function () {
                if (opts.eventCallback && typeof opts.eventCallback === 'function') {
                    opts.eventCallback();
                }
            }
        });
    };

    //Show Error Message
    $.fn.ShowFlashMessages = function (options) {
        var defaults = {
            message: '',
            type: 'error',
        };

        var opts = $.extend({}, defaults, options);
        var obj = $('.adm-c-mainContainer');
        if (opts.type === 'error') {

            obj.prepend('<div class="adm-c-alert__customized">' +
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' + opts.message +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    ' <span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '</div>' +
                    '</div>');
        } else {

            obj.prepend('<div class="adm-c-alert__customized">' +
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' + opts.message +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    ' <span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '</div>' +
                    '</div>');
        }
        $(".alert").fadeTo(2000, 500).slideUp(500, function () {
            $(".alert").slideUp(500);
            $(".alert").hide();
        });
    };

    $.fn.hideScreenLoader = function (options) {
        var defaults = {};
        var opts = $.extend({}, defaults, options);
        $('#globalLoader').hide();
    };

    $.fn.showScreenLoader = function (options) {
        var defaults = {};
        var opts = $.extend({}, defaults, options);
        $('#globalLoader').show();
    };

    $('.deleteConfirmation').on('click', function (e) {
        e.preventDefault();
        var elem = $(this);
        var url = elem.data('url');
        if (typeof url === "undefined" || url === "") {
            return;
        }

        bootbox.confirm({
            title: "Confirm",
            message: 'Do you really want to delete this record?',
            className: "modal__wrapper",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'c-button c-button-rounded c-button-success'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'c-button c-button-rounded c-button-inverse'
                }
            },
            callback: function (result) {
                if (result == true) {
                    window.location = url;
                }

            }
        });
    });

    $('body').on('click', '.dismissNotification', function (e) {
        e.preventDefault();
        $(this).closest('.c-alerts').remove();
    });
  
    $('.updateStatusGrid').on('click', function (e) {
       
        e.preventDefault();
        var elem = $(this);
        var url = elem.data('url');
        if (typeof url === 'undefined' || url === '') {
            return;
        }

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                if (data.success == "1") {
                    elem.data('status', data.status);
                    var html = (data.status == "1") ? "<a href='javascript:;' title='Active'><span class='badge badge-success'>Active</span><i class='fa fa-spin fa-spinner hide'></i></a>" : "<a href='javascript:;' title='Inactive'><span class='badge badge-danger'>Inactive</span><i class='fa fa-spin fa-spinner hide'></i></a>";
                    elem.html(html);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                elem.find('.badge').removeClass('hide');
                elem.find('.fa').addClass('hide');
                $().General_ShowErrorMessage({message: jqXHR.responseText});
            },
            beforeSend: function (jqXHR, settings) {
                elem.find('.badge').addClass('hide');
                elem.find('.fa').removeClass('hide');
            },
            complete: function (jqXHR, textStatus) {
                elem.find('.badge').removeClass('hide');
                elem.find('.fa').addClass('hide');
            }
        });
    });
   

}(jQuery));