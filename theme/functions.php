<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 17:58
	 */


	if(!function_exists('send_mail')){

		/**
		 * Send html content mail
		 * @param string $to - оставьте поле пустым, чтобы писбмо было отправлено супер-администратору сайта
		 * @param        $subject
		 * @param        $content
		 */
		function send_mail( $to = '', $subject = '', $content = '' ){ //TODO: не работает корректно
			if( !is_string( $to ) || trim( $to ) == '' ){
				$to = get_bloginfo( 'admin_email' );
				if( !filter_var( $to, FILTER_VALIDATE_EMAIL ) ){
					$to = get_option( 'admin_email' );
				}
			}
			$headers = [ 'From: ' . get_bloginfo( 'name' ) . ' <noreply@' . $_SERVER['SERVER_NAME'] . '>' ];
			$headers[] = 'Reply-To: noreply@' . $_SERVER['SERVER_NAME'] . '';
			add_filter( 'wp_mail_content_type', function(){ return "text/html"; } );
			wp_mail( $to, html_entity_decode( $subject ), $content, $headers );
		}

	}