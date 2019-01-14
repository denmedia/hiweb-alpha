<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06/12/2018
	 * Time: 09:29
	 */
	/**
	 * @var \hiweb_theme\tools\pwa self
	 */

	if( is_admin() ){
		include_css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/all.min.css' );
	}

	if( !\hiweb\urls::get()->is_ssl() ) hiweb\admin::NOTICE( 'Внимание! На сайте не установлен SSL. Это требуется для того, чтобы браузер работал в режиме PWA.' )->CLASS_()->error();

	$admin_menu = add_admin_menu_page( self::$admin_menu_slug, '<i class="fal fa-mobile-android"></i> PWA', 'options-general.php' );
	$admin_menu->page_title( '<i class="fal fa-mobile-android"></i> Установки Progressive Web Application' );

	add_field_separator( '<i class="fal fa-cog"></i> Основные настройки' )->LOCATION()->ADMIN_MENUS( 'hiweb-theme-pwa' );
	add_field_image( 'icon' )->label( 'Иконка приложения в формате PNG' )->description( 'Реккомендуемый размер иконки не менее 192пикс, иначе посетителю не будет задан вопрос об установке ссылки на рабочий стол' )->LOCATION( true );
	add_field_image( 'icon-splash' )->label( 'Иконка на сплэш-экран, в формате PNG' )->description( 'Это изображение будет показано на жкране зщагрузки приложения, реккомендуемый (минимальный) размер 512пикс. Если не устанавливать иконку, будет автоматически заимствовано изображен е основной иконки.' )->LOCATION( true );
	add_field_text( 'name' )->placeholder( get_bloginfo( 'name' ) )->label( 'Наименовние приложения' )->LOCATION( true );
	add_field_text( 'short_name' )->placeholder( get_bloginfo( 'name' ) )->label( 'Короткое наименовние приложения' )->LOCATION( true );
	add_field_text( 'description' )->placeholder( get_bloginfo( 'description' ) )->label( 'Описание приложения' )->LOCATION( true );
	add_field_text( 'related_applications-play' )->label( 'Ссылка в Google Play для андроид приложения' )->LOCATION( true );
	add_field_text( 'related_applications-itunes' )->label( 'Ссылка в iTunes для iOS приложения' )->LOCATION( true );
	add_field_select( 'orientation' )->options( [ 'any' => 'Любое положение', 'natural' => 'Натуральное', 'portrait' => 'Портретный', 'landscape' => 'Горизонтальный', 'portrait-primary' => 'Портретный (приоритетно)', 'portrait-secondary' => 'Портретный (второстепенно)', 'landscape-primary' => 'Горизонтальный (приоритетно)', 'landscape-secondary' => 'Горизонтально (второстепенно)' ] )->label( 'Привязанная ориентация приложения' )->LOCATION( true );
	add_field_select( 'display' )->options( [ '' => '--выберите пункт--', 'fullscreen' => 'Полноэкранный режим', 'minimul-ui' => 'Минимальный', 'standalone' => 'Отдельное окно', 'browser' => 'Стандартный браузерный' ] )->label( 'Стиль отображения' )->LOCATION( true );
	add_field_color( 'theme_color' )->VALUE( '#ffffff' )->get_parent_field()->label( 'Цвет темы' )->description( 'Данный цвет используется в статусбаре, если приложение работает не полноэкранном режиме, а так же отображается в фоне заголовка, под иконкой приложения в основной окне прилоржения' )->LOCATION( true );
	add_field_color( 'background_color' )->VALUE( '#ffffff' )->get_parent_field()->label( 'Цвет заднего фона' )->description( 'В момент загрузки приложения этот цвет заполдняет экран под иконкой' )->LOCATION( true );

	///
	add_field_separator( '<i class="fas fa-cog"></i> Service Worker' )->LOCATION( true );
	add_field_checkbox( 'service-worker-enable' )->label_checkbox( 'Включить поддержку Service Worker' )->LOCATION( true );

	///
	add_field_separator( '<i class="fab fa-safari"></i> Установки для Safari iOS' )->LOCATION( true );
	add_field_checkbox( 'apple-mobile-web-app-capable' )->label_checkbox( 'Полноэкранный режим браузера' )->description( 'Опция удаляет адресную строку и кнопки навигации по умолчанию в Safari iOS' )->LOCATION( true );
	add_field_select( 'apple-mobile-web-app-status-bar-style' )->options( [ 'default' => 'Стандартный', 'black' => 'Черный статус бар', 'black-translucent' => 'Прозрачный с черным текстом' ] )->VALUE( 'default' )->get_parent_field()->label( 'Стиль статус бара в Safari iOS' )->description( '' )->LOCATION( true );

	///
	add_field_separator( '<i class="fab fa-android"></i> Установка для браузера Android и Google Chrome (на iOS)' )->LOCATION( true );
	add_field_color( 'head-meta-theme-color' )->label( 'Цвет адресной строки в браузераз (для Android 5.0 и выше)' )->LOCATION( true );