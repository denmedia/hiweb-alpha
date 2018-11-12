jQuery(document).ready(function ($) {
    $.ajax({
        url: '/wp-json/hiweb_theme/criticalCss/data',
        dataType: 'json',
        type: 'post',
        data: {hash: hiweb_theme_current_template_hash_id},
        success: function (response) {
            let selectors = [];
            for (let index in response) {
                if ($(response[index]).length > 0 && $(response[index]).offset().top < hiweb_theme_critical_offset) {
                    selectors.push(response[index]);
                }
            }
            $.ajax({
                url: '/wp-json/hiweb_theme/criticalCss/make',
                dataType: 'json',
                type: 'post',
                data: {hash: hiweb_theme_current_template_hash_id, selectors: selectors},
                success: function (response) {

                }
            });
        }
    });
});