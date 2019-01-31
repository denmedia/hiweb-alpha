<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 16.10.2018
	 * Time: 19:03
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class checkbox extends input{

		static $input_title = 'Чекбокс';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Опция' )->VALUE( 'Опция' )->get_parent_field() )->label( 'Чекбокс (галочка)' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' ) )->label( 'Имя поля на латинице' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_checkbox( 'require' )->label_checkbox( 'Обязательно для заполнения' ) );
		}

		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>">
			<?php if( self::get_data( 'label' ) != '' ){
				?>

				<?php
			} elseif( self::is_required_empty_label() ) {
				?>
				<div class="required-empty-label">
				<?php
			}
		}

		public function the(){
			$this->the_prefix();
			if( self::get_data( 'label' ) != '' ){
				?>
				<label class="label">
				<?php
			}
			?>
			<input tabindex="" type="checkbox" name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?>/>
			<?php
			if( self::get_data( 'label' ) != '' ){
				?>
				<?= self::get_data( 'label' ) ?> <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></label>
				<?php
			}
			$this->the_sufix();
		}

		public function the_sufix(){
			?>
			</div>
			<?php
			if( self::is_required_empty_label() ){
				?>
				</div>
				<?php
			}
		}


	}