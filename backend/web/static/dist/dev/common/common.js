var CommonController = (function ($) {
    return {
        showHideInputs: function (prefix) {
            $('.' + prefix + 'selectAttr').change(function () {
                $(this).find("option:selected").each(function () {
                    var optionValue = $(this).text().toLowerCase();
                    var boxclass = prefix + optionValue;
                    if (optionValue) {
                        $('.' + prefix).not("." + boxclass).addClass('hide');
                        $('.' + prefix).not("." + boxclass).find('input').val('');
                        $('.' + prefix).not("." + boxclass).find('select').val('');
                        $('.' + prefix).not("." + boxclass).find('select').trigger("chosen:updated");
                        $("." + boxclass).removeClass('hide')

                    } else {
                        $("." + boxclass).addClass('hide');
                        $("." + boxclass).find('input').val('');
                        $("." + boxclass).find('select').val('');
                        $("." + boxclass).find('select').trigger("chosen:updated");
                    }
                });
            }).change();
        }
    };
}(jQuery));