jQuery(document).ready(function ($) {
    var $mmenu = $(".mm-menu_offcanvas");
    var mmenu_args = {
        "navbars": [
            {
                "position": "top",
                "content": [
                    "breadcrumbs",
                    "close"
                ]
            },
            {
                "position": "bottom",
                "content": $mmenu.data('bottom-content')
            }
        ],
        hooks: {
            'open:before': function () {
                $('.mh-head.mh-sticky').each(function () {
                    $(this).css('top', $(this).offset().top);
                });
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