<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 27/10/2018
	 * Time: 23:04
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class json extends input{

		static $default_name = 'json';
		static $input_title = 'Данные JSON';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Текстовое поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' )->VALUE( self::$default_name )->get_parent_field() )->label( 'Имя поля на латинице' );
		}


		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>"><?php
		}


		public function the(){
			$this->the_prefix();
			?><input tabindex="" type="hidden" name="<?= self::get_name() ?>"/>
			<?php
			$this->the_sufix();
		}


		public function the_sufix(){
			?>
			</div>
			<?php
		}


		public function get_email_value( $value ){
			$value_array = json_decode( stripslashes($value), true );
			if( json_last_error() === JSON_ERROR_NONE ){
				return apply_filters( 'hiweb-theme-widgets-form-input-json-'.$this->get_name().'-email-value', $value_array, $this );
			} else {
				return 'Ошибка в данных JSON <code>' . $value . '</code>';
			}
		}


	}