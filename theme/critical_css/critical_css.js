jQuery(document).ready(function ($) {

    /**
     * Critical CSS Generator
     * @type {{}}
     */
    var hiweb_theme_cCSS = {

        cHeight: 1000,
        cElements: 'html, body, div, a, span, p, b, strong, nav, ul, ol, li, img, form, input, textarea, select, button, main, side, header, footer, [class], [id], h1, h2, h3, h4, h5, h6',
        cElementsDisallow: 'head, link, meta, script',

        init: function () {
            if (typeof hiweb_theme_current_template_hash_id === 'string') {
                let $cElements = hiweb_theme_cCSS.extract_cHTML('html');
                $.ajax({
                    url: '/wp-json/hiweb_theme/criticalCss/chtml',
                    dataType: 'json',
                    type: 'post',
                    data: {hash: hiweb_theme_current_template_hash_id, chtml: $cElements[0][0].outerHTML},
                    success: function (response) {
                        console.info('hiWeb Theme: critical CSS created!');
                    }
                });
            }
            if (typeof hiweb_theme_critical_defer_styles === 'object') {
                let $head = $('head');
                $head.append('<!--Defer STYLES-->');
                for (let index in hiweb_theme_critical_defer_styles) {
                    $("<link/>", {
                        rel: "stylesheet",
                        type: "text/css",
                        href: hiweb_theme_critical_defer_styles[index]
                    }).appendTo("head");
                }
                $head.append('<!--End Defer STYLES-->');
                $('script[data-critical-defer-scripts]').remove();
            }
        },

        /**
         *
         * @param $element
         * @returns {*}
         */
        extract_cHTML: function ($element) {
            $element = $($element);
            if ($element.length === 0) return '';
            let R = [];
            $($element).each(function () {
                let $source = $(this);
                if ($source.offset().top <= hiweb_theme_cCSS.cHeight) {
                    let $current = $source.first().clone().empty();
                    //let $children = hiweb_theme_cCSS.extract_cHTML($source.children(hiweb_theme_cCSS.cElements));
                    let $children = hiweb_theme_cCSS.extract_cHTML($source.children().not(hiweb_theme_cCSS.cElementsDisallow));
                    for (let index in $children) {
                        $current.append($children[index]);
                    }
                    R.push($current);
                }
            });
            return R;
        },

    };

    hiweb_theme_cCSS.init();
});