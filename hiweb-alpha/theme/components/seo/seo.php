<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:18
	 */
	
	namespace theme;
	
	
	use hiweb\components\AdminMenu\AdminMenu_Page;
	
	
	class seo{
		
		private static $init = false;
		/**
		 * @deprecated
		 * @var bool
		 */
		static $option_force_redirect_slash_end = false;
		static $option_page_permalink_force_slash_end = false;
		static $option_term_permalink_force_slash_end = false;
		static $option_use_paginate_canonical = true;
		
		static $admin_menu_main = 'hiweb-seo-main';
		static $admin_menu_main_parent = 'options-general.php';
		/** @var AdminMenu_Page */
		static $admin_menu_main_page;
		
		
		static function init(){
			if( !self::$init ){
				self::$init = true;
				require_once __DIR__ . '/options.php';
				require_once __DIR__ . '/hooks.php';
				require_once __DIR__ . '/post-type-meta.php';
				require_once __DIR__ . '/authors.php';
				require_once __DIR__ . '/yoast.php';
				require_once __DIR__ . '/global_functions.php';
			}
		}
		
		
		/**
		 * @param string|\WP_Post_Type $post_type
		 * @return bool
		 */
		static function post_type_has_settings( $post_type ){
			$post_type = $post_type instanceof \WP_Post_Type ? $post_type : get_post_type_object( $post_type );
			if( !$post_type instanceof \WP_Post_Type ) return false;
			return $post_type->public && ( $post_type->name == 'page' || $post_type->name == 'post' || $post_type->publicly_queryable );
		}
		
		
		static function post_type_is_enable( $post_type ){
			$post_type = $post_type instanceof \WP_Post_Type ? $post_type : get_post_type_object( $post_type );
			if( !$post_type instanceof \WP_Post_Type ) return false;
			if( !self::post_type_has_settings( $post_type ) ) return false;
			$R = get_option( 'hiweb-option-' . \theme\seo::$admin_menu_main . '-enable-' . $post_type->name );
			return $R;
		}
		
		
		static function post_type_is_enable_customH1( $post_type ){
			$post_type = $post_type instanceof \WP_Post_Type ? $post_type : get_post_type_object( $post_type );
			if( !$post_type instanceof \WP_Post_Type ) return false;
			if( !self::post_type_has_settings( $post_type ) ) return false;
			$R = get_option( 'hiweb-option-' . \theme\seo::$admin_menu_main . '-enable-custom-h1-' . $post_type->name );
			return $R;
		}
		
		
		static function post_type_is_enable_customLoopTitle( $post_type ){
			$post_type = $post_type instanceof \WP_Post_Type ? $post_type : get_post_type_object( $post_type );
			if( !$post_type instanceof \WP_Post_Type ) return false;
			if( !self::post_type_has_settings( $post_type ) ) return false;
			$R = get_option( 'hiweb-option-' . \theme\seo::$admin_menu_main . '-enable-custom-loop-title-' . $post_type->name );
			return $R;
		}
		
		
		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}
		
		
		/**
		 * @param string $post_type
		 * @return mixed|null
		 */
		static function get_post_type_title( $post_type = 'post' ){
			$post_type = get_post_type_object( $post_type );
			if( $post_type->has_archive ){
				return (bool)get_option( 'hiweb-option-' . self::$admin_menu_main . '-archive-title-' . $post_type->name );
			}
			return null;
		}
		
		
		/**
		 * @return mixed
		 */
		static function is_author_enable(){
			return (bool)get_option( 'hiweb-option-' . self::$admin_menu_main . '-authors-enable' );
		}
		
		
		/**
		 * @return mixed
		 */
		static function is_paged_append_enable(){
			return (bool)get_option( 'hiweb-option-' . self::$admin_menu_main . '-paged-append-enable' );
		}
		
		
	}