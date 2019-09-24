<?php

	namespace theme\widgets;


	use theme\includes\frontend;


	class menu_collapse{

		static $defer_include_scripts;


		static function init( $defer_include_scripts = true ){
			static $init = false;
			self::$defer_include_scripts = $defer_include_scripts;
			if( !$init ){
				if( !$defer_include_scripts ){
					frontend::css( __DIR__ . '/style.css' );
					frontend::js( __DIR__ . '/app.js', frontend::jquery() );
				}
				require_once __DIR__ . '/widget.php';
			}
		}

	}