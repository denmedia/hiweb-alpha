<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08/12/2018
	 * Time: 17:45
	 */

	namespace theme\pages_cache;


	use hiweb\arrays;
	use hiweb\cron;
	use hiweb\dump;
	use hiweb\urls;
	use hiweb_theme\pagesCache;


	class queue{

		/** @var array */
		static private $urls;
		static private $option_key = 'hiweb_theme-pages_cache-queue';
		/** @var array|null */
		static private $option_raw_data;
		static private $urls_change = false;
		static private $is_init = false;
		static private $rest_url = 'hiweb_theme/pages_cache/queue_next';


		static function init(){
			if( !self::$is_init ){
				self::$is_init = true;
				self::$option_raw_data = get_option( self::$option_key, null );

				if( !is_array( self::$option_raw_data ) ){
					self::$urls = [ '/' => 10 ];
					self::init_first();
				} else {
					self::$urls = self::$option_raw_data;
				}

				add_action( 'shutdown', '\\hiweb_theme\\tools\\pagesCache\\queue::_update' );

				if( !wp_next_scheduled( 'hiweb_theme_pagesCache_queue' ) )
					wp_schedule_event( time(), '1min', 'hiweb_theme_pagesCache_queue' );
			}
		}


		/**
		 * Return url for addint them to cron
		 * @return string
		 */
		static function get_cron_url(){
			return urls::root( false ) . '/wp-cron.php';
		}


		/**
		 * Return cron string for job, add this string to you'r hosting cron queue
		 * @return string
		 */
		static function get_cron_string(){
			return cron::to_string( self::get_cron_url(), '*', '*', '*', '*', '*' );
		}


		/**
		 * @return bool
		 */
		static function is_cron_exists(){
			return cron::job_exists( self::get_cron_string() );
		}

		/**
		 * @return bool|string
		 */
		static function get_cron(){
			return cron::add_url( self::get_cron_url(), '*', '*', '*', '*', '*' );
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$is_init;
		}


		static private function init_first(){
			add_action( 'init', function(){
				///NAV MENUS
				$nav_menu_ids = array_values( get_nav_menu_locations() );
				array_unique( $nav_menu_ids );
				foreach( $nav_menu_ids as $nav_menu_id ){
					$menu_items = wp_get_nav_menu_items( $nav_menu_id );
					foreach( $menu_items as $menu_item ){
						if( $menu_item->type == 'taxonomy' ){
							self::add_url( get_term_link( (int)$menu_item->object_id ), 8 );
							pagesCache::add_to_queue_relatives( get_term( $menu_item->object_id ), 5 );
						} elseif( $menu_item->type == 'post' ) {
							self::add_url( get_permalink( (int)$menu_item->object_id ), 8 );
							pagesCache::add_to_queue_relatives( get_post( $menu_item->object_id ), 5 );
						}
					}
				}
				///CATEGORIES
				/** @var \WP_Taxonomy $taxonomy */
				foreach( get_taxonomies( [], false ) as $taxonomy ){
					if( !$taxonomy->public || !$taxonomy->publicly_queryable )
						continue;
					foreach( get_terms( [ 'taxonomy' => $taxonomy->name ] ) as $wp_term ){
						self::add_url( get_term_link( $wp_term ), 7 );
					}
				}
				///POSTS
				/** @var \WP_Post_Type $post_type */
				foreach( get_post_types( [], false ) as $post_type ){
					if( !$post_type->public || !$post_type->publicly_queryable )
						continue;
					if( arrays::get( [ 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'user_request' ] )->has_value( $post_type->name ) )
						continue;
					if( $post_type->has_archive )
						self::add_url( get_post_type_archive_link( $post_type->name ), 3 );
					foreach( get_posts( [ 'post_type' => $post_type->name, 'posts_per_page' => 40 ] ) as $wp_post ){
						self::add_url( get_permalink( $wp_post ) );
					}
				}
			} );
		}


		/**
		 * @param     $url
		 * @param int $priority
		 * @return array|bool
		 */
		static function add_url( $url, $priority = 5 ){
			$url = tools::filter_url( $url );
			if( !options::is_allow_url( $url ) )
				return false;
			if( !array_key_exists( $url, self::$urls ) ){
				//Занести новый URL в очередь
				self::$urls_change = true;
				self::$urls[ $url ] = $priority < 0 ? 0 : $priority;
			} elseif( self::$urls[ $url ] < $priority && $priority >= 0 ) {
				//Если URL есть, то поменять его приоритет в большую сторону
				self::$urls_change = true;
				self::$urls[ $url ] = $priority;
			} elseif( $priority < 0 ) {
				//Перенести URL в конец очереди и установить приоритет 0. Данная процедура необходима после созранения кэша
				unset( self::$urls[ $url ] );
				self::$urls_change = true;
				self::$urls = array_merge( [ $url => 0 ], self::$urls );
			}
			return self::$urls;
		}


		/**
		 * @param $url
		 * @return array
		 */
		static function remove_url( $url ){
			if( array_key_exists( $url, self::$urls ) ){
				self::$urls_change = true;
				unset( self::$urls[ $url ] );
			}
			return self::$urls;
		}


		/**
		 * @param bool $high_priority
		 * @param bool $move_getting_url_to_back
		 * @return bool|string
		 */
		static function get_url( $high_priority = true, $move_getting_url_to_back = false ){
			self::init();
			if( !is_array( self::$urls ) || count( self::$urls ) == 0 )
				return false;
			///
			asort( self::$urls, SORT_NUMERIC );
			$urls = array_keys( self::$urls );
			$R = $high_priority ? array_pop( $urls ) : array_shift( $urls );
			if( $high_priority && $move_getting_url_to_back ){
				self::$urls_change = true;
				unset( self::$urls[ $R ] );
				self::$urls = array_merge( [ $R => 0 ], self::$urls );
			}
			self::_update();
			return $R;
		}


		/**
		 * @return array
		 */
		static function get_urls(){
			self::init();
			return self::$urls;
		}


		/**
		 * Flush queue urls
		 * @return bool
		 */
		static function flush(){
			return delete_option( self::$option_key );
		}


		/**
		 * Flush queue urls
		 * @alias self::flush()
		 * @return bool
		 */
		static function clear(){
			$B = self::flush();
			self::init_first();
			return $B;
		}


		/**
		 * Сохранить (обновить) список URL в БД
		 */
		static function _update(){
			$B = false;
			if( self::$urls_change && options::is_enable() ){
				$B = update_option( self::$option_key, self::$urls );
			}
			return $B;
		}


		/**
		 * Создать кэш следующего URL в очереди
		 * @param int $limit - лимит на количество попыток повторного получения URL
		 * @return array|bool
		 */
		static function next( $limit = 99 ){
			if( !pagesCache::is_init() && !options::is_enable() && $limit < 1 )
				return false;
			if( $limit > count( self::get_urls() ) )
				$limit = count( self::get_urls() );
			///
			self::init();
			///
			$next_url = self::get_url();
			self::add_url( $next_url, - 1 );
			///
			if( is_string( $next_url ) ){
				if( pagesCache::get_cache( $next_url )->make() !== false ){
					return $next_url;
				} else {
					return self::next( $limit - 1 );
				}
			}
			return false;
		}


	}