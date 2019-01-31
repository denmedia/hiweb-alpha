jQuery(document).ready(function ($) {
    var $mmenu = $(".hiweb-mmenu-nav");
    var mmenu_args = {
        language: 'ru',
        // "navbars": [
        //     {
        //         "position": "top",
        //         "content": [
        //             "breadcrumbs",
        //             "close"
        //         ]
        //     },
        //     {
        //         "position": "bottom",
        //         "content": $mmenu.data('bottom-content')
        //     }
        // ],
        extensions: ["fx-menu-slide", "pageshadow", "effect-menu-slide", "position-right"],
        hooks: {
            'open:before': function () {
                $('.mh-head.mh-sticky').each(function () {
                    $(this).css('top', $(this).offset().top);
                });
            },
            'open:finish': function(){
                $('.mm-page').addClass('mm-opened');
            },
            'close:before': function(){
                $('.mm-page').removeClass('mm-opened');
            },
            'close:finish': function () {
                $('.mh-head.mh-sticky').css('top', '');
                $('.hamburger[href]').removeClass('is-active');

            }
        }
    };
    ///
    $mmenu.mmenu(mmenu_args, {
        language: "ru"
    }).find('ul.mm-listview').css('visibility','');
    ///
    if (typeof $('body').swipe === 'function') {
        $('.mm-panels').swipe({
            //excludedElements: '.owl-carousel, input, form, button, .fancybox-inner',
            swipeLeft: function () {
                $(".mm-menu_offcanvas").data("mmenu").close();
            },
        });
    }
});