<?php
	
	add_filter( 'wpseo_canonical', function( $bool ){
		if( get_field( 'yoast-canonical-disable', theme\seo::$admin_menu_main ) ) return false;
		return $bool;
	} );
	
	add_filter( 'wpseo_next_rel_link', function( $bool ){
		if( get_field( 'yoast-canonical-prev-next-disable', theme\seo::$admin_menu_main ) ) return false;
		return $bool;
	} );
	add_filter( 'wpseo_prev_rel_link', function( $bool ){
		if( get_field( 'yoast-canonical-prev-next-disable', theme\seo::$admin_menu_main ) ) return false;
		return $bool;
	} );