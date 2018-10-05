<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 21:00
	 */

	namespace hiweb_theme;


	class body{

		/** @var array */
		static $body_classes = [];
		/** @var bool */
		static $use_body_wp_class = true;


		static function the_classes(){
			if( self::$use_body_wp_class ) body_class( self::$body_classes ); else echo implode( ' ', self::$body_classes );
		}

	}