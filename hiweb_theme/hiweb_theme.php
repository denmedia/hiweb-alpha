<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 11:34
	 */


	class hiweb_theme{

		/** @var \hiweb_theme\modules\nav_menu[] */
		private static $nav_menus = [];
		/** @var array \hiweb_theme\modules\mmenu[] */
		private static $mmenus = [];


		/**
		 * @param string $nav_location
		 * @return \hiweb_theme\modules\nav_menu
		 */
		static function nav_menu( $nav_location = 'header' ){
			if( !array_key_exists( $nav_location, self::$nav_menus ) ){
				self::$nav_menus[ $nav_location ] = new \hiweb_theme\modules\nav_menu( $nav_location );
			}
			return self::$nav_menus[ $nav_location ];
		}


		/**
		 * @param string $nav_location
		 * @return \hiweb_theme\modules\mmenu
		 */
		static function mmenus( $nav_location = 'mobile-menu' ){
			if( !array_key_exists( $nav_location, self::$mmenus ) ){
				$mmenu = new \hiweb_theme\modules\mmenu( $nav_location );
				$mmenu->hooks();
				self::$mmenus[ $nav_location ] = $mmenu;
			}
			return self::$mmenus[ $nav_location ];
		}

	}