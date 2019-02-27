<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 11:06
	 */

	use theme\breadcrumbs;
	use theme\includes\includes;


	if( \hiweb\context::is_admin_page() ){
		includes::fontawesome( );
	}

	add_admin_menu_page( breadcrumbs::$admin_options_slug, '<i class="far fa-shoe-prints"></i> Хлебные крошки', 'themes.php' );

	///HOME CRUMB
	add_field_separator( 'Настройки домашней крошки' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'home-enable' )->label_checkbox( 'Показывать в хлебных крошках домашнюю страницу' )->VALUE( 'on' )->get_parent_field()->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_fontawesome( 'home-icon' )->label( 'Иконка для домашней крошки' )->VALUE( 'fas fa-home' )->get_parent_field()->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_text( 'home-text' )->placeholder( get_bloginfo( 'name' ) )->label( 'Название домашней кношки' )->description( 'Оставьте поле пустым, в таком случае будет взято название сайта' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_text( 'home-href' )->placeholder( get_home_url() )->label( 'Ссылка главной крошки' )->description( 'Оставьте поле пустым, в таком случае будет автоматически установлена ссылка на главную страницу' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );

	///SEPARATE CRUMB
	add_field_separator( 'Установки разделителя крошек' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'separator-enable' )->label_checkbox( 'Использовать иконку разделителя крошек' )->VALUE( 'on' )->get_parent_field()->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'separator-last-enable' )->label_checkbox( 'Показывать иконку в конце крошек (если иконки включены)' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_fontawesome( 'separator-icon' )->VALUE( 'far fa-angle-right' )->get_parent_field()->label('иконка разделителя крошек')->description('Оставьте иконку пустой, чтобы не использовать ее')->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_text('separator-text')->label('Текстовой символ разделителя')->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );

	///Nav Menu
	//add_field_separator( 'Структура крошек' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
//	$locations = \hiweb\themes::get()->locations();
//	$R = [ '' => '--не учитывать--' ];
//	foreach( $locations as $location_name => $nav_menu_id ){
//		$R[ $location_name ] = '' . wp_get_nav_menu_name( $location_name ) . ' (локация меню: ' . $location_name . ')';
//	}
	//add_field_select( 'nav_menu' )->options( $R )->label( 'Учитывать структуру крошек из выбранной навигации' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );

	///Current Page
	add_field_separator( 'Текущая страница' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'current-enable' )->label_checkbox( 'Показывать текущую страницу' )->VALUE('on')->get_parent_field()->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
	add_field_checkbox( 'current-url' )->label_checkbox( 'Использовать ссылку на текущую страницу' )->LOCATION()->ADMIN_MENUS( breadcrumbs::$admin_options_slug );
