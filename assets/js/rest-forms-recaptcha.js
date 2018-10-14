jQuery(document).ready(function () {
    grecaptcha.ready(function () {
        $('form input[name="recaptcha-token"]').each(function () {
            var $input_token = $(this);
            grecaptcha.execute($input_token.data('key')).then(function (token) {
                $input_token.val(token);
                $input_token.closest('form').find('[type="submit"]').removeAttr('disabled');
            });
        });
    });
});