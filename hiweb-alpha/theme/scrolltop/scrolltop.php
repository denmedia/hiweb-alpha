<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 09.10.2018
	 * Time: 13:24
	 */

	namespace theme;


	use hiweb\components\Includes\IncludesFactory_FrontendPage;
	use theme\includes\frontend;


	class scrolltop{

		static protected $classes = [ 'hiweb-theme-widget-scrolltop' ];
		static $admin_menu_slug = 'hiweb-scrolltop';
		static $admin_menu_parent = 'themes.php';


		static function init(){
			require_once __DIR__.'/options.php';
			add_action( '\theme\html_layout\body::the_after-before', '\theme\scrolltop::the' );
		}


		/**
		 * @param string $class
		 */
		static function add_class( $class ){
			self::$classes[] = $class;
		}


		/**
		 * @return string
		 */
		static function get_class(){
			return implode( ' ', self::$classes );
		}


		static function the(){
			include_frontend_css( __DIR__ . '/scrolltop.css' );
			include_frontend_js( __DIR__ . '/scrolltop.min.js', IncludesFactory_FrontendPage::jquery() );
			get_template_part( HIWEB_THEME_PARTS . '/scrolltop' );
		}

	}