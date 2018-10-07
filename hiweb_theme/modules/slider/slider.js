jQuery(document).ready(function ($) {
    $('.hiweb-theme-slider.owl-carousel').each(function () {
        $(this).owlCarousel({
            items: 1
        });
        ///
        var $slider_root = $(this);
        var slider_timeout = null;
        var slider_current_index = 0;
        var $slide_interval = $(this).data('slide-interval');
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
                    slider_timeout = setTimeout(function () {
                        $slider_root.trigger('next.owl.carousel');
                    }, $slide_interval);
                }

                $slider_root.find('.owl-item > .slide > video').on('ended', function () {
                    $slider_root.trigger('next.owl.carousel');
                });
            }
        }).on('changed.owl.carousel', function (event) {
            if (slider_current_index !== event.page.index) {
                slider_current_index = event.page.index;
                clearTimeout(slider_timeout);
                $slider_root.find('.owl-item > .slide > video').removeAttr('autoplay').trigger('pause').each(function () {
                    this.currentTime = 0;
                });
                var is_video = $slider_root.find('.owl-item').eq(event.item.index).find('video').length > 0;
                if (is_video) {
                    var $slide = $slider_root.find('.owl-item').eq(event.item.index).find('video');
                    $slide.trigger('play')  [0].play();
                } else {
                    slider_timeout = setTimeout(function () {
                        $slider_root.trigger('next.owl.carousel');
                    }, $slide_interval);
                }
            }
        });
        ///
    });
});