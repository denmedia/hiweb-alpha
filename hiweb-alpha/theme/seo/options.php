<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:18
	 */

	theme\seo::$admin_menu_main_page = add_admin_menu_page( theme\seo::$admin_menu_main, '<i class="fab fa-searchengin"></i> SEO', theme\seo::$admin_menu_main_parent );

	add_action( 'init', function(){
		add_field_separator( 'Заголовок архивной страницы' )->LOCATION()->ADMIN_MENUS( theme\seo::$admin_menu_main );
		$has_archives = false;
		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->has_archive ){
				$has_archives = true;
				add_field_text( 'archive-title-' . $post_type->name )->placeholder( $post_type->label )->label( 'Заголовок для архивной страницы "' . $post_type->label . '"' )->description( 'Оставьте поле пустым6 в таком случае будет использовано название данного типа записей <code>' . $post_type->labels->name . '</code>' )->LOCATION( true );
			}
		}
		if( !$has_archives ) add_field_separator( '', 'таких страниц не обнаружено' );
		///
		add_field_separator( 'Мета-теги на страницах' )->LOCATION()->ADMIN_MENUS( theme\seo::$admin_menu_main );
		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->public ){
				add_field_checkbox( 'enable-' . $post_type->name )->label_checkbox( 'Включить поддержку настроек для "' . $post_type->label . '"' )->VALUE( 'on' )->get_parent_field()->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
				add_field_checkbox( 'enable-custom-h1-' . $post_type->name )->label_checkbox( 'Включить поддерджку индивидуального H1 для "' . $post_type->labels->name . '"' )->VALUE( 'on' )->get_parent_field()->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
				add_field_checkbox( 'enable-custom-loop-title-' . $post_type->name )->label_checkbox( 'Включить поддерджку индивидуального архивного заголовка "' . $post_type->labels->name . '"' )->VALUE( 'on' )->get_parent_field()->FORM()->WIDTH()->half()->get_parent_field()->LOCATION( true );
			}
		}
	} );