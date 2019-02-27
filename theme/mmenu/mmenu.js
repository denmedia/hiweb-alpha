jQuery(document).ready(function ($) {
    $(".hiweb-mmenu-nav").each(function () {
        var $mmenu = $(this);

        var mmenu_args = {
            language: 'ru',
            extensions: $mmenu.is('[data-extensions]') ? JSON.parse($mmenu.attr('data-extensions')) : [],
            hooks: {
                'open:before': function () {
                    $('.mh-head.mh-sticky').each(function () {
                        $(this).css('top', $(this).offset().top);
                    });
                },
                'open:finish': function () {
                    $('.mm-page').addClass('mm-opened');
                },
                'close:before': function () {
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
        }).find('ul.mm-listview').css('visibility', '');
        $mmenu.find('.mm-navbar').append('<a class="mm-btn mm-btn_close mm-navbar__btn" href="#page" aria-owns="page"><span class="mm-sronly">Закрыть меню</span></a>');
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

});