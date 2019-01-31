<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 03:02
	 */

	//Disable WP Blocks Library
	add_action( 'wp_print_styles', function(){
		if( !\theme\includes\frontend::$use_wp_block_library && \hiweb\context::is_frontend_page() )
			wp_styles()->dequeue( 'wp-block-library' );
	} );

	//Remove default jQuery
	add_action( 'wp_enqueue_scripts', function(){
		if( !\theme\includes\frontend::$use_wp_jquery_core && \hiweb\context::is_frontend_page() )
			wp_deregister_script( 'jquery' );
	} );