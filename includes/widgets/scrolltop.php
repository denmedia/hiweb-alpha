<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 09.10.2018
	 * Time: 13:24
	 */

	namespace hiweb_theme\widgets;


	use hiweb_theme\includes;


	class scrolltop{

		static protected $classes = [ 'hiweb-theme-widget-scrolltop' ];
		static protected $icon_class = 'far fa-arrow-to-top';
		static $tag_target = 'body';
		static $scroll_offset = 200;


		static function init(){
			includes::css( HIWEB_THEME_ASSETS_DIR . '/css/widget-scrolltop.min.css' );
			add_action( 'hiweb_theme_body_sufix_before', 'hiweb_theme\widgets\scrolltop::the' );
		}


		/**
		 * @param string $class
		 */
		static function set_icon_class( $class = 'far fa-arrow-to-top' ){
			self::$icon_class = $class;
		}


		/**
		 * @return string
		 */
		static function get_icon_class(){
			return self::$icon_class;
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
			includes::defer_script_file( 'scrolltop' );
			get_template_part( HIWEB_THEME_PARTS . '/scrolltop' );
		}

	}