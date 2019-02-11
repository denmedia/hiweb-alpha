<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 25/11/2018
	 * Time: 11:18
	 */

	namespace theme\pages_cache;


	use hiweb\paths;


	class indexInject{

		private static $template_index_name = 'template-index-hiweb.php';
		private static $route_index_name = 'index-hiweb-cache.php';
		private static $template_htaccess_name = 'htaccess-template';


		/**
		 * @return string
		 */
		static function get_index_template_path(){
			$tempalte_path = __DIR__ . '/pagesCache/' . self::$template_index_name;
			if( !file_exists( $tempalte_path ) || !is_readable( $tempalte_path ) || !is_file( $tempalte_path ) ) return false;
			return $tempalte_path;
		}


		/**
		 *
		 */
		static function do_index_injection(){
			//Replace
			$htaccess = paths::get( '/.htaccess' );
			$htaccess_template = paths::get( __DIR__ . '/' . self::$template_htaccess_name );
			$B = true;
			///HTACCESS INJECT
			if( !$htaccess->is_exists() && $htaccess_template->is_writable() ){
				$B = $htaccess->FILE()->make_file( str_replace( '{index-file}', self::$route_index_name, $htaccess_template->get_content() ) );
				if( $B ){
					if( function_exists( 'console_info' ) ){
						console_info( '\hiweb_theme\tools\pages_cache\index_file::do_index_injection - файл .htaccess создан!' );
					}
				} elseif( function_exists( 'console_warn' ) ) {
					console_warn( '\hiweb_theme\tools\pages_cache\index_file::do_index_injection - не удалось создать файл .htaccess, возможно права на запись отсутствуют' );
				}
			} else {
				$htaccess_content = $htaccess->get_content();
				if( preg_match( '/(?<marker>#hiweb-theme-pages-cache-inject (?>start|end))/im', $htaccess_content ) < 1 ){
					$B = $htaccess->set_content( str_replace( '{index-file}', self::$route_index_name, $htaccess_template->get_content() ), - 1 );
					if( $B ){
						if( function_exists( 'console_info' ) ){
							console_info( __METHOD__ . ' - файл .htaccess изменен!' );
						}
					} elseif( function_exists( 'console_warn' ) ) {
						console_warn( __METHOD__ . ' - не удалось изменить .htaccess, возможно права на запись отсутствуют' );
					}
				}
			}
			///INDEX ROUTE MAKE
			if( $B ){
				$B = false;
				$index_file = paths::get( self::$route_index_name );
				if( !$index_file->is_exists() ){
					$index_template = paths::get( __DIR__ . '/' . self::$template_index_name );
					if( $index_template->get_content( '' ) != '' ){
						$B = $index_file->FILE()->make_file( strtr( $index_template->get_content( '' ), [
							'{hiweb-theme-pages-cache-direct}' => paths::get( __DIR__ )->get_path_relative() . '/pages_cache_direct.php'
						] ) );
					}
					///
					if( $B ){
						if( function_exists( 'console_info' ) ){
							console_info( '\hiweb_theme\tools\pages_cache\index_file::do_index_injection - файл '.self::$template_index_name.' создан!' );
						}
					} elseif( function_exists( 'console_warn' ) ) {
						console_warn( '\hiweb_theme\tools\pages_cache\index_file::do_index_injection - не удалось создать файл '.self::$template_index_name.', возможно права на запись отсутствуют' );
					}
				}
			}
			///

		}

	}