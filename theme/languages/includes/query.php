<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06/11/2018
	 * Time: 14:31
	 */

	use theme\languages;


	add_filter( 'do_parse_request', function( $true, \WP $wp, $extra_query_vars ){
		$explode = explode( '/', $_SERVER['REQUEST_URI'] );
		if( languages::is_exists( $explode[1] ) ){
			if( languages::set_lang_id( $explode[1], $_SERVER['REQUEST_URI'] ) ){
				unset ( $explode[1] );
			}
		}
		$_SERVER['REQUEST_URI'] = join( '/', $explode );
		return true;
	}, 10, 3 );

	////FILTERING
	add_action( 'pre_get_posts', function( $wp_query ){
		/** @var WP_Query $wp_query */

		$condition = [];
		$lang_id = languages::get_current_id();
		if( is_admin() && $wp_query->is_main_query() ){
			///Admin Posts list
			if( is_admin() && isset( $_GET['lang'] ) && languages::is_exists( $_GET['lang'] ) ){
				$lang_id = $_GET['lang'];
				$condition = [ true ];
			}
		} else {
			///
			$condition = [
				!$wp_query->is_single() && $wp_query->is_main_query(),
				$wp_query->query['hiweb_theme_language_filter'],
			];
			///post types
			foreach( languages::get_post_types( true ) as $post_type ){
				if( $post_type == 'post' ){
					$condition[] = $wp_query->is_main_query() && $wp_query->is_archive() && $post_type == 'post';
				} else {
					$condition[] = $wp_query->is_main_query() && is_post_type_archive( $post_type );
				}
			}
			///Force filtering
			$condition[] = isset( $wp_query->query['hiweb_theme_language_filter'] ) ? $wp_query->query['hiweb_theme_language_filter'] : false;
			///Search Page
			//$condition[] = $wp_query->is_main_query() && $wp_query->is_search();
		}
		///
		foreach( $condition as $bool ){
			if( $bool === true ){
				languages::get_language( $lang_id )->filter_wp_query( $wp_query );
				break;
			}
		}
	} );

	add_action( 'pre_get_posts', function( $wp_query ){
		/** @var WP_Query $wp_query */
		if( !$wp_query->is_main_query() ) return;
		if( $wp_query->is_single() ){
			$lang_post = languages::get_post( $wp_query->query['name'] );
			//Проверка, существует ли страница, доступна ли локалия для нее
			if( $lang_post->is_exists() ){
				///Редирект slug на соответствующий URL братской странички
				if( languages::$url_change_set !== false && $lang_post->get_lang_id() != languages::get_current_id() ){
					if( $lang_post->get_sibling_post( languages::get_current_id(), true )->is_exists() ){
						console_log( 'Редирект slug на соответствующий URL братской странички' );
						wp_redirect( get_permalink( $lang_post->get_sibling_post( languages::get_current_id() )->get_post_id() ), 301 );  //редирект на оригианльный URL страницы/поста
						die;
					}
				}
				///Редирект статей на их оригинальные страницы с учетом префикса LANG-ID в URL запросе
				if( ( $lang_post->is_default() && languages::$url_change_set !== false ) || ( !$lang_post->is_default() && languages::$url_change_set === false ) ){
					console_log( 'Редирект статей на их оригинальные страницы с учетом префикса LANG-ID в URL запросе' );
					wp_redirect( get_permalink( $lang_post->get_post_id() ), 301 ); //редирект на оригианльный URL страницы/поста
					die;
				}
			}
		}
		if( $wp_query->is_page() && count( $wp_query->query ) == 0 ){
			$lang_post = languages::get_post( \hiweb\themes::get()->get_front_page() );
			if( languages::$url_change_set == false ){

			} else {
				if( $lang_post->get_sibling_post( languages::get_current_id() )->is_default() ){
					wp_redirect( get_page_link( $wp_query->query_vars['page_id'] ), 301 );
				} elseif( $lang_post->get_sibling_post( languages::get_current_id() )->is_exists() ) {
					///
				} else {
					//console_info( $wp_query );
					//$wp_query->set( 'page_id', get_option( 'page_on_front' ) );
				}
			}
		}
	} );

	///HOME URL
	add_filter( 'home_url', function( $url, $path, $orig_scheme, $blog_id ){
		if( is_admin() ) return $url;
		if( languages::get_current_language()->is_default() ) return $url;
		///
		if( $path == '' || $path == '/' ){
			$url = rtrim( $url, '/' ) . '/' . languages::get_current_id();
		}
		return $url;
	}, 10, 4 );

	///POSTS PERMALINK
	add_filter( 'pre_post_link', function( $permalink, $post, $leavename ){
		if( $post instanceof WP_Post && languages::is_post_type_allowed( $post->post_type ) ){
			$lang_post = languages::get_post( $post );
			if( !$lang_post->is_default() ) $permalink = '/' . $lang_post->get_lang_id() . $permalink;
		}
		return $permalink;
	}, 10, 3 );

	///PAGES PERMALINK
	add_filter( 'get_page_uri', function( $uri, $page ){
		if( languages::is_post_type_allowed( 'page' ) && $page instanceof WP_Post ){
			$lang_post = languages::get_post( $page );
			if( !$lang_post->is_default() ) $uri = '/' . $lang_post->get_lang_id() . '/' . $uri;
		}
		return $uri;
	}, 10, 3 );

	add_filter( 'pre_term_link', function( $termlink, $term ){
		if( $term instanceof WP_Term && languages::is_taxonomy_allowed( $term->taxonomy ) ){
			$lang_term = languages::get_term( $term );
			if( !$lang_term->is_default() ) $termlink = '/' . $lang_term->get_lang_id() . $termlink;
		}
		return $termlink;
	}, 10, 3 );

	////
	//apply_filters( 'wp_get_nav_menus', get_terms( 'nav_menu',  $args), $args )
	add_filter( 'wp_get_nav_menu_items', function( $items, $menu, $args ){
		if( !is_admin() ){
			$current_lang_id = languages::get_current_id();
			foreach( $items as $index => $item ){
				$R = '';
				switch( $item->object ){
					case 'category':
						$current_term = languages::get_term( $item->object_id );
						if( $current_term->get_lang_id() != $current_lang_id && $current_term->is_sibling_lang_exists( $current_lang_id ) ){
							$R = $current_term->get_sibling_term( $current_lang_id )->get_wp_term()->name;
							$items[ $index ]->url = get_term_link( $current_term->get_sibling_term( $current_lang_id )->get_term_id() );
						} else {
							$R = $current_term->get_wp_term()->name;
							$items[ $index ]->url = get_term_link( $current_term->get_term_id() );
						}
						break;
					case 'page':
						$current_post = languages::get_post( $item->object_id );
						if( $current_post->get_lang_id() != $current_lang_id && $current_post->is_sibling_lang_exists( $current_lang_id ) ){
							$R = $current_post->get_sibling_post( $current_lang_id )->get_wp_post()->post_title;
							$items[ $index ]->url = get_permalink( $current_post->get_sibling_post( $current_lang_id )->get_post_id() );
						} else {
							$R = $current_post->get_wp_post()->post_title;
							$items[ $index ]->url = get_permalink( $current_post->get_post_id() );
						}
						break;
				}
				if( $R != '' ){
					$items[ $index ]->title = $R;
				}
			}
		}
		return $items;
	}, 10, 3 );