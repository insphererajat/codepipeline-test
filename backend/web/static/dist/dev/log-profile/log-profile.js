var LogProfileController = (function ($) {
    return {
        createUpdate: function () {
            LogProfileController.CreateUpdate.init();
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
        changeStatus: function () {
            $('.js-changeStatus').off('click').on('click', function (e) {

                e.preventDefault();
                var elem = $(this);
                var guid = elem.data('guid');
                var id = elem.data('id');
                if (typeof guid === undefined || guid === "" || typeof id === undefined || id === "") {
                    $().General_ShowErrorMessage({message: 'Error: Invalid click.'});
                    return false;
                }
                
                var message = "<select name='status' id='js-status'><option value='1'>Approved</option><option value='2'>Reject</option></select><br/><textarea onkeypress='return onlyAlphabetsNumeric(event,this);' onpaste='return false' oncut='return false' oncopy='return false' id='js-remarks' placeholder='Write reason here' maxlength='200'></textarea><br/><span style='font-size:12px;font-style:italic;'>Max 200 character</span><div class='error'></div>";

                bootbox.confirm({
                    closeButton: false,
                    title: "Update Status",
                    message: message,
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button'
                        },
                        cancel: {
                            label: "Cancel",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey'
                        }
                    },
                    callback: function (result) {
                        if (result === true) {
                            
                            var status = $('#js-status').val();
                            var remarks = $('#js-remarks').val();

                            $.ajax({
                                type: 'post',
                                url: baseHttpPath + '/api/applicant-post/log-profile-update-status',
                                dataType: 'json',
                                data: {id: id, guid: guid, status: status, remarks: remarks, _csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == "1") {
                                        $.fn.General_ShowNotification({message: 'Status update successfully.'});
                                        var text = (status == 1) ? 'Approved' : 'Rejected';
                                        elem.parents('td.action-bars').siblings('td.js-applicationStatus').html(text);
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

LogProfileController.CreateUpdate = (function ($) {
    var attachEvents = function () {
        LogProfileController.fromDatePicker();
        LogProfileController.toDatePicker();
        LogProfileController.changeStatus();

    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));