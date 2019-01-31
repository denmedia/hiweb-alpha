<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06/12/2018
	 * Time: 13:38
	 */

	namespace theme\tools\pagesCache;


	/**
	 * Класс для прямой работы с кэшем из index-hiweb-cache.php
	 * Class direct_index
	 * @package hiweb_theme\tools\pagesCache
	 */
	class pagesCache_direct{

		private static $current_cache;
		private static $cache_start_query = false;


		/**
		 * @return cache_item
		 */
		static function get_current_cache(){
			if( !self::$current_cache instanceof cache_item ){
				self::$current_cache = new cache_item( tools::get_request_uri() );
			}
			return self::$current_cache;
		}


		/**
		 * @return bool
		 */
		static function get_cache_start_query(){
			return self::$cache_start_query;
		}


		static function the_start(){
			require_once __DIR__ . '/tools.php';
			require_once __DIR__ . '/options.php';
			require_once __DIR__ . '/indexInject.php';
			require_once __DIR__ . '/cache_item.php';
			//
			if( isset( $_GET['nocache'] ) && $_GET['nocache'] == '1' )
				self::get_current_cache()->flush();
			//
			if( strpos( tools::get_request_uri(), '/wp-json/hiweb_theme/theme-js' ) === 0 ){
				///
				$hiweb_theme_dir = dirname( dirname( dirname( __DIR__ ) ) );
				$assets_dir = $hiweb_theme_dir . '/assets';
				$vendors_dir = $hiweb_theme_dir . '/vendors';
				$hiweb_dir = $hiweb_theme_dir . '/hiweb-core-3';
				///
				$scripts_id = md5( tools::get_request_uri() );
				$cache_dir = dirname( dirname( $hiweb_theme_dir ) ) . '/cache/hiweb-theme-js';
				$cache_file = $cache_dir . '/' . $scripts_id . '.js';
				///headers
				$seconds_to_cache = 2.592e+6;
				$ts = gmdate( "D, d M Y H:i:s", time() + $seconds_to_cache ) . " GMT";
				header( "Expires: $ts" );
				header( "Pragma: cache" );
				header( "Cache-Control: max-age=$seconds_to_cache" );
				header( 'Content-Type: application/javascript' );
				///
				if( file_exists( $cache_file ) && is_readable( $cache_file ) ){
					echo "//this is cache!\n".file_get_contents( $cache_file );
					die;
				}
				if( !file_exists( $cache_dir ) ){
					mkdir( $cache_dir, 0755, true );
				}
				$scripts = explode( '|', $_GET['s'] );
				$R = [];
				///
				foreach( $scripts as $script ){
					$find_path = [
						$assets_dir . '/js/rest-' . $script . '.min.js',
						$assets_dir . '/js/rest-' . $script . '.js',
					];
					$file_exist = false;
					foreach( $find_path as $path ){
						if( is_file( $path ) && is_readable( $path ) ){
							$R[] = strtr( file_get_contents( $path ), [
								//								'{hiweb-assets-url}' => HIWEB_URL_ASSETS,
								//								'{hiweb-theme-url}' => HIWEB_THEME_URL,
								//								'{hiweb-theme-assets-url}' => HIWEB_THEME_ASSETS_URL
							] );
							$file_exist = true;
							break;
						}
					}
					if( !$file_exist ){
						$R[] = '//' . $script . "\r//file not exists!";
					}
				}
				///
				if( count( $R ) == 0 ){
					echo '//empty script file';
				} else {
					require_once $hiweb_dir . '/vendors/php_html_css_js_minifier.php';
					$R = implode( "\r\r", $R );
					$R = \hiweb\vendors\php_html_css_js_minifier::minify_js( $R );
					echo $R;
					file_put_contents( $cache_file, $R );
				}
				die;
			}
			//
			$method = __METHOD__;
			$is_allow_user = self::get_current_cache()->is_allowed_use( true );
			if( $is_allow_user === true ){
				$round_time = round( self::get_current_cache()->get_left_time() / 60, 1 );
				$content = self::get_current_cache()->get_content();
				echo "<!--{$method}: current cache start, time left: {$round_time}min, use '?nocache' to force disable cache-->{$content}<!--hiWeb Pages Cache: current cache end-->";
				die;
			}
		}


		static function the_end(){

			///do nothing

		}


	}