<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:18
	 */

	use hiweb\arrays;
	use theme\forms;


	add_shortcode( 'hiweb-theme-widget-form', function( $atts ){
		return forms::get( arrays::get( $atts )->value_by_key( 'id' ) )->get_html();
	} );

	add_shortcode( 'hiweb-theme-widget-form-button', function( $atts ){
		return forms::get( arrays::get( $atts )->value_by_key( 'id' ) )->get_fancybox_button( arrays::get( $atts )->value_by_key( 'html' ) );
	} );