jQuery(document).ready(function ($) {
    $('.stellarnav').each(function () {
        $(this).stellarNav({
            theme: 'plain',
            breakpoint: 0,
            sticky: false,
            position: 'static',
            showArrows: $(this).is('[data-arrows="1"]'),
            closeBtn: false,
            scrollbarFix: true
        });
    });
})
;