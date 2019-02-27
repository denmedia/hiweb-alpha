<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:06
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class html_insert extends input{

		static $default_name = 'html_insert';
		static $input_title = 'HTML вставка в форму';

		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_textarea( 'html' )->placeholder( 'HTML код' ) )->label( 'HTML вставка в форму' )->compact( 1 );
		}


		public function the(){
			echo self::get_data('html', false);
		}


	}