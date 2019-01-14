<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 20:58
	 */

	namespace hiweb_theme;


	use hiweb_theme\widgets\bootstrap\wrap;


	class footer{

		static $use_wp_footer = true;
		static $layout = 'default';
		static protected $wrap;


		static function the(){
			get_template_part( HIWEB_THEME_PARTS . '/footer/layout', self::$layout );
			if( self::$use_wp_footer ) wp_footer();
		}


		/**
		 * @return wrap
		 */
		static function wrap(){
			if( !self::$wrap instanceof wrap ){
				self::$wrap = new wrap( 'footer' );
			}
			return self::$wrap;
		}

	}