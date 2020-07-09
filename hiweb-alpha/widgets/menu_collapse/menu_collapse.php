<?php

	namespace theme\widgets;


	use theme\includes\frontend;


	class menu_collapse{

		static $defer_include_scripts;
		static $options_handle = 'hiweb-menu-collapse';


		static function init( $defer_include_scripts = true ){
			static $init = false;
			self::$defer_include_scripts = $defer_include_scripts;
			if( !$init ){
				$init = true;
				if( !$defer_include_scripts ){
					include_frontend_css( __DIR__ . '/style.css' );
					include_frontend_js( __DIR__ . '/app.min.js', 'jquery' );
				}
				require_once __DIR__ . '/options.php';
				require_once __DIR__ . '/widget.php';
			}
		}

	}