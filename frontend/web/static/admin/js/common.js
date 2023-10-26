(function () {

    $('.testSelAll2').SumoSelect({
        selectAll: true
    });

    $(".chzn-select").chosen();

    $('[data-toggle="tooltip"]').tooltip();

    $('.js-datetimepicker').datetimepicker();

    $('.js-navTabs').scrollingTabs({
        bootstrapVersion: 4,
        scrollToTabEdge: true,
        disableScrollArrowsOnFullyScrolled: true
    });

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-green'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {
            color: '#ffffff',
            secondaryColor: '#dedede',
            jackColor: '#2ec4b6'
        });
    });

    $('.js-drawerOpen').on('click', function () {
        $('.js-drawerMain').addClass('active');
    });

    $('.adm-c-sideBar .close-icon .icon--expanded').on('click', function () {
        $(this).closest('.adm-c-sideBar__container').addClass('icons-only');
        $('body').addClass('sub-sidebar sub-sidebar-md');
        $('.c-pageContainer__wrapper').addClass('sidebar-collapsed');
    });
    
    $('.adm-c-sideBar .close-icon .icon--colapsed').on('click', function () {
        $(this).closest('.adm-c-sideBar__container').removeClass('icons-only');
        $('body').removeClass('sub-sidebar sub-sidebar-md');
        $('.c-pageContainer__wrapper').removeClass('sidebar-collapsed');
    });

    function sideBarFunction() {
        var sideBarMenu = $('.js-sidebarItem');

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
            }
            ;
        });
    }
    ;

    sideBarFunction();

    $('.js-formAccordian').on('click', function() {
        $(this).closest('.adm-c-tableGrid__wrapper__head').find('.filters-wrapper').toggleClass('hide');
    })

})(jQuery);