<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 20:58
	 */

	namespace hiweb_theme;


	class footer{

		static $use_wp_footer = true;
		static $layout = 'default';


		static function the(){
			get_template_part( HIWEB_THEME_PARTS . '/footer/layout', self::$layout );
			if( self::$use_wp_footer ) wp_footer();
		}

	}