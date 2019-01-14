jQuery(document).ready(function ($) {
    // Scroll to a Specific Div
    var $scrolltop_widget = $('.hiweb-theme-widget-scrolltop');
    if ($scrolltop_widget.length) {
        var scrolltop_widget_show = function () {
            var windowpos = $(window).scrollTop();
            if (windowpos >= $scrolltop_widget.data('scroll-offset')) {
                $scrolltop_widget.fadeIn(300);
            } else {
                $scrolltop_widget.fadeOut(300);
            }
        };
        $scrolltop_widget.on('click', function () {
            var target = $(this).attr('data-target');
            // animate
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000);
        });
        scrolltop_widget_show();

        $(window).on('scroll', scrolltop_widget_show);
    }
});