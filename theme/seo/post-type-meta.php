<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:52
	 */


	add_action('init', function(){

		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->public ){

				if(get_field('enable-' . $post_type->name, \theme\seo::$admin_menu_main)) {

					add_field_text('seo-meta-title')->label('Заголовок')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
					add_field_text('seo-meta-keywords')->label('Ключевые слова')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
					add_field_textarea('seo-meta-description')->label('Описание страницы')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();

				}

				if(get_field('enable-custom-h1-' . $post_type->name, \theme\seo::$admin_menu_main)) {
					add_field_text('seo-custom-h1')->label('Заголовок H1')->description('Оставьте поле пустым для использования основного заголовка')->LOCATION()->POST_TYPES($post_type->name)->META_BOX()->title('SEO установки')->context()->side();
				}

			}
		}

	}, 20);