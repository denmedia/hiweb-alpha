jQuery(document).ready(function ($) {
    $('.hiweb-theme-widget-slider.owl-carousel').each(function () {
        var $slider_root = $(this);
        var slider_timeout = null;
        var slider_current_index = 0;
        var $slide_interval = $(this).data('slide-interval');
        var slider_set_timeout = function () {
            clearTimeout(slider_timeout);
            slider_timeout = setTimeout(function () {
                $slider_root.trigger('next.owl.carousel');
            }, $slide_interval);
        };
        $slider_root.owlCarousel({
            items: 1,
            nav: true,
            loop: true,
            autoplay: false,
            onInitialized: function (event) {
                var is_video = $slider_root.find('.owl-item.active > .slide > video').length > 0;
                if (is_video) {
                    var $slide = $slider_root.find('.owl-item.active > .slide > video').attr('autoplay', '');
                    $slide.find('.owl-item.active > .slide > video').trigger('play');
                    $slide[0].play();
                } else {
                    slider_set_timeout();
                }
                $slider_root.find('.owl-item > .slide > video').on('ended', function () {
                    $slider_root.trigger('next.owl.carousel');
                });
                setTimeout(function () {
                    $slider_root.find('.owl-item.active').addClass('showed');
                }, 500);
            },
            onTranslate: function () {
                $slider_root.find('.owl-item').removeClass('showed');
            },
            onTranslated: function () {
                $slider_root.find('.owl-item.active').addClass('showed');
            }
        }).on('changed.owl.carousel', function (event) {
            if (slider_current_index !== event.page.index) {
                slider_current_index = event.page.index;

                $slider_root.find('.owl-item > .slide > video').removeAttr('autoplay').trigger('pause').each(function () {
                    this.currentTime = 0;
                });
                var is_video = $slider_root.find('.owl-item').eq(event.item.index).find('video').length > 0;
                if (is_video) {
                    var $slide = $slider_root.find('.owl-item').eq(event.item.index).find('video');
                    $slide.trigger('play')  [0].play();
                } else {
                    slider_set_timeout();
                }
            }
        }).on('mouseover tap', function () {
            clearTimeout(slider_timeout);
        }).on('mouseout', function () {
            slider_set_timeout();
        });
        ///
    });
});