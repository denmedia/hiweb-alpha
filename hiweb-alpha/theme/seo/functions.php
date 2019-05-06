<?php

	//get_the_h1()

	if( !function_exists( 'get_the_h1' ) ){

		function get_the_h1(){

			if( function_exists( 'get_queried_object' ) ){
				$queried_object = get_queried_object();
				if( $queried_object instanceof WP_Post ){
					if( get_field( 'enable-' . $queried_object->post_type, 'hiweb-seo-main' ) ){
						if( get_field( 'enable-custom-h1-' . $queried_object->post_type, 'hiweb-seo-main' ) ){
							if( get_field( 'seo-custom-h1', $queried_object ) != '' ){
								return get_field( 'seo-custom-h1' );
							}
						}
					}
					return get_the_title( $queried_object );
				} elseif( $queried_object instanceof WP_Post_Type ) {
					if( get_field( 'enable-' . $queried_object->name, 'hiweb-seo-main' ) ){
						$archive_title = get_field( 'archive-title-' . $queried_object->name, 'hiweb-seo-main' );
						if( $archive_title != '' ){
							return $archive_title;
						}
					}
					return post_type_archive_title();
				} elseif( $queried_object instanceof WP_Term ) {
					$term_title = get_field( 'seo-custom-h1', $queried_object );
					if( $term_title != '' ){
						return $term_title;
					}
					return single_term_title();
				} else {
					return get_the_title();
				}
			}
		}
	}