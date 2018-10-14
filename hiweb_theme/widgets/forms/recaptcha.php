<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 13:01
	 */

	namespace hiweb_theme\widgets\forms;


	use hiweb\strings;
	use hiweb_theme\includes;
	use hiweb_theme\widgets\forms;


	class recaptcha{

		/**
		 * @param bool $public
		 * @return string
		 */
		static function get_recaptcha_key( $public = true ){
			return get_field( ( $public ? 'public-key' : 'private-key' ), forms::$options_name_recapthca );
		}


		/**
		 * @param string $post_name
		 * @param bool   $return_boolean - true => возвращает тоько true или false после проверки
		 * @return array|bool|mixed|object
		 */
		static function get_recaptcha_verify( $post_name = 'recaptcha-token', $return_boolean = true ){
			if( self::get_recaptcha_key() == '' ) return true;
			$post_token_value = \hiweb\path::request( $post_name );
			///
			$post_data = http_build_query( [
				'secret' => self::get_recaptcha_key( false ),
				'response' => $post_token_value,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			] );
			$opts = [
				'http' => [
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => $post_data
				]
			];
			$context = stream_context_create( $opts );
			$response = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify', false, $context );
			return $return_boolean ? json_decode( $response )->success : json_decode( $response );
		}


		static function the_input(){
			if( trim( self::get_recaptcha_key() ) == '' ) return;
			include_js( 'https://www.google.com/recaptcha/api.js?render=' . self::get_recaptcha_key(), [], false );
			$id_rand = strings::rand();
			?>
			<input type="hidden" id="<?= $id_rand ?>" name="recaptcha-token" data-key="<?= self::get_recaptcha_key() ?>" value="">
			<?php
		}

	}