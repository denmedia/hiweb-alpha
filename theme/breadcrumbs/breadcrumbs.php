<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 10:57
	 */

	namespace theme;


	use theme\breadcrumbs\crumb;
	use theme\includes\frontend;


	class breadcrumbs{

		static $admin_options_slug = 'breadcrumbs';

		protected static $queried_object;
		//protected static $current_nav_menu_location;
		protected static $crumbs;
		protected static $crumbs_limit = 10;
		static $class = '';


		static function init(){
			require_once __DIR__ . '/options.php';
		}


		static function the(){
			self::init();
			frontend::fontawesome();
			self::$queried_object = get_queried_object();
			get_template_part( HIWEB_THEME_PARTS . '/widgets/breadcrumbs/wrap-prefix' );
			//items
			foreach( self::get_crumbs() as $index => $crumb ){
				$crumb->the();
				if( ( get_field( 'separator-enable', self::$admin_options_slug ) && ($index + 1) < count( self::get_crumbs() ) ) || get_field( 'separator-last-enable', self::$admin_options_slug ) ){
					echo self::get_the_separator();
				}
			}
			//
			get_template_part( HIWEB_THEME_PARTS . '/widgets/breadcrumbs/wrap-sufix' );
		}


		/**
		 * @return string
		 */
		static function get_the_separator(){
			ob_start();
			get_template_part( HIWEB_THEME_PARTS . '/widgets/breadcrumbs/item-separator' );
			return strtr( ob_get_clean(), [ '{icon}' => get_field( 'separator-icon', self::$admin_options_slug ) ] );
		}


		/**
		 * @return string
		 */
		static function get_the(){
			ob_start();
			self::the();
			return ob_get_clean();
		}


		/**
		 * @return crumb[]
		 */
		static function get_crumbs(){
			if( !is_array( self::$crumbs ) ){
				self::$crumbs = [];
				$current_crumb = new crumb();
				if( get_field( 'current-enable', self::$admin_options_slug ) ) self::$crumbs[] = $current_crumb;
				///
				$limit = self::$crumbs_limit;
				while( $limit > 0 && $current_crumb->get_parent_crumb() !== false ){
					$current_crumb = $current_crumb->get_parent_crumb();
					if( !get_field( 'home-enable', self::$admin_options_slug ) && $current_crumb->get_parent_crumb() === false ) break;
					self::$crumbs[] = $current_crumb;
					$limit --;
				}
				///
				self::$crumbs = array_reverse( self::$crumbs );
			}
			if( count( self::$crumbs ) < 2 ) return [];
			return self::$crumbs;
		}

	}