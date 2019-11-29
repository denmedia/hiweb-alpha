<?php

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class privacy extends input{

		static $default_name = 'hiweb-forms-privacy-police-check';
		static $input_title = 'Галочка политики конфидициальности';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_separator( 'Галочка политики конфидициальности', 'Настройки данной галочки находятся на странице опций' ) )->label( '' )->description( '' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( get_option( 'hiweb-option-hiweb-forms-privacy-checkbox-text' ) ) )->label( 'Текст напротив галочки согласия политики конфидициальности:' )->description( 'Оставьте это поле пусты, в таком случае будет взят стандартный текст из опций' );
		}


		/**
		 * @return bool
		 */
		public function is_email_submit_enable(){
			return false;
		}


		/**
		 * @return bool
		 */
		public function is_required(){
			return true;
		}


		/**
		 * @return mixed|string|null
		 */
		public function get_name(){
			return self::$default_name;
		}


		public function get_require_error_message(){
			console_info( get_field( 'privacy-checkbox-error-text', 'hiweb-forms' ) );
			return get_field( 'privacy-checkbox-error-text', 'hiweb-forms' );
		}


		/**
		 * @return string
		 */
		public function get_require_error_message_html(){
			if( self::is_required() && self::get_require_error_message() ){
				return '<div class="require-error-message">' . $this->get_require_error_message() . '</div>';
			}
			return '';
		}


		public function the(){
			$this->the_prefix();
			$text = get_field( 'privacy-checkbox-text', 'hiweb-forms' );
			if( self::get_data( 'label' ) != '' ) $text = self::get_data( 'label', false );
			if( strpos( $text, '{' ) !== false && strpos( $text, '}' ) !== false ) $text = strtr( $text, [ '{' => '<a href="' . get_privacy_policy_url() . '" target="_blank">', '}' => '</a>' ] );
			if( $text != '' ){
				?>
				<label class="label">
				<?php
			}
			$checked = get_field( 'privacy-checkbox-default', 'hiweb-forms' ) != '';
			?>
			<input tabindex="" type="checkbox" <?= $checked ? 'checked="checked"' : '' ?> name="<?= self::$default_name ?>" <?= self::get_tag_pair( 'placeholder' ) ?> <?= self::is_required() ? 'data-required' : '' ?>/>
			<?php

			if( $text != '' ){
				?><?= $text ?> <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></label><?php
			}
			$this->the_sufix();
		}


	}