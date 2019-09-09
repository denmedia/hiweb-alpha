<?php
	/*
	Plugin Name: hiWeb Core 4
	Plugin URI: http://plugins.hiweb.moscow/core
	Description: Framework Plugin for WordPress min v4.8
	Version: 4.0.0.0
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/

	add_action( 'after_setup_theme', function(){
		$R = load_theme_textdomain( 'hiweb-core-4', __DIR__ . '/languages' );
	} );

	if( version_compare( PHP_VERSION, '7.0.0' ) >= 0 ){
		//	    require_once __DIR__ . '/include/spl_autoload_register.php';
		//		require_once __DIR__ . '/traits/hidden_methods.php';
		//		require_once __DIR__ . '/include/define.php';
		//		require_once __DIR__ . '/include/init.php';
	} else {
		add_action( 'after_setup_theme', function(){
			die( __( 'Your version of PHP must be 7.0 or higher.', 'hiweb-core-4' ) );
		} );
	}