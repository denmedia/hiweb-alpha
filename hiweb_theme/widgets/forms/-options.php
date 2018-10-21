<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 11:18
	 * @var \hiweb_theme\widgets\forms self
	 */

	namespace hiweb_theme\widgets;


	use hiweb_theme\widgets\forms\inputs\button;
	use hiweb_theme\widgets\forms\inputs\checkbox;
	use hiweb_theme\widgets\forms\inputs\email;
	use hiweb_theme\widgets\forms\inputs\phone;
	use hiweb_theme\widgets\forms\inputs\text;
	use hiweb_theme\widgets\forms\inputs\textarea;


	self::$post_type_object = add_post_type( self::$post_type_name );
	self::$post_type_object->menu_icon( 'fas fa-comment-alt-edit' );
	//self::$post_type->menu_icon('data:image/svg+xml;base64,');
	self::$post_type_object->labels()->menu_name( 'Формы на сайте' )->name( 'Формы' );
	self::$post_type_object->supports()->title();
	self::$post_type_object->public_( true )->has_archive( false )->show_ui( true )->show_in_menu( true )->show_in_nav_menus( false )->show_in_admin_bar( false );
	///
	$INPUTS = add_field_repeat( 'inputs' );
	$INPUTS->label( 'Поля ввода' )->LOCATION()->POST_TYPES( self::$post_type_name );
	//
	text::add_repeat_field( $INPUTS );
	textarea::add_repeat_field( $INPUTS );
	email::add_repeat_field( $INPUTS );
	phone::add_repeat_field( $INPUTS );
	checkbox::add_repeat_field( $INPUTS );
	button::add_repeat_field( $INPUTS );
	//

	$strtr_descriptions = [];
	foreach(
		forms::get_strtr_templates( [
			'{data-list}' => 'Список заполненных данных',
			'{name}' => 'Содержимое данного поля (вместо {name} укажите имя поля)'
		], true ) as $key => $descript
	){
		$strtr_descriptions[] = '<code>' . $key . '</code> - ' . $descript;
	}
	$strtr_descriptions = implode( ', ', $strtr_descriptions );
	//
	add_field_separator( 'Статус отправки формы AJAX', 'Эти настройки актуальны только для данной формы. Если оставить их незаполненными, вместо них будут использованы стандартные установки со страницы <a data-tooltip="Открыть страницу опций" href="'.get_admin_url(null, 'edit.php?post_type='.self::$post_type_name.'&page='.self::$options_name).'">Опции формы</a>' )->LOCATION()->POST_TYPES( self::$post_type_name );
	add_field_fontawesome( 'icon-process' )->label( 'Иконка процесса отправки' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_fontawesome( 'icon-success' )->label( 'Иконка удачной отправки сообщения' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_fontawesome( 'icon-warn' )->label( 'Иконка не верно заполненной формы' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_fontawesome( 'icon-error' )->label( 'Иконка ошибки во время отправки' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-process' )->label( 'Текст отправки формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-success' )->label( 'Текст удачной отправки формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-warn' )->label( 'Текст ошибки заполненной формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-error' )->label( 'Текст ошибки в процессе отправки формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );

	///

	///Options
	self::$options_object = add_admin_menu_page( self::$options_name, '<i class="fas fa-cog"></i> Опции', 'edit.php?post_type=' . self::$post_type_name );
	add_field_text( 'email' )->placeholder( get_bloginfo( 'admin_email' ) )->label( 'Адрес поты, на который будет отправляться сообщения.' )->description( 'Этот адрес будет стандартным для приема сообщений. Если оставить поле пустым, письма будут отправляться на адрес супер-администратора <b>' . get_bloginfo( 'admin_email' ) . ' <a href="' . get_admin_url( null, 'options-general.php#home-description' ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> Для каждой формы так же можно установить индивидуальный адрес. Так же можно указать несколько адресов через запятую или пробел, например: <code>info@email.com admin@email.com</code>' )->LOCATION()->ADMIN_MENUS( self::$options_name );

	add_field_separator( 'Шаблоны писем' )->LOCATION( true );
	add_field_text( 'theme-email-admin' )->label( 'Тема письма для администратора' )->description( $strtr_descriptions )->VALUE( 'На сайте {site-name} была отправлена форма' )->get_parent_field()->LOCATION( true );
	add_field_content( 'content-email-admin' )->label( 'Стандартное содердимое письма для администратора' )->description( $strtr_descriptions )->VALUE( '<h3>На сайте <a href="#{home-url}">{site-name}</a> была заполнена форма "{form-title}".</h3>
Посетитель указал следующие данные:
<hr>
{data-list}
<hr>
С уважением, <a href="#{home-url}">{site-name}</a>' )->get_parent_field()->LOCATION( true );
	add_field_checkbox( 'send-client-email' )->label_checkbox( 'Отправлять письмо заполнителю формы по указанному им адресу, в случае, если в форме было поле email и оно было корректно заполнено.' )->LOCATION( true );
	add_field_text( 'theme-email-client' )->label( 'Тема письма для заполнителя' )->description( $strtr_descriptions )->VALUE( 'Вы заполнили форму на сайте {site-name}' )->get_parent_field()->LOCATION( true );
	add_field_content( 'content-email-client' )->label( 'Стандартное содердимое письма для заполнителя' )->description( $strtr_descriptions )->VALUE( 'Вы указали данные на сайте <a href="#{home-url}">{site-name}</a>
{data-list}
<hr>
С уважением, <a href="#{home-url}">{site-name}</a>' )->get_parent_field()->LOCATION( true );

	add_field_separator( 'Статус отправки формы AJAX', 'Иконки и сообщения о статусе отправки' )->LOCATION( true );
	add_field_fontawesome( 'icon-process' )->VALUE( 'fal fa-clock' )->get_parent_field()->label( 'Иконка процесса отправки' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_fontawesome( 'icon-success' )->VALUE( 'fal fa-comment-alt-check' )->get_parent_field()->label( 'Иконка удачной отправки сообщения' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_fontawesome( 'icon-warn' )->VALUE( 'fal fa-comment-exclamation' )->get_parent_field()->label( 'Иконка не верно заполненной формы' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_fontawesome( 'icon-error' )->VALUE( 'fal fa-comment-times' )->get_parent_field()->label( 'Иконка ошибки во время отправки' )->FORM()->WIDTH()->quarter()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-process' )->VALUE( 'Отправка сообщения...' )->get_parent_field()->label( 'Текст отправки формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-success' )->VALUE( 'Спасибо, сообщение было отправлено.' )->get_parent_field()->label( 'Текст удачной отправки формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-warn' )->VALUE( 'Сообщение не отправлено, не верно заполнена форма' )->get_parent_field()->label( 'Текст ошибки заполненной формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	add_field_textarea( 'text-error' )->VALUE( 'Ошибка во время отправки сообщения, попробуйте снова.' )->get_parent_field()->label( 'Текст ошибки в процессе отправки формы' )->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
	//

	///Options reCaptcha
	self::$options_object_recaptcha = add_admin_menu_page( self::$options_name_recapthca, '<i class="fas fa-user-check"></i> reCaptcha', 'edit.php?post_type=' . self::$post_type_name );
	add_field_separator( 'Google reCaptcha v.3', 'Воспользуйтесь Google reCaptcha (анти-бот проверка) для фильтрации ботов.<br><a href="https://www.google.com/recaptcha/admin#v3signup" target="_blank"><i class="fas fa-key"></i> Получите секретный и публичный ключ на странице сервиса.</a><br><a href="https://developers.google.com/recaptcha/docs/v3" target="_blank"><i class="fas fa-book"></i> Документация сервиса.</a>' )->LOCATION()->ADMIN_MENUS( self::$options_name_recapthca );
	add_field_text( 'public-key' )->label( 'Публичный ключ' )->LOCATION( true );
	add_field_text( 'private-key' )->label( 'Секретный (приватный) ключ' )->LOCATION( true );
	add_field_text( 'text-error' )->label( 'Сообщение о неудачной проверка анти-бот фильтра' )->VALUE( 'Ошибка проверки анти-бот фильтра' )->get_parent_field()->LOCATION( true );
	///
	///
