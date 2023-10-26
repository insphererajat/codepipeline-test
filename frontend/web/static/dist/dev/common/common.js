(function () {

    $('.testSelAll2').SumoSelect({
        selectAll: true
    });

    $(".chzn-select").chosen({
        disable_search: true
    });
    $(".chzn-select-with-search").chosen();
    $('.chosen-multi').chosen();

    $('[data-toggle="tooltip"]').tooltip();

    $('.js-datetimepicker').datetimepicker({
        'format': 'DD-MM-YYYY'
    });
    
    $('.js-datepicker-max-yesterday').datetimepicker({
        'format': 'DD-MM-YYYY',
        maxDate: $.now()
    });
    
    $('.js-datepicker-start-todate').datetimepicker({
        'format': 'DD-MM-YYYY',
        minDate: $.now()
    });
    
    $('.js-domicile-issue-date-max').datetimepicker({
        'format': 'DD-MM-YYYY',
        minDate: '2000-11-09'
    });

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-green'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {
            color: '#ffffff',
            secondaryColor: '#dedede',
            jackColor: '#2ec4b6'
        });
    });
 
    $('.js-openMenu').on('click', function () { 
        $('.js-MenuOpened').addClass('active');
    });

    $('.js-menuClose').on('click', function () {
        $('.js-MenuOpened').removeClass('active');
    });

    function sideBarFunction() {
        var sideBarMenu = $('.c-sideBar__navigation__item.js-sidebarItem');
        $(sideBarMenu).on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if ($(window).width() > 992) {
                if ($('body').hasClass('sub-sidebar sub-sidebar-md')) {
                    return;
                }
            }
            if ($(this).parent('.js-sidebarList').hasClass('selected')) {
                $(this).next('.sub-dropdown').slideUp();
                $(this).parent('.js-sidebarList').removeClass('selected');
            } else {
                sideBarMenu.next('.sub-dropdown').slideUp();
                sideBarMenu.parent('.js-sidebarList').removeClass('selected');
                $(this).next('.sub-dropdown').slideToggle();
                $(this).parent('.js-sidebarList').addClass('selected');
            };
        });
    };

    sideBarFunction();

})(jQuery);