<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/11/2018
	 * Time: 15:57
	 */

	use hiweb\paths\path;
	use hiweb_theme\tools\criticalCss;


	add_filter( 'template_include', '\hiweb_theme\tools\criticalCss::_set_current_theme_template_file', 9999999 );
	///print cCSS, if cache is exists
	add_action( 'wp_head', '\hiweb_theme\tools\criticalCss::hooks_wp_head' );
	///collect style files to cache
	add_action( 'shutdown', '\hiweb_theme\tools\criticalCss::hook_shutdown', 999999999 );

	add_action( 'rest_api_init', function(){

		register_rest_route( 'hiweb_theme', 'criticalCss/chtml', [
			'methods' => 'post',
			'callback' => '\hiweb_theme\tools\criticalCss::hook_make_ccss'
		] );
	} );

	add_filter( '\hiweb\css::_add_action_wp_register_script-enable', function( $enable, \hiweb\css\file $css ){
		if( criticalCss::get_styles()->is_cache_exists() ){
			return false;
		}
		return $enable;
	}, 10, 2 );