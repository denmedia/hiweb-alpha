<?php

	namespace theme;


	use hiweb\core\Paths\PathsFactory;
	use hiweb\core\Strings;
	
	
	class redirects{

		static $option_admin_slug = 'hiweb-redirects';


		static function init(){
			static $init = false;

			if( !$init ){
				add_admin_menu_page( self::$option_admin_slug, '<i class="fas fa-directions"></i> Редиректы', 'options-general.php' );

				add_field_separator( 'Правила для редиректа', 'Допускается использовать регулярные выражения для исходных ссылок' );
				$repeat = add_field_repeat( 'rules' );
				$repeat->location()->admin_menus( self::$option_admin_slug );
				$repeat->add_col_field( add_field_text( 'source' ) )->label( 'Исходная ссылка' )->width( 2 );
				$repeat->add_col_field( add_field_text( 'destination' ) )->label( 'Путь назначения' )->width( 2 );
				$repeat->add_col_field( add_field_text( 'status' )->default_value( '301' ) )->label( 'Код редиректа' );
				$repeat->add_col_field( add_field_checkbox( 'enable' )->label_checkbox('Включить')->default_value( true ) )->label( 'Включено' );

				add_action( 'get_header', function(){
					if( have_rows( 'rules', self::$option_admin_slug ) ){
						while( have_rows( 'rules', self::$option_admin_slug ) ){
							the_row();
							$source = get_sub_field( 'source' );
							if( trim( $source ) == '' ) continue;
							$current_url = PathsFactory::get_current_url( false );
							if( preg_match( '/[\*\(\)]+/', $source ) > 0 ) $source = "/{$source}/";
							if( $current_url == $source || Strings::is_regex( $source ) && preg_match( $source, $current_url, $matches ) > 0 ){
								$strtr = [];
								foreach( $matches as $index => $match ){
									$strtr[ '$'.((string)$index) ] = $match;
								}
								$destination = strtr( get_sub_field( 'destination' ), $strtr );
								wp_redirect( $destination, get_sub_field( 'status' ) );
							}
						}
					}
				} );
			}
		}

	}