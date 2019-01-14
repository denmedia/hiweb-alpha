<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 21:23
	 */

	use hiweb\arrays;
	use hiweb_theme\head;
	use hiweb_theme\includes;
	use hiweb_theme\widgets\forms;


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

	add_action( 'wp_print_styles', function(){
		if( !includes::$wp_block_library )
			wp_styles()->dequeue( 'wp-block-library' );
	} );

	add_action( 'wp_head', function(){
		if( is_array( head::$code ) )
			foreach( head::$code as $code ){
				echo $code . "\r\n";
			}
	} );

	add_action( 'hiweb_theme_body_sufix_before', function(){
		if( count( includes::$defer_script_files ) > 0 ){
			$defer_url_scripts = get_rest_url( null, 'hiweb_theme/theme-js' ) . '?s=' . implode( '|', includes::$defer_script_files );
			$js = includes::js( $defer_url_scripts );
			$js->add_deeps( includes::jquery() );
		}
		if( count( includes::$async_script_files ) > 0 ){
			?>
			<script async src="<?= get_rest_url( null, 'hiweb_theme/theme-js' ) ?>?s=<?= implode( '|', includes::$async_script_files ) ?>"></script><?php
		}
	}, 99999 );

	add_action( 'rest_api_init', function(){

		register_rest_route( 'hiweb_theme', 'theme-js', [
			'methods' => 'get',
			'callback' => function(){
				$seconds_to_cache = 2.592e+6;
				$ts = gmdate( "D, d M Y H:i:s", time() + $seconds_to_cache ) . " GMT";
				header( "Expires: $ts" );
				header( "Pragma: cache" );
				header( "Cache-Control: max-age=$seconds_to_cache" );
				header( 'Content-Type: application/javascript' );

				$scripts = explode( '|', \hiweb\urls::request( 's' ) );
				$R = [];
				///
				foreach( $scripts as $script ){
					$find_path = [
						HIWEB_THEME_ASSETS_DIR . '/js/rest-' . $script . '.min.js',
						HIWEB_THEME_ASSETS_DIR . '/js/rest-' . $script . '.js',
					];
					$file_exist = false;
					foreach( $find_path as $path ){
						$path = \hiweb\paths::get( $path );
						if( $path->is_file() && $path->is_readable() ){
							$R[] = strtr( $path->get_content(), [
									'{hiweb-assets-url}' => HIWEB_URL_ASSETS,
									'{hiweb-theme-url}' => HIWEB_THEME_URL,
									'{hiweb-theme-assets-url}' => HIWEB_THEME_ASSETS_URL
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
					$R = implode( "\r\r", $R );
					$R = \hiweb\vendors\php_html_css_js_minifier::minify_js($R);
					echo $R;
				}
				die;
			}
		] );

		register_rest_route( 'hiweb_theme', 'widgets/forms/submit', [
			'methods' => 'post',
			'callback' => function(){
				return forms::get( $_POST['hiweb-theme-widget-form-id'] )->do_submit( $_POST );
			}
		] );
	} );

	add_shortcode( 'hiweb-theme-widget-form', function( $atts ){
		return forms::get( arrays::get( $atts )->value_by_key( 'id' ) )->get_html();
	} );

	add_shortcode( 'hiweb-theme-widget-form-button', function( $atts ){
		return forms::get( arrays::get( $atts )->value_by_key( 'id' ) )->get_fancybox_button( arrays::get( $atts )->value_by_key( 'html' ) );
	} );