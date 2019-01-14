jQuery(document).ready(function ($) {
    ///
    var url = new URL(window.location.href);
    url.searchParams.delete('nocache');
    window.history.pushState("", "", url.toString());
    ///
    $.ajax({
        url: document.location.href + '?nocache',
        success: function (response) {

            response = $('<div/>').append(response);

            ///CSS
            response.find('link[rel="stylesheet"][href]').each(function () {
                let href = $(this).attr('href');
                if ($('html').find('link[rel="stylesheet"][href="' + href + '"]').length === 0) {
                    $('head').append($(this)[0].outerHTML);
                }
            });

            let admin_bar = response.find('#wpadminbar');
            if (admin_bar.length > 0) {
                $('body').append(admin_bar[0].outerHTML);
            }

        }
    });
});