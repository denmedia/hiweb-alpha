<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 22:15
	 */

	namespace hiweb_theme\modules;


	class bootstrap{

		private static $cols_to_class_simple = [
			'1.1' => 'col-12',
			'1.2' => 'col-lg-6',
			'1.3' => 'col-lg-4',
			'1.4' => 'col-md-3',
			'1.6' => 'col-md-2',
			'1.12' => 'col-md-1',
			'2.2' => 'col-6',
			'2.3' => 'col-md-6 col-lg-4',
			'2.4' => 'col-md-6 col-lg-3',
			'2.6' => 'col-md-6 col-lg-2',
			'2.12' => 'col-md-6 col-lg-1',
			'3.3' => 'col-4',
			'3.4' => 'col-sm-4 col-lg-3',
			'3.6' => 'col-md-4 col-lg-2',
			'3.12' => 'col-md-4 col-lg-1',
			'4.4' => 'col-3',
			'4.6' => 'col-sm-3 col-lg-2',
			'4.12' => 'col-sm-3 col-lg-1',
			'6.6' => 'col-2',
			'6.12' => 'col-sm-2 col-lg-1',
			'12.12' => 'col-1'
		];


		/**
		 * @param int $xl
		 * @param int $lg
		 * @param int $md
		 * @param int $sm
		 * @param int $xs
		 * @return string
		 */
		static function cols_to_class( $xl = 6, $lg = 4, $md = 3, $sm = 2, $xs = 1 ){
			$cols_data = get_defined_vars();
			$classes = [];
			foreach( $cols_data as $name => $cols ){
				if( is_numeric( $cols ) ){
					$classes[] = 'col-' . $name . '-' . round( 12 / $cols );
				}
			}
			return implode( ' ', $classes );
		}


		/**
		 * @param int $full
		 * @param int $small
		 * @return mixed
		 */
		static function cols_to_class_simple( $full = 4, $small = 2 ){
			return self::$cols_to_class_simple[ $small . '.' . $full ];
		}

	}