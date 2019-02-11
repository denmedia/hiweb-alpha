<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08/12/2018
	 * Time: 17:46
	 */

	add_action( 'rest_api_init', function(){

		register_rest_route( 'hiweb_theme', 'pages_cache/queue_next', [
			'methods' => 'get',
			'callback' => function(){
				return \hiweb_theme\pagesCache\queue::next();
			}
		] );
	} );
