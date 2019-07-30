<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 22:06
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class input{

		static $default_name = 'input';
		static $input_title = 'Новый инпут';
		public $data = [];


		static function add_repeat_field( field $parent_repeat_field ){

			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_separator( self::$input_title, 'Для данного инпута нет изменяемых данных' ) );
		}


		/**
		 * @param      $key
		 * @param bool $htmlentities
		 * @return mixed|null|string
		 */
		public function get_data( $key, $htmlentities = true ){
			if( array_key_exists( $key, $this->data ) ){
				return $htmlentities ? htmlentities( $this->data[ $key ] ) : $this->data[ $key ];
			}
			return null;
		}


		/**
		 * @return mixed|null|string
		 */
		public function get_name(){
			return is_null( self::get_data( 'name' ) ) ? self::$default_name : self::get_data( 'name' );
		}


		/**
		 * @param             $tag_name
		 * @param null|string $data_name
		 * @param null|mixed  $value
		 * @return string
		 */
		public function get_tag_pair( $tag_name, $data_name = null, $value = null ){
			$tag_value = ( is_null( $value ) ? self::get_data( is_null( $data_name ) ? $tag_name : $data_name ) : htmlentities( $value ) );
			if( is_null( $tag_value ) ) return ''; else return $tag_name . '="' . $tag_value . '"';
		}


		/**
		 * @return bool
		 */
		public function is_required(){
			return $this->get_data( 'require' ) == 'on';
		}


		/**
		 * @return bool
		 */
		public function is_required_empty_label(){
			return $this->get_data( 'label' ) == '' && $this->is_required();
		}


		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>">
			<?php
			if( isset( $this->data['icon'] ) && $this->data['icon'] != '' ){
				?>
				<i class="<?= $this->data['icon'] ?>"></i>
				<?php
			}
			if( self::get_data( 'label' ) != '' ){
				?>
				<label class="label"><?= self::get_data( 'label' ) ?> <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></label>
				<?php
			} elseif( self::is_required_empty_label() ) {
				?>
				<div class="required-empty-label">
				<?php
			}
		}


		public function the(){
			$this->the_prefix();
			?><input tabindex="" type="text" name="<?= self::get_name() ?>" <?= self::get_tag_pair( 'placeholder' ) ?>/>
			<?php
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


		/**
		 * @param $value
		 * @return string
		 */
		public function get_email_html( $value ){
			$label = trim( $this->get_data( 'label' ) );
			if( $label == '' ) $label = $this->get_data( 'placeholder' );
			if( $label == '' ) $label = self::$default_name;
			return '<b>' . $label . ':</b> ' . $this->get_email_value( $value );
		}


		/**
		 * @param $value
		 * @return string
		 */
		public function get_email_value( $value ){
			return nl2br( trim( $value ) );
		}

	}