<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:52
	 */
	
	add_action( 'init', function(){
		
		$post_type_taxonomies = [];
		foreach( get_taxonomies() as $taxonomy ){
			$taxonomy = get_taxonomy( $taxonomy );
			if( $taxonomy->public && $taxonomy->publicly_queryable ){
				$post_type_taxonomies = array_merge( $post_type_taxonomies, $taxonomy->object_type );
				add_field_tab( 'SEO настройки категории' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-custom-h1' )->label( 'Индивидуальный заголовок H1' )->tooltip_help( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-custom-loop-title' )->label( 'Индивидуальный заголовок на архивной странице' )->tooltip_help( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text('seo-custom-loop-link-tag-title')->label('Тег <u>title</u> для ссылки на данный термин (категорию/метку)')->tooltip_help('Данный текст будет помещен в тег <code>a href title="..."</code>, если в шаблоне данное предусмотрено')->location(true);
				add_field_text( 'seo-meta-title' )->label( 'Мета-Заголовок <code>&lt;title&gt;...&lt;/title&gt;</code>' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_textarea( 'seo-meta-description' )->label( 'Мета-Описание страницы <code>&lt;meta name=&quot;description&quot; content=&quot;...&quot; /&gt;</code>' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-meta-keywords' )->label( 'Мета-Ключевые слова <code>&lt;meta name=&quot;keywords&quot; content=&quot;...&quot; /&gt;</code>' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_select( 'seo-meta-robots-mode' )->options( [
					'default' => '--выберите вариант--',
					'noindex' => 'noindex - Не индексировать текст страницы. Страница не будет участвовать в результатах поиска',
					'nofollow' => 'nofollow - Не переходить по ссылкам на странице	',
					'none' => 'none - Соответствует директивам noindex, nofollow',
					'noarchive' => 'noarchive - Не показывать ссылку на сохраненную копию в результатах поиска',
					'noyaca' => 'noyaca - Не использовать сформированное автоматически описание	',
					'index' => 'index - Отмена дерективы "noindex"',
					'follow' => 'follow - Отмена дерективы "nofollow"',
					'archive' => 'archive - Отмена дерективы "noarchive"',
					'all' => 'all - Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице'
				] )->label( 'Режим индексации страницы и ссылок <code>&lt;meta name=&quot;robots&quot; content=&quot;...&quot; /&gt;</code>' )->tooltip_help( 'Данный режим добавить в шапку страницы таксономии мета тег <code>robots</code> внутри тега <code>head</code>.<br><a href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html" target="_blank">Документация на Яндекс.Вебмастер</a>' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_tab('Шаблон SEO настроек для категории')->location(true);
				add_field_separator( 'Шаблон SEO настроек для всех вложенных записей и страниц данной категории', 'Оставьте поля пустыми, чтобы не использовать шаблон в том или ином месте.<br><i class="far fa-exclamation-circle"></i> Внимание! Если одна запись, страница или товар находиться сразу в несколких категориях, то возможен конфдик со случайным исходом конфликтующих данных.' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-sub-custom-h1' )->label( 'Шаблон индивидуального заголовока H1' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-sub-custom-loop-title' )->label( 'Шаблон индивидуального заголовока на архивной странице' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-sub-meta-title' )->label( 'Шаблон заголовока (title)' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_textarea( 'seo-sub-meta-description' )->label( 'Шаблон описания страницы (description)' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_text( 'seo-sub-meta-keywords' )->label( 'Шаблон ключевых слов (keywords)' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_select( 'seo-sub-meta-robots-mode' )->options( [
					'default' => '--выберите вариант--',
					'noindex' => 'noindex - Не индексировать текст страницы. Страница не будет участвовать в результатах поиска',
					'nofollow' => 'nofollow - Не переходить по ссылкам на странице	',
					'none' => 'none - Соответствует директивам noindex, nofollow',
					'noarchive' => 'noarchive - Не показывать ссылку на сохраненную копию в результатах поиска',
					'noyaca' => 'noyaca - Не использовать сформированное автоматически описание	',
					'index' => 'index - Отмена дерективы "noindex"',
					'follow' => 'follow - Отмена дерективы "nofollow"',
					'archive' => 'archive - Отмена дерективы "noarchive"',
					'all' => 'all - Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице'
				] )->label( 'Режим индексации для всех страницы и ссылок, если иного не задано самой страницей' )->tooltip_help( 'Данный режим добавить в шапку страницы таксономии мета тег <code>robots</code> внутри тега <code>head</code>.<br><a href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html" target="_blank">Документация на Яндекс.Вебмастер</a>' )->location()->TAXONOMIES( $taxonomy->name );
				add_field_tab_end();
			}
		}
		$post_type_taxonomies = array_unique( $post_type_taxonomies );
		
		foreach( get_post_types() as $post_type ){
			/** @var WP_Post_Type $post_type */
			$post_type = get_post_type_object( $post_type );
			if( $post_type->public && ( $post_type->_builtin || $post_type->publicly_queryable ) ){
				if( get_field( 'enable-custom-h1-' . $post_type->name, \theme\seo::$admin_menu_main ) ){
					add_field_text( 'seo-custom-h1' )->label( 'Индивидуальный заголовок H1' )->tooltip_help( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
				}
				if( get_field( 'enable-custom-loop-title-' . $post_type->name, \theme\seo::$admin_menu_main ) ){
					add_field_text( 'seo-custom-loop-title' )->label( 'Индивидуальный заголовок на архивной странице' )->tooltip_help( 'Оставьте поле пустым для использования основного заголовка текущей страницы' )->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
					add_field_text('seo-custom-loop-link-tag-title')->label('Тег <u>title</u> для ссылки на данную запись (пост/страницу/товар)')->tooltip_help('Данный текст будет помещен в тег <code>a href title="..."</code>, если в шаблоне данное предусмотрено')->location(true);
				}
				if( get_field( 'enable-' . $post_type->name, \theme\seo::$admin_menu_main ) ){
					add_field_separator('')->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
					add_field_text( 'seo-meta-title' )->label( 'Заголовок (title)' )->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
					add_field_textarea( 'seo-meta-description' )->label( 'Описание страницы (description)' )->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
					add_field_text( 'seo-meta-keywords' )->label( 'Ключевые слова (keywords)' )->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
					add_field_select( 'seo-meta-robots-mode' )->options( [
						'default' => '--выберите вариант--',
						'noindex' => 'noindex - Не индексировать текст страницы. Страница не будет участвовать в результатах поиска',
						'nofollow' => 'nofollow - Не переходить по ссылкам на странице	',
						'none' => 'none - Соответствует директивам noindex, nofollow',
						'noarchive' => 'noarchive - Не показывать ссылку на сохраненную копию в результатах поиска',
						'noyaca' => 'noyaca - Не использовать сформированное автоматически описание	',
						'index' => 'index - Отмена дерективы "noindex"',
						'follow' => 'follow - Отмена дерективы "nofollow"',
						'archive' => 'archive - Отмена дерективы "noarchive"',
						'all' => 'all - Соответствует директивам index и follow — разрешено индексировать текст и ссылки на странице'
					] )->label( 'Режим индексации страницы и ссылок' )->tooltip_help( 'Данный режим добавить в шапку страницы таксономии мета тег <code>robots</code> внутри тега <code>head</code>.<br><a href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html" target="_blank">Документация на Яндекс.Вебмастер</a>' )->location()->posts( $post_type->name )->META_BOX()->title( 'SEO установки' )->context()->side();
				}
			}
		}
	}, 30 );