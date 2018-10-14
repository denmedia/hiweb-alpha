<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:23
	 */

	namespace hiweb_theme\widgets\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class email extends input{

		static $default_name = 'email';
		static $input_title = 'Адрес почты';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'email' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) );
		}

		public function the(){
			$this->the_prefix();
			?>
			<div class="input"><input type="email" name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?>/></div>
			<?php
			$this->the_sufix();
		}


	}