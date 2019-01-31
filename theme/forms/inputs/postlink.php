<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-29
	 * Time: 09:42
	 */

	namespace theme\forms\inputs;


	use hiweb\fields\types\repeat\field;


	class postlink extends input{

		static $default_name = 'postlink';
		static $input_title = 'Сылка на страницу';


		static function add_repeat_field( field $parent_repeat_field ){
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'label' )->placeholder( 'Лейбл поля' )->VALUE( 'Форма отправлена со страницы' )->get_parent_field() )->label( 'Ссылка на страницу' )->compact( 1 );
			$parent_repeat_field->add_col_flex_field( self::$input_title, add_field_text( 'name' )->placeholder( 'Имя поля на латинице' )->VALUE('postlink')->get_parent_field() )->label( 'Имя поля на латинице' );
		}


		public function the(){
			?><input tabindex="" type="hidden" name="<?= self::get_name() ?>" value="<?= get_the_ID() ?>"/>
			<?php
		}


		/**
		 * @param $value
		 * @return string
		 */
		public function get_email_value( $value ){
			if( !is_numeric( $value ) || intval( $value ) < 1 ){
				return 'Страница не указана';
			}
			$wp_post = get_post( $value );
			if( $wp_post instanceof \WP_Post ){
				return '<a href="' . get_permalink( $wp_post ) . '">' . $wp_post->post_title . '</a>';
			}
			return 'не удалось получить сылку на страницу';
		}


	}