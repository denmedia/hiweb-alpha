<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 20:12
	 */

	namespace hiweb_theme;


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
		 * @return string
		 */
		public function __toString(){
			return self::get();
		}


	}