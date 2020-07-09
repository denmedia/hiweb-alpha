<?php
	
	add_admin_menu_page( theme\widgets\menu_collapse::$options_handle, ' Коллапс-меню', 'themes.php' )->icon_url( 'fas fa-list-alt' );
	
	add_field_separator( 'Добавьте, если еще не сделали это, в разделе <a href="' . get_admin_url( null, 'widgets.php' ) . '" target="_blank">"Внешний вид -> Виджеты"</a> виджет, под названием "Навигация по категориям hiWeb" в одну из позиций сайдбара. Так же укажите в данном виджете меню.' )->location()->options( theme\widgets\menu_collapse::$options_handle );
	
	add_field_fontawesome( 'icon-expand' )->default_value( 'fas fa-plus-circle' )->label( 'Иконка "раскрыть"' )->location( true );
	add_field_fontawesome( 'icon-collapse' )->default_value( 'fas fa-minus-circle' )->label( 'Иконка "свернуть"' )->location( true );