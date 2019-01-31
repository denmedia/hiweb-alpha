<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 11:33
	 */

	namespace theme\tools;


	use hiweb\paths;
	use hiweb\urls;
	use hiweb_theme\includes;
	use hiweb_theme\tools\pagesCache\cache_item;
	use hiweb_theme\tools\pagesCache\indexInject;
	use hiweb_theme\tools\pagesCache\options;
	use hiweb_theme\tools\pagesCache\queue;
	use hiweb_theme\tools\pagesCache\tools;


	class pagesCache{

		private static $is_init = false;
		static $_admin_menu_slug = 'hiweb-theme-pages-cache';
		static private $force_stop_make_cache = false;
		static private $force_stop_make_cache_message = '';

		static private $caches = [];


		static function init(){
			require_once __DIR__ . '/pagesCache/options.php';
			require_once __DIR__ . '/pagesCache/indexInject.php';
			require_once __DIR__ . '/pagesCache/tools.php';
			require_once __DIR__ . '/pagesCache/rest.php';
			require_once __DIR__ . '/pagesCache/queue.php';
			require_once __DIR__ . '/pagesCache/hooks.php';
			require_once __DIR__ . '/pagesCache/cache_item.php';
			includes::js( HIWEB_THEME_ASSETS_DIR . '/js/tool-pagesCache_direct.js', includes::jquery() );
			if( !self::$is_init ){
				self::$is_init = true;
				include_once __DIR__ . '/pagesCache/admin-menu.php';
				indexInject::do_index_injection();
				queue::init();
				if( options::is_allow_url( tools::get_request_uri() ) && !self::get_cache( tools::get_request_uri() )->is_exists() ){
					queue::add_url( tools::get_request_uri() );
				}
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$is_init;
		}


		/**
		 * @return bool
		 */
		static function is_enable(){
			return options::is_enable();
		}


		/**
		 * @param string $message
		 */
		static function force_stop_make_cache( $message = '' ){
			self::$force_stop_make_cache = true;
			self::get_cache()->force_disable();
			if( $message != '' )
				console_info( addslashes( __METHOD__ ) . ' - pages cache is disabled: ' . $message );
		}


		/**
		 * Turn off cache
		 */
		static function disable(){
			options::set( 'enable', '', true );
		}


		/**
		 * Turn on cache
		 */
		static function enable(){
			options::set( 'enable', 'on', true );
		}


		/**
		 * @return string
		 */
		static function get_cache_dir(){
			return tools::base_dir() . '/wp-content/cache/hiweb-theme-pages-cache';
		}


		/**
		 * Clear all cached files.
		 * If you wont clear caches and queue urls, use 'flush' function
		 */
		static function clear(){
			$R = [];
			foreach( paths::get( self::get_cache_dir() )->get_sub_files() as $path => $file ){
				$R[ $path ] = @unlink( $path );
			}
			return $R;
		}


		/**
		 * Flush all caches and queue urls
		 * @return array
		 */
		static function flush(){
			$R = self::clear();
			queue::flush();
			return $R;
		}


		/**
		 * Get cache item by url/uri
		 * @param $url
		 * @return cache_item
		 */
		static function get_cache( $url = null ){
			require_once __DIR__ . '/pagesCache/tools.php';
			if( !is_string( $url ) )
				$url = tools::get_request_uri();
			$url = tools::filter_url( $url );
			if( !array_key_exists( $url, self::$caches ) ){
				require_once __DIR__ . '/pagesCache/cache_item.php';
				self::$caches[ $url ] = new cache_item( $url );
			}
			return self::$caches[ $url ];
		}


		static function make_cache( $url, $ignore_expires = true ){
			return self::get_cache( $url )->make( $ignore_expires );
		}


		static function add_to_queue( $url, $priority = 5 ){
			return queue::add_url( $url, $priority );
		}


		/**
		 * Add to queue relatives posts and terms
		 * @param     $postOrTerm
		 * @param int $hight_priority - set hight level priority, like 3 or 8...
		 */
		static function add_to_queue_relatives( $postOrTerm, $hight_priority = 10 ){
			if( $postOrTerm instanceof \WP_Post ){
				pagesCache::add_to_queue( get_post_type_archive_link( $postOrTerm->post_type ), floor( $hight_priority * .8 ) );
				pagesCache::add_to_queue( '/', floor( $hight_priority * .9 ) );
				if( intval( get_option( 'page_for_posts' ) ) != 0 )
					pagesCache::add_to_queue( get_permalink( get_option( 'page_for_posts' ) ), floor( $hight_priority * .8 ) );
				foreach( get_object_taxonomies( $postOrTerm ) as $taxonomy ){
					$terms = get_the_terms( $postOrTerm, $taxonomy );
					if( is_array( $terms ) )
						foreach( $terms as $wp_term ){
							pagesCache::get_cache( get_term_link( $wp_term ) )->flush();
							pagesCache::add_to_queue( get_term_link( $wp_term ), floor( $hight_priority * .7 ) );
						}
				}
			} elseif( $postOrTerm instanceof \WP_Term ) {
				foreach(
					get_posts( [
						'post_type' => 'any',
						'tax_query' => [
							[
								'taxonomy' => $postOrTerm->taxonomy,
								'field' => 'term_id',
								'terms' => $postOrTerm->term_id
							]
						],
						'posts_per_page' => 20
					] ) as $wp_post
				){
					pagesCache::add_to_queue( get_permalink( $wp_post ), 7 );
				}
			}
		}


	}