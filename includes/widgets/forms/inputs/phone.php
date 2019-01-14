<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:51
	 */

	namespace hiweb_theme\widgets\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class phone extends input{

		static $default_name = 'phone';
		static $input_title = 'Номер телефона';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Номер телефона' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'placeholder' )->placeholder( 'Плейсхолдер в поле' ) )->label( 'Плейсхолдер в поле' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'mask-use' )->label_checkbox( 'Использовать маску' ) )->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'mask' )->placeholder( '+7(999)999-99-99' )->VALUE( '+7(999)999-99-99' )->get_parent_field() )->label('Макска для ввода телефона')->compact(1);
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) );
		}

		public function the(){
			$this->the_prefix();
			?>
			<div class="input"><input type="text" tabindex="" <?=$this->get_tag_pair('data-input-mask','mask')?> name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?>/></div>
			<?php
			$this->the_sufix();
		}


	}