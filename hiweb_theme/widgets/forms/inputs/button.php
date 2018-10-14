<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 19:02
	 */

	namespace hiweb_theme\widgets\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class button extends input{

		static $input_title = 'Кнопка';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Отправить' )->VALUE( 'Отправить' )->get_parent_field() )->label( 'Кнопка с текстом' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_select( 'type' )->options( [ 'submit' => 'Отправка формы', 'reset' => 'Сброс формы' ] ) )->compact( 1 );
		}

		public function the_prefix(){
			?>
			<div class="input">
			<?php
		}


		public function the(){
			?>
			<button type="<?= self::get_data( 'type' ) ?>" <?= self::get_data( 'type' ) == 'submit' ? 'disabled' : '' ?>><?= self::get_data( 'label' ) ?></button>
			<?php
		}


	}