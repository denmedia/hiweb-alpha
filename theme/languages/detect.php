<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-10
	 * Time: 18:11
	 */

	namespace theme\languages;


	use hiweb\urls;
	use theme\languages;


	class detect{

		static $url_prefix = '';
		static $uri_original = '';
		static $browser_lang_accept_id;

		private static $lang_id;

		static function init(){
			list($dirs, $params) = explode('?',$_SERVER['REQUEST_URI']);
			$explode = explode( '/', $dirs );
			self::$uri_original = $_SERVER['REQUEST_URI'];
			if( languages::is_exists( $explode[1] ) ){
				self::$url_prefix = $explode[1];
				unset ( $explode[1] );
			}
			$_SERVER['REQUEST_URI'] = join( '/', $explode ).($params != '' ? '?'.$params : '');
		}

		/**
		 * Get detect result
		 */
		static function get_id(){
			if( !is_string( self::$lang_id ) ){
				///URL PREFIX
				if( self::$url_prefix != '' && languages::is_exists( self::$url_prefix ) ){
					self::$lang_id = self::$url_prefix;
				} ///CHECK BROWSER
				elseif( self::get_id_by_browser() != '' ) {
					self::$lang_id = self::get_id_by_browser();
				} ///DEFAULT
				else {
					self::$lang_id = languages::get_default_id();
				}
			}
			return self::$lang_id;
		}


		/**
		 * @return bool|string
		 */
		static function get_id_by_browser(){
			if( !is_string( self::$browser_lang_accept_id ) ){
				self::$browser_lang_accept_id = '';
				if( array_key_exists( substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ), languages::get_languages() ) ){
					self::$browser_lang_accept_id = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
				}
			}

			return self::$browser_lang_accept_id;
		}


		/**
		 * @deprecated
		 * @return bool|string
		 */
		static protected function autodetect_lang_id(){
			$R = '';
			///CHECK SUBDOMAIN
			preg_match( '/^(?<subdomain>[\w\-_]+)\..*/i', $_SERVER['HTTP_HOST'], $domains );
			if( $R == '' && array_key_exists( 'subdomain', $domains ) && array_key_exists( $domains['subdomain'], self::get_languages() ) ){
				$R = $domains['subdomain'];
			}
			///CHECK URL LANG REQUEST
			if( $R == '' ){
				if( preg_match( '/^\/(?<lang_id>[\w\d-_]+)\/?.*/i', $_SERVER['REQUEST_URI'], $matches ) > 0 && isset( $matches['lang_id'] ) ){
					if( self::is_exists( $matches['lang_id'] ) ){
						$R = $matches['lang_id'];
					}
				}
			}
			///CHECK CURRENT POST/PAGE
			if( $R == '' && urls::get()->get_clear() != urls::root() && function_exists( 'get_queried_object' ) ){
				if( get_queried_object() instanceof \WP_Post && !is_front_page() )
					$R = self::get_post( get_queried_object_id() )->get_lang_id();
				if( get_queried_object() instanceof \WP_Term )
					$R = self::get_term( get_queried_object_id() )->get_lang_id();
			}
			///SET FROM SESSIONS
			//			if( session_id() == '' ) session_start();
			//			if( $R == '' && array_key_exists( self::$session_key, $_SESSION ) ){
			//				$test_lang_id = $_SESSION[ self::$session_key ];
			//				if( self::is_exists( $test_lang_id ) ){
			//					$R = $test_lang_id;
			//				}
			//			}

			///SET DEFAULT LANG
			if( $R == '' ){
				$R = self::get_default_id();
			}
			return $R;
		}

	}