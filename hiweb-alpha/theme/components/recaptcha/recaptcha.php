<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 13:01
	 */
	
	namespace theme;
	
	
	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;
	
	
	/**
	 * Class recaptcha
	 * @package theme
	 * @version 1.3
	 */
	class recaptcha{
		
		static $admin_menu_slug = 'hiweb-recaptcha';
		static $admin_menu_parent = 'options-general.php';
		static $options_object_recaptcha;
		static $last_response = null;
		
		
		static function init(){
			///Options reCaptcha
			require_once __DIR__ . '/options.php';
            include_frontend_js(__DIR__ . '/App.min.js', 'jquery-core');
			///
			add_filter( '\theme\forms\form::do_submit-allow_submit_form', function( $array, $form, $submit_data ){
				if( self::is_enable() && !self::get_recaptcha_verify() ){
					$R = [ 'success' => false, 'message' => get_field( 'text-error', recaptcha::$admin_menu_slug ), 'status' => 'warn' ];
					//$R['recaptcha_error'] = self::$last_response;
					return $R;
				}
				return null;
			}, 10, 3 );
		}

		static function _wc_support_init(){
            require_once __DIR__ . '/woocommerce.php';
        }
		
		
		/**
		 * @param bool $public
		 * @return string
		 */
		static function get_recaptcha_key( $public = true ){
			self::init();
			return get_field( ( $public ? 'public-key' : 'private-key' ), self::$admin_menu_slug );
		}
		
		
		/**
		 * @return bool
		 */
		static function is_enable(){
			return ( trim( self::get_recaptcha_key() ) != '' && get_field( 'enable', self::$admin_menu_slug ) );
		}
		
		
		/**
		 * @return float
		 */
		static function get_minimal_score(){
			return floatval( get_field( 'min-score', self::$admin_menu_slug ) );
		}
		
		
		/**
		 * @param string $post_name
		 * @param bool   $return_boolean - true => возвращает тоько true или false после проверки
		 * @return array|bool|mixed|object
		 * @version 1.2
		 */
		static function get_recaptcha_verify( $post_name = 'recaptcha-token', $return_boolean = true ){
			if( self::is_enable() == '' ) return true;
			$post_token_value = PathsFactory::request( $post_name );
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
			$response_std = json_decode( $response );
			self::$last_response = $response_std;
			if( $return_boolean ){
				return !$response_std->success ? ( floatval( $response_std->score ) >= self::get_minimal_score() ) : true;
			}
			else{
				return [
					json_decode( $response ),
					'secret' => self::get_recaptcha_key( false ),
					'response' => $post_token_value,
					'remoteip' => $_SERVER['REMOTE_ADDR']
				];
			}
		}
		
		
		static function the_input(){
			if( self::is_enable() ){
				include_frontend_js( 'https://www.google.com/recaptcha/api.js?render=' . self::get_recaptcha_key( true ) );
				$id_rand = Strings::rand();
				?>
				<input type="hidden" id="<?= $id_rand ?>" name="recaptcha-token" data-key="<?= self::get_recaptcha_key() ?>" data-hiweb-form-recaptcha-input>
				<?php
			}
		}
		
		
		/**
		 * @return mixed
		 */
		static function get_error_message(){
			return get_field( 'text-error', self::$admin_menu_slug );
		}
		
	}