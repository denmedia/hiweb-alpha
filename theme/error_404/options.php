<?php

	\theme\includes\admin::fontawesome( false );

	add_admin_menu_page( \theme\error_404::$admin_menu_slug, '<i class="fas fa-exclamation-square"></i> Страница 404', \theme\error_404::$admin_menu_parent )->page_title( '<i class="fas fa-exclamation-square"></i> Страница ошибки 404' );

	add_field_text( 'title' )->VALUE( '404' )->get_parent_field()->label( 'Титл страницы' )->LOCATION()->ADMIN_MENUS( \theme\error_404::$admin_menu_slug );

	add_field_content( 'content' )->VALUE( '<h2>Упс! Данной страницы не найдено.</h2><h4>Извините...Страницу, которую Вы искали не может быть найдена...</h4>' )->get_parent_field()->label( 'Содержимое страницы' )->LOCATION()->ADMIN_MENUS( \theme\error_404::$admin_menu_slug );