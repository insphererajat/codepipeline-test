
var QualificationController = (function ($) {
    return {
        createUpdate: function () {
            QualificationController.CreateUpdate.init();
        },
    };
}(jQuery));
QualificationController.CreateUpdate = (function ($) {
    var attachEvents = function () {
        
        $(".chzn-select").chosen({width: '100%'});
        $('.qualificationParent').on('change', function (e) {

            if ($(this).val() == '') {

                $('.subjectQualification').show();
            } else {
                $('.subjectQualification').hide();
            }

        });
        $('.subjectQualification').show();

    };

    return {
        init: function () {
            attachEvents();

        }
    };
}(jQuery));