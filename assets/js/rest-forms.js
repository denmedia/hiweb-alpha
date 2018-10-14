jQuery(document).ready(function () {

    var $form_widgets = $('.hiweb-theme-widget-form');
    $form_widgets.each(function () {
        var $form = $(this);
        $form.on('submit', function (e) {
            e.preventDefault(); // prevent native submit
            var $form_status = $('[data-form-status-id="' + $form.data('form-id') + '"]');
            $form_status.attr('data-status', 'wait');
            if (!$form_status.is('[data-wait-message]')) {
                $form_status.attr('data-wait-message', $form_status.find('.message').html());
            } else {
                $form_status.find('.message').html($form_status.data('wait-message'));
            }
            var $fancybox = $.fancybox.open({
                src: $form_status,
                type: 'inline',
                closeExisting: false,
                animationEffect: 'zoom-in-out'
            });
            $form.ajaxSubmit({
                dataType: 'json',
                success: function (response) {
                    $fancybox.close({
                        afterClose: function () {
                            $fancybox.destroy();
                        }
                    });
                    $fancybox = $.fancybox.open({
                        src: $form_status,
                        type: 'inline',
                        closeExisting: false,
                        animationEffect: 'zoom-in-out'
                    });
                    if (!response.hasOwnProperty('success')) {
                        $form_status.data('status', 'error');
                    } else {
                        $form_status.attr('data-status', response.status);
                        $form_status.find('.message').html(response.message);
                        if (response.status == 'success') {

                        }
                    }
                    setTimeout(function () {
                        $fancybox.close({
                            afterClose: function () {
                                $fancybox.destroy();
                            }
                        });
                    }, 1000);
                },
                error: function (response) {
                    console.error(response);
                }
            })
        });
    });


});