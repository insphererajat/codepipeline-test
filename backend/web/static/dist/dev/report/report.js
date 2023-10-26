var ReportController = (function ($) {
    return {
        createUpdate: function () {
            ReportController.CreateUpdate.init();
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
        }
    };
}(jQuery));

ReportController.CreateUpdate = (function ($) {
    var attachEvents = function () {
        ReportController.fromDatePicker();
        ReportController.toDatePicker();
    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));