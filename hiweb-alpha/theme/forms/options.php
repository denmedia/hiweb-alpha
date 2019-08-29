<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 11:18
	 * @var \theme\forms self
	 */

	namespace theme\widgets;


	use theme\forms;


	///Options
	self::$options_object = add_admin_menu_page( self::$options_name, '<i class="fas fa-cog"></i> Опции', 'edit.php?post_type=' . self::$post_type_name );
	add_field_text( 'email' )->placeholder( get_bloginfo( 'admin_email' ) )->label( 'Адрес почты, на который будет отправляться сообщения.' )->description( 'Этот адрес будет стандартным для приема сообщений. Если оставить поле пустым, письма будут отправляться на адрес супер-администратора <b>' . get_bloginfo( 'admin_email' ) . ' <a href="' . get_admin_url( null, 'options-general.php#home-description' ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> Для каждой формы так же можно установить индивидуальный адрес. Так же можно указать несколько адресов через запятую или пробел, например: <code>info@email.com admin@email.com</code>' )->LOCATION()->ADMIN_MENUS( self::$options_name );
	add_field_text( 'reply_email' )->placeholder( 'noreply@{domain}' )->VALUE( 'noreply@{domain}' )->get_parent_field()->label( 'Укажите адрес отправителя' )->description( 'Если оставить пустым адрес отправителя, он будет сформирован автоматически по шаблону noreply@{domain}<br>Допускаеться использование шорткода <code>{domain}</code> - домен текущего сайта.' )->LOCATION( true );

	add_field_text( 'email' )->placeholder( get_field( 'email', self::$options_name ) )->label( 'Адрес почты для данной формы, на который будет отправляться сообщения.' )->description( 'Этот адрес(а) будет стандартным для приема сообщений, игнорируя общие установки адресов для всех форм. Если оставить поле пустым, письма будут отправляться на адрес, указанный в основных настройках форм <b>' . get_field( 'email', self::$options_name ) . '</b> <a href="' . get_admin_url( null, 'options.php?page=' . forms::$options_name ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> или на адрес супер-администратора <b>' . get_bloginfo( 'admin_email' ) . ' <a href="' . get_admin_url( null, 'options-general.php#home-description' ) . '" data-tooltip="Изменить этот адрес" title="Изменить этот адрес"><i class="fas fa-pencil-alt"></i></a></b> Так же можно указать несколько адресов через запятую или пробел, например: <code>info@email.com admin@email.com</code>' )->LOCATION()->POST_TYPES( forms::$post_type_name )->POSITION()->edit_form_after_title();

	add_field_text( 'reply_email' )->placeholder( get_field('reply_email', self::$options_name) )->label( 'Укажите адрес отправителя' )->description( 'Если оставить пустым адрес отправителя, он будет взят из опций <code>'.get_field('reply_email', self::$options_name).'</code><br>Допускаеться использование шорткода <code>{domain}</code> - домен текущего сайта.' )->LOCATION()->POST_TYPES( forms::$post_type_name )->POSITION()->edit_form_after_title();

	add_field_separator( 'Шаблоны писем' )->LOCATION()->ADMIN_MENUS( self::$options_name );
	add_field_text( 'theme-email-admin' )->label( 'Тема письма для администратора' )->description( $strtr_descriptions )->VALUE( 'На сайте {site-name} была отправлена форма' )->get_parent_field()->LOCATION( true );
	add_field_content( 'content-email-admin' )->label( 'Стандартное содердимое письма для администратора' )->description( $strtr_descriptions )->VALUE( '<h3>На сайте <a href="#{home-url}">{site-name}</a> была заполнена форма "{form-title}".</h3>
Посетитель указал следующие данные:
<div style="background: #ddd; padding: .5em 1em; font-size: 1.2rem;">
{data-list}
</div>
С уважением, <a href="#{home-url}">{site-name}</a>' )->get_parent_field()->LOCATION( true );
	add_field_checkbox( 'send-client-email' )->label_checkbox( 'Отправлять письмо заполнителю формы по указанному им адресу, в случае, если в форме было поле email и оно было корректно заполнено.' )->LOCATION( true );
	add_field_text( 'theme-email-client' )->label( 'Тема письма для заполнителя' )->description( $strtr_descriptions )->VALUE( 'Вы заполнили форму на сайте {site-name}' )->get_parent_field()->LOCATION( true );
	add_field_content( 'content-email-client' )->label( 'Стандартное содердимое письма для заполнителя' )->description( $strtr_descriptions )->VALUE( 'Вы указали данные на сайте <a href="#{home-url}">{site-name}</a>
<div style="background: #ddd; padding: .5em 1em;">
{data-list}
</div>
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
