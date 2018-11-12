<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 20:12
	 */

	namespace hiweb_theme;


	/**
	 * Class head отвечает за вывод главного тега HEAD в HTML
	 * @package hiweb_theme
	 */
	class head{

		static $favicon_png_context = [ 'favicon-png', 'header' ];
		static $favicon_ico_context = [ 'favicon-ico', 'header' ];
		static $meta_viewport = 'width=device-width, initial-scale=1';
		/** @var bool Use */
		static $use_wp_head = true;

		static $default_favicon_url = HIWEB_THEME_ASSETS_URL . '/img/favicon.png';

		static $use_emoji = false;

		static $show_meta_generator = false;

		static $show_link_wlwmanifest = false;

		static $show_link_rel_EditURI = false;

		static $show_RSS_links = false;

		static $show_restApi_link = false;

		static $use_wp_embed = false;

		static $code = [];

		static $html_tags = [];


		/**
		 * Print head tag and body prefix
		 */
		static function the(){
			get_template_part( HIWEB_THEME_PARTS . '/head/head' );
			get_template_part( HIWEB_THEME_PARTS . '/body/prefix' );
		}


		/**
		 * @return string
		 */
		static function get(){
			ob_start();
			self::the();
			return ob_get_clean();
		}


		/**
		 * @param $code
		 * @return array
		 */
		static function add_code( $code ){
			self::$code[] = $code;
			return self::$code;
		}


		/**
		 * @return string
		 */
		public function __toString(){
			return self::get();
		}


		/**
		 * @param      $name
		 * @param null $value
		 */
		static function add_html_tag( $name, $value = null ){
			self::$html_tags[ $name ] = $value;
		}


		/**
		 * @param bool $return_array
		 * @return array|string
		 */
		static function get_html_tags( $return_array = false ){
			$R = [];
			if( is_array( self::$html_tags ) ) foreach( self::$html_tags as $name => $value ){
				$R[] = $name . '="' . htmlentities( $value ) . '"';
			}
			return $return_array ? $R : join( ' ', $R );
		}


	}