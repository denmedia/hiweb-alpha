jQuery(document).ready(function ($) {
    $(".mm-menu_offcanvas").mmenu({
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
                "content": [
                    '<div class="flex-fill header-block align-self-center d-none d-lg-block pl-lg-2 d-md-block d-sm-block"><p><a href="tel:+79291234567" target="_blank"><i class="fas fa-phone-square"></i> +7 (929) 1234567</a></p><p><a href="tel:+79291234567" target="_blank"><i class="fas fa-phone-square"></i> +79291234567</a></p><p><a href="mailto: info@dexia.ru" target="_blank"><i class="fas fa-envelope"></i> callto: info@dexia.ru</a></p></div>'
                ]
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
    }, {
        language: "ru"
    });
    if (typeof $('body').swipe === 'function') {
        $('.mm-panels').swipe({
            //excludedElements: '.owl-carousel, input, form, button, .fancybox-inner',
            swipeLeft: function () {
                $(".mm-menu_offcanvas").data("mmenu").close();
            },
        });
    }
});