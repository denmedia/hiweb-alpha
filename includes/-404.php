<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 23/10/2018
	 * Time: 10:59
	 */

	use hiweb_theme\includes;


	if( \hiweb\context::is_admin_page() ){
		includes::fontawesome( false );
	}

	add_admin_menu_page( 'error-404', '<i class="far fa-times-octagon"></i> Стнаница 404', 'themes.php' )->page_title( '<i class="far fa-times-octagon"></i> Страница ошибки 404' );

	add_field_text( 'title' )->VALUE( '404' )->get_parent_field()->label( 'Титл страницы' )->LOCATION()->ADMIN_MENUS( 'error-404' );

	add_field_content( 'content' )->VALUE( '<h2>Упс! Данной страницы не найдено.</h2><h4>Извините...Страницу, которую Вы искали не может быть найдена...</h4>' )->get_parent_field()->label( 'Содержимое страницы' )->LOCATION()->ADMIN_MENUS( 'error-404' );