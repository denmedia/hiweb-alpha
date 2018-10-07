<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06.10.2018
	 * Time: 12:40
	 */

	namespace hiweb_theme\modules;


	use hiweb\strings;
	use hiweb_theme\modules\slider\slider;


	class sliders{

		static $sliders = [];


		/**
		 * @param null $id
		 * @return slider
		 */
		static function slider( $id = null ){
			if( is_null( $id ) ) $id = strings::rand();
			if( !array_key_exists( $id, self::$sliders ) ){
				self::$sliders[ $id ] = new slider( $id );
			}
			return self::$sliders[$id];
		}


	}