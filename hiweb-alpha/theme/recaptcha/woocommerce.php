<?php
	
	use theme\recaptcha;
	
	
	add_action( 'woocommerce_checkout_after_order_review', function(){
		if( recaptcha::is_enable() ){
			recaptcha::the_input();
		}
	} );
	add_action( 'woocommerce_after_checkout_validation', function( $data, $errors ){
		if( recaptcha::is_enable() ){
			/** @var WP_Error $errors */
			if( !recaptcha::get_recaptcha_verify() ){
				$errors->add( 'recaptcha', recaptcha::get_error_message() );
			}
		}
	}, 10, 2 );