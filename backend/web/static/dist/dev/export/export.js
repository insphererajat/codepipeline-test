var ExportController = (function ($) {
    return {
        attendance: function () {
            ExportController.Attendance.init();
        }
    };
}(jQuery));

ExportController.Attendance = (function ($) {
    var attachEvents = function () {
        
        $('.js-classified').on('change', function (e) {
            e.preventDefault();
            var elem = $(this);
            var classifiedId = elem.val();
            if (classifiedId === "") {
                $(".js-examcentre").val('');
                $(".js-examcentre").trigger("chosen:updated");
                return;
            }

            $.ajax({type: 'post',
                url: baseHttpPath + '/api/exam/get-exam-centre',
                dataType: 'json',
                data: {classifiedId: classifiedId, _csrf: yii.getCsrfToken()},
                success: function (data, textStatus, jqXHR) {
                    if (data.success == "1") {
                        $('.js-examcentre').html(data.template);
                        $(".js-examcentre").trigger("chosen:updated");
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

    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));