<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:17
	 */

	register_rest_route( 'hiweb_theme', 'minify-js', [
		'methods' => 'get',
		'callback' => function(){
			$seconds_to_cache = 2.592e+6;
			$ts = gmdate( "D, d M Y H:i:s", time() + $seconds_to_cache ) . " GMT";
			header( "Expires: $ts" );
			header( "Pragma: cache" );
			header( "Cache-Control: max-age=$seconds_to_cache" );
			header( 'Content-Type: application/javascript' );

//			$scripts = explode( '|', \hiweb\urls::request( 's' ) );
//			$R = [];
//			///
//			foreach( $scripts as $script ){
//				$find_path = [
//					HIWEB_THEME_ASSETS_DIR . '/js/rest-' . $script . '.min.js',
//					HIWEB_THEME_ASSETS_DIR . '/js/rest-' . $script . '.js',
//				];
//				$file_exist = false;
//				foreach( $find_path as $path ){
//					$path = \hiweb\paths::get( $path );
//					if( $path->is_file() && $path->is_readable() ){
//						$R[] = strtr( $path->get_content(), [
//							'{hiweb-assets-url}' => HIWEB_URL_ASSETS,
//							'{hiweb-theme-url}' => HIWEB_THEME_URL,
//							'{hiweb-theme-assets-url}' => HIWEB_THEME_ASSETS_URL
//						] );
//						$file_exist = true;
//						break;
//					}
//				}
//				if( !$file_exist ){
//					$R[] = '//' . $script . "\r//file not exists!";
//				}
//			}
//			///
//			if( count( $R ) == 0 ){
//				echo '//empty script file';
//			} else {
//				$R = implode( "\r\r", $R );
//				$R = \hiweb\vendors\php_html_css_js_minifier::minify_js( $R );
//				echo $R;
//			}
			die;
		}
	] );