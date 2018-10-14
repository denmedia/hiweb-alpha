jQuery(document).ready(function ($) {
    var $header_scroll = $('.mh-head[data-use-stick]');
    if ($header_scroll.length > 0 && typeof $header_scroll.mhead === 'function') {
        $header_scroll.each(function () {
            $(this).mhead({
                scroll: {
                    hide: 10,
                    show: 10,
                    tolerance: 6
                }
            });
        });
    }
});