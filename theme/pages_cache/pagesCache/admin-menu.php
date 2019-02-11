<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 12:12
	 */

	if( is_admin() ){
		\theme\includes\admin::fontawesome();
	}

	add_admin_menu_page( \theme\pages_cache::$_admin_menu_slug, '<i class="fas fa-car-battery"></i> Pages cache', 'options-general.php' )->function_page( function(){
		include_once __DIR__ . '/admin-menu-page.php';
	} )->use_default_form( false );


	////
	add_action( 'admin_bar_menu', function( $wp_admin_bar ){
		/** @var WP_Admin_Bar $wp_admin_bar */
		if( \hiweb\context::is_frontend_page() && \theme\pages_cache::is_init() ){
			if(\theme\pages_cache::get_cache()->is_exists()){
				$args = [
					'id' => 'hiweb-theme-pagescache-update',
					'title' => '<span style="font-size: 1.2em">♺</span> Обновить кэш страницы',
					'href' => \hiweb\urls::get()->set_params( [ 'nocache' => 1 ] ),
					'meta' => [ 'class' => 'my-toolbar-page' ]
				];
				$wp_admin_bar->add_node( $args );
			}else{

			}
		}
	}, 999 );