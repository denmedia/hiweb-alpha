<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 16:15
	 */

	use theme\forms;


	add_action( 'rest_api_init', function(){
		register_rest_route( 'hiweb_theme', 'widgets/forms/submit', [
			'methods' => 'post',
			'callback' => function(){
				return forms::get( $_POST['hiweb-theme-widget-form-id'] )->do_submit( $_POST );
			}
		] );
	} );