<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08/12/2018
	 * Time: 23:20
	 */

	use theme\pages_cache;
	use theme\pages_cache\queue;


	//После добавления / обновления записи или страницы обновлять ее кэш  принудительно и так же в родительских записях и таксономиях так же обновлять
	add_action( 'wp_insert_post', function( $post_id, $post, $update ){
		if( wp_is_post_revision( $post ) || $post->post_status != 'publish' )
			return;
		pages_cache::get_cache( get_permalink( $post ) )->make( true );
		pages_cache::add_to_queue_relatives( $post );
	}, 999999, 3 );

	//После удаления поста в корзину, необходимо обновить кэш возможно связанных страниц
	add_action( 'after_delete_post', function( $postid ){
		$post = get_post( $postid );
		if( $post instanceof WP_Post ){
			pages_cache::add_to_queue( get_post_type_archive_link( $post->post_type ), 8 );
			pages_cache::add_to_queue_relatives( $post );
		}
	} );

	//После изменения/добавлеия темина обновить его кэш и поставить в очередь все входящие в него записи
	add_action( 'edited_terms', function( $term_id, $taxonomy ){
		pages_cache::make_cache( get_term_link( $term_id ), true );
		pages_cache::add_to_queue_relatives( get_term( $term_id ) );
	}, 9999, 2 );

	//Сбрасывать кэш, когда опции на странице админки обновлены
	add_filter( 'whitelist_options', function( $whitelist_options ){
		pages_cache::clear();
		pages_cache::add_to_queue( '/', 10 );
		return $whitelist_options;
	} );

	add_filter( 'init', function(){
		if( class_exists( '\hiweb_theme\pagesCache\pagesCache_direct' ) ){
			if( pages_cache\pages_cache_direct::get_cache_start_query() && pages_cache::is_init() && pages_cache::is_enable() && ( !isset( $_GET['nocache'] ) || $_GET['nocache'] == '1' ) ){
				show_admin_bar( false );
			}
		}
	} );

	//Выполнять фоновое обновление кэша
	add_filter( 'cron_schedules', function( $schedules ){
		if( !isset( $schedules["5min"] ) ){
			$schedules["5min"] = [
				'interval' => 5 * 60,
				'display' => __( 'Once every 5 minutes' )
			];
		}
		if( !isset( $schedules["1min"] ) ){
			$schedules["1min"] = [
				'interval' => 60,
				'display' => __( 'Once every 1 minutes' )
			];
		}
		if( !isset( $schedules["10sec"] ) ){
			$schedules["1min"] = [
				'interval' => 10,
				'display' => __( 'Once every 10 seconds' )
			];
		}
		return $schedules;
	} );
	add_action( 'hiweb_theme_pagesCache_queue', function(){
		///$url
		\hiweb\dump::the( queue::next() );
	} );