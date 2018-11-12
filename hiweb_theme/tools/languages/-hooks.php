<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/11/2018
	 * Time: 18:18
	 */

	///HOOKS
	use hiweb_theme\tools\languages;


	add_action( 'save_post', function( $post_id, $post, $update ){
		//
		if( wp_is_post_revision( $post_id ) || get_post( $post_id )->post_status != 'publish' ) return;
		//
		if( array_key_exists( languages::$post_meta_key_lang_id, $_POST ) ){
			update_post_meta( $post_id, languages::$post_meta_key_lang_id, $_POST[ languages::$post_meta_key_lang_id ] );
		}
	}, 10, 3 );

	add_action( 'current_screen', function( $current_screen ){
		if( languages::is_post_type_allowed( $current_screen->post_type ) && $current_screen->base == 'post' && $current_screen->action == 'add' ){
			if( !array_key_exists( languages::$post_create_sibling_get_key_id, $_GET ) ){
				return false;
			}
			$post = languages::get_post( $_GET[ languages::$post_create_sibling_get_key_id ] );
			if( !$post->is_exists() ){
				console_warn( 'Попытка создать локализированную версию несуществующей записи/страницы' );
				return false;
			}
			$lang_id = $_GET[ languages::$post_create_sibling_get_key_lang_id ];
			if( !languages::is_exists( $lang_id ) ){
				console_warn( 'Попытка создать локализированную версию записи/страницы в неизвестной локалии' );
				return false;
			}
			if( $post->get_sibling_post( $lang_id, true )->is_exists() ){
				wp_redirect( html_entity_decode( get_edit_post_link( $post->get_sibling_post( $lang_id, true )->get_post_id() ) ) );
				return false;
			}
			///MAKE ALTER LANG
			$new_term_id = languages::do_make_sibling_post( $post->ID, $lang_id );
			if( is_int( $new_term_id ) ){
				///REDIRECT
				wp_redirect( html_entity_decode( get_edit_post_link( $new_term_id ) ) );
				return true;
			} else {
				console_warn( 'Не удалось создать копию статьи/страницы' );
			}
		}
		if( languages::is_post_type_allowed( $current_screen->post_type ) && $current_screen->base == 'edit-tags' ){
			if( !array_key_exists( languages::$post_create_sibling_get_key_id, $_GET ) ){
				return false;
			}
			$lang_term = languages::get_term( $_GET[ languages::$post_create_sibling_get_key_id ] );
			if( !$lang_term->is_exists() ){
				console_warn( 'Попытка создать локализированную версию несуществующего термина' );
				return false;
			}
			//Check is lang exists
			$lang_id = $_GET[ languages::$post_create_sibling_get_key_lang_id ];
			if( !languages::is_exists( $lang_id ) ){
				console_warn( 'Попытка создать локализированную версию термина в неизвестной локалии' );
				return false;
			}
			///Redirect if term exists
			if( $lang_term->get_sibling_term( $lang_id )->is_exists() ){
				wp_redirect( html_entity_decode( get_edit_term_link( $lang_term->get_sibling_term( $lang_id )->get_term_id() ) ) );
				return false;
			}
			///MAKE ALTER LANG
			$new_term_id = $lang_term->do_make_sibling( $lang_id );
			if( is_int( $new_term_id ) ){
				///REDIRECT
				wp_redirect( html_entity_decode( get_edit_term_link( $new_term_id ) ) );
				return true;
			} else {
				console_warn( 'Не удалось создать копию термина' );
			}
		}
		return false;
	} );

	add_action( 'edited_term', function( $term_id, $tt_id, $taxonomy ){
		if( languages::is_taxonomy_allowed( $taxonomy ) ){
			$lang_meta_key = languages::$post_meta_key_lang_id;
			if( array_key_exists( $lang_meta_key, $_POST ) ){
				update_term_meta( $term_id, $lang_meta_key, $_POST[ $lang_meta_key ] );
			}
		}
	}, 10, 3 );


	//	add_filter( 'the_title', function( $title, $id ){
	//		if( \hiweb\context::is_frontend_page() && languages::get_default_id() !== languages::get_current_id() ){
	//			$wp_post = get_post( $id );
	//			$title_lang = languages::get_current_language()->get_field( 'post_title', $wp_post );
	//			return $title_lang == '' ? $title : $title_lang;
	//		}
	//		return $title;
	//	}, 10, 2 );

	//	add_filter( 'the_content', function( $content ){
	//		if( languages::get_default_id() !== languages::get_current_id() ){
	//			return languages::get_current_language()->get_field('post_content');
	//		}
	//		return $content;
	//	}, 1 );

	//apply_filters( 'wp_get_nav_menus', get_terms( 'nav_menu',  $args), $args )
	//	add_filter( 'wp_get_nav_menu_items', function( $items, $menu, $args ){
	//		if( \hiweb\context::is_frontend_page() && languages::get_default_id() != languages::get_current_id() ){
	//			foreach( $items as $index => $item ){
	//				$R = '';
	//				switch( $item->object ){
	//					case 'category':
	//						$R = languages::get_current_language()->get_field( 'name', get_term( $item->object_id ) );
	//						break;
	//					case 'page':
	//						$R = languages::get_current_language()->get_field( 'name', get_post( $item->object_id ) );
	//						break;
	//				}
	//				if( $R != '' ){
	//					$items[ $index ]->title = $R;
	//				}
	//			}
	//		}
	//		return $items;
	//	}, 10, 3 );

	//	add_filter( 'get_terms', function( $terms, $taxonomy_name, $query_vars, $terms_query ){
	//		if( \hiweb\context::is_frontend_page() && languages::get_default_id() != languages::get_current_id() ){
	//			/**
	//			 * @var int     $index
	//			 * @var WP_Term $wp_term
	//			 */
	//			foreach( $terms as $index => $wp_term ){
	//				$R = languages::get_current_language()->get_field( 'name', $wp_term );
	//				if( $R != '' ){
	//					$wp_term->name = $R;
	//				}
	//			}
	//		}
	//		return $terms;
	//	}, 10, 4 );

	//	add_filter( 'get_comments_number', function( $count, $post_id ){
	//		if( \hiweb\context::is_frontend_page() ){
	//			$comments_query = [
	//				'post_id' => $post_id,
	//				'meta_key' => 'language_id',
	//				'meta_value' => languages::get_current_id()
	//			];
	//			$comments = get_comments( $comments_query );
	//			return count( $comments );
	//		}
	//		return $count;
	//	}, 10, 2 );