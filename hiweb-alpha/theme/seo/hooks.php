<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:37
	 */

	use hiweb\urls;


	add_filter( 'get_the_archive_title', function( $title ){
		if( function_exists( 'get_queried_object' ) ){
			$queried_object = get_queried_object();
			if( $queried_object instanceof WP_Post_Type ){
				$archive_title = get_field( 'archive-title-' . $queried_object->name, \theme\seo::$admin_menu_main );
				if( $archive_title != '' ){
					return $archive_title;
				} else {
					return $queried_object->labels->name;
				}
			}
		}
		return $title;
	} );

	add_action( 'wp', function(){
		if( function_exists( 'get_queried_object' ) ){
			$queried_object = get_queried_object();
			if( $queried_object instanceof WP_Post ){
				if( get_field( 'enable-' . $queried_object->post_type, 'hiweb-seo-main' ) ){
					add_filter( 'wp_title', function( $title ){
						if( get_field( 'seo-meta-title' ) != '' ){
							return get_field( 'seo-meta-title' );
						}
						return $title;
					} );
					if( get_field( 'seo-meta-keywords' ) != '' ) \theme\html_layout\tags\head::add_html_addition( '<meta name="keywords" content="' . htmlentities( get_field( 'seo-meta-keywords' ), ENT_QUOTES, 'UTF-8' ) . '" />' );
					if( get_field( 'seo-meta-description' ) != '' ) \theme\html_layout\tags\head::add_html_addition( '<meta name="description" content="' . htmlentities( get_field( 'seo-meta-description' ), ENT_QUOTES, 'UTF-8' ) . '" />' );
				}
			} elseif( $queried_object instanceof WP_Term ) {
				//META TITLE
				add_filter( 'wp_title', function( $title ){
					$title .= ( is_paged() ? ' - страница ' . get_query_var( 'paged' ) : '' );
					return $title;
				} );
				///SEO META
				if( get_field( 'seo-meta-keywords' ) != '' ) \theme\html_layout\tags\head::add_html_addition( '<meta name="keywords" content="' . htmlentities( get_field( 'seo-meta-keywords' ), ENT_QUOTES, 'UTF-8' ) . '" />' );
				if( get_field( 'seo-meta-description' ) != '' ) \theme\html_layout\tags\head::add_html_addition( '<meta name="description" content="' . htmlentities( get_field( 'seo-meta-description' ), ENT_QUOTES, 'UTF-8' ) . '" />' );
				add_filter( 'single_cat_title', function( $title_h1 ){
					if( get_field( 'seo-custom-h1' ) != '' ){
						return get_field( 'seo-custom-h1' );
					}
					return $title_h1;
				}, 10 );
				///CUSTOM THE POST TITLE
				add_filter( 'single_tag_title', function( $title_h1 ){
					if( get_field( 'seo-custom-h1' ) != '' ){
						return get_field( 'seo-custom-h1' );
					}
					return $title_h1;
				}, 10 );
				///CUSTOM THE TERM TITLE
				add_filter( 'single_term_title', function( $title_h1 ){
					if( get_field( 'seo-custom-h1' ) != '' ){
						return get_field( 'seo-custom-h1' );
					}
					return $title_h1;
				}, 10 );
			}
		}
		if(\theme\seo::$option_use_paginate_canonical && function_exists( 'is_paged' ) && is_paged() ){
//			$current_url = \hiweb\urls::get()->get_url();
//			$current_url = preg_replace( '/(?<paged>\/page\/[\d]+\/?)$/im', '', $current_url );
			theme\html_layout\tags\head::add_html_addition( '<link rel="canonical" href="' . get_pagenum_link( 1 ) . '" />' );
		}
	} );

	///REDIRECT SLASH END
	add_action( 'wp', function(){
		if(\hiweb\context::is_frontend_page() && theme\seo::$option_force_redirect_slash_end ){
			if( preg_match( '~\/$~i', urls::get_current_url(false) ) == 0 && !is_search() && strpos(urls::get_current_url(false),'?') === false ){
				wp_redirect(urls::get()->get_url().'/', 301, 'hiweb-theme-seo');
			}
		}
	} );

	//apply_filters( 'post_link', $permalink, $post, $leavename )
	//apply_filters( 'pre_term_link', $termlink, $term )
	add_filter( 'term_link', function( $termlink, $term, $taxonomy ){
		if( theme\seo::$option_term_permalink_force_slash_end && preg_match( '~\/$~i', $termlink ) == 0 && strpos($termlink,'?') === false){
			$termlink .= '/';
		}
		return $termlink;
	}, 10, 3 );