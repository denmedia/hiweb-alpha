<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 27/10/2018
	 * Time: 23:04
	 */
	
	namespace theme\forms\inputs;
	
	
	use hiweb\components\Dump\Dump;
	use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
	
	
	class json extends input{
		
		static $default_name = 'json';
		static $input_title = 'Данные JSON';
		
		
		static function add_repeat_field( Field_Repeat_Options $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' ) )->label( 'Данные JSON' )->compact( 1 )->flex()->icon( '<i class="fas fa-asterisk"></i>' );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' )->default_value( self::$default_name ) )->label( 'Имя поля на латинице' );
		}
		
		
		public function the_prefix(){
			?>
			<div class="input-wrap input-wrap-<?= self::get_name() ?>"><?php
		}
		
		
		public function the(){
			$this->the_prefix();
			?><input type="hidden" name="<?= self::get_name() ?>"/>
			<?php
			$this->the_sufix();
		}
		
		
		public function the_sufix(){
			?>
			</div>
			<?php
		}
		
		
		public function get_email_value( $value ){
			$value_array = json_decode( stripslashes( $value ), true );
			if( json_last_error() === JSON_ERROR_NONE ){
				$filter_tag = 'hiweb-theme-widgets-form-input-json-' . $this->get_name() . '-email-value';
				if( has_filter( $filter_tag ) ){
					return apply_filters( $filter_tag, $value_array, $this );
				}
				else{
					if( !is_array( $value_array ) ){
						return $value_array;
					}
					else{
						$R = '';
						foreach( $value_array as $key => $val ){
							if( is_array( $val ) || is_object( $val ) ){
								$sub_val_str = [];
								foreach( $val as $sub_key => $sub_val ){
									$sub_val = ( is_array( $sub_val ) || is_object( $sub_val ) ) ? json_encode( $sub_val ) : $sub_val;
									$sub_val_str[] = ( is_numeric( $sub_key ) ? $sub_val : '<i>' . $sub_key . '</i>: ' . $sub_val );
								}
								$sub_val_str = '<div style="display: inline-block; vertical-align: top">' . join( '</br>', $sub_val_str ) . '</div>';
								$R .= '<div><b style="vertical-align: top">' . $key . ': </b>' . $sub_val_str . '</div>';
							}
							else{
								$R .= "<div><b>{$key}: </b>{$val}</div>";
							}
						}
						return $R;
					}
				}
			}
			else{
				return 'Ошибка в данных JSON <code>' . $value . '</code>';
			}
		}
		
		
	}