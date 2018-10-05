<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 21:23
	 */

	namespace hiweb_theme;


	class hooks{

		static function init(){

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
			}
		}

	}