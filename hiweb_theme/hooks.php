<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 21:23
	 */

	namespace hiweb_theme;


	use hiweb\arrays;
	use hiweb\path;
	use hiweb_theme\widgets\forms;


	class hooks{

		static function init(){

			//Remove default jQuery
			add_action( 'wp_enqueue_scripts', function(){
				wp_deregister_script( 'jquery' );
			} );

			//Remove Emoji
			if( !head::$use_emoji ){
				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
			}

			//Remove Meta Generator
			if( !head::$show_meta_generator ){
				//add_filter( 'the_generator', '__return_false' ); //?
				remove_action( 'wp_head', 'wp_generator' );
			}

			if( !head::$show_link_wlwmanifest ){
				remove_action( 'wp_head', 'wlwmanifest_link' );
			}

			if( !head::$show_link_rel_EditURI ){
				remove_action( 'wp_head', 'rsd_link' );
			}

			if( !head::$show_RSS_links ){
				remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
				remove_action( 'wp_head', 'feed_links_extra', 3 );
			}

			if( !head::$show_restApi_link ){
				remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
				remove_action( 'wp_head', 'rest_output_link_wp_head' );
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
				remove_action( 'template_redirect', 'rest_output_link_header', 11 );
			}

			if( !head::$use_wp_embed ){
				add_action( 'wp_footer', function(){
					wp_deregister_script( 'wp-embed' );
				} );
			}

			add_action( 'hiweb_theme_body_sufix_after', function(){
				?>
				<script defer src="<?= get_rest_url( null, 'hiweb_theme/theme.js' ) ?>?s=<?= implode( '|', includes::$defer_script_files ) ?>"></script><?php
			}, 99999 );

			add_action( 'rest_api_init', function(){

				register_rest_route( 'hiweb_theme', 'theme.js', [
					'methods' => 'get',
					'callback' => function(){
						$seconds_to_cache = 3600;
						$ts = gmdate( "D, d M Y H:i:s", time() + $seconds_to_cache ) . " GMT";
						header( "Expires: $ts" );
						header( "Pragma: cache" );
						header( "Cache-Control: max-age=$seconds_to_cache" );
						header( 'Content-Type: application/javascript' );

						$scripts = explode( '|', path::request( 's' ) );
						$R = [];
						foreach( $scripts as $script ){
							$find_path = [
								HIWEB_THEME_ASSETS_DIR . '/js/rest-' . $script . '.min.js',
								HIWEB_THEME_ASSETS_DIR . '/js/rest-' . $script . '.js',
							];
							$file_exist = false;
							foreach( $find_path as $path ){
								if( file_exists( $path ) && is_file( $path ) && is_readable( $path ) ){
									$R[] = '// ' . path::path_to_url( $path ) . "\r" . file_get_contents( $path );
									$file_exist = true;
									break;
								} else {
									$R[] = $path . "\r";
								}
							}
							if( !$file_exist ){
								$R[] = '//' . $script . "\r//file not exists!";
							}
						}
						if( count( $R ) == 0 ){
							echo '//empty script file';
						} else {
							echo implode( "\r\r", $R );
						}
						die;
					}
				] );

				register_rest_route( 'hiweb_theme', 'widgets/forms/submit', [
					'methods' => 'post',
					'callback' => function(){
						return forms::get( $_POST['form_id'] )->do_submit( $_POST );
					}
				] );
			} );

			add_shortcode( 'hiweb-theme-widget-form', function( $atts ){
				return forms::get( arrays::get_value_by_key( $atts, 'id' ) )->get_html();
			} );
		}

	}