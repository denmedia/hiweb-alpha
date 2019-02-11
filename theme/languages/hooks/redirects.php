<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-02-10
	 * Time: 21:07
	 */

	use hiweb\urls;
	use theme\languages\detect;


	///FRONT PAGE AFTER AUTO LANG DETECT
	add_action( 'get_header', function(){
		if( rtrim( urls::root(), '/' ) == rtrim( urls::get()->get_url(), '/' ) ){
			wp_redirect( get_home_url(), 301 );
		}
	} );

	///REDIRECT FROM DUPLICATE DEFAULT LANG URLS
	add_action('template_redirect', function(){
		if(function_exists( 'get_queried_object' )) {
			$qo = get_queried_object();
			if($qo instanceof WP_Post) {
				if(!is_front_page() && \theme\languages::is_post_type_allowed($qo->post_type) && get_permalink($qo) != urls::root(false).detect::$uri_original) {
					wp_redirect( get_permalink($qo), 301);
				}
			}
			elseif($qo instanceof WP_Term) {
				foreach(get_taxonomy($qo->taxonomy)->object_type as $post_type) {
					if(\theme\languages::is_post_type_allowed($post_type) && get_term_link($qo) != urls::root(false).detect::$uri_original){
						wp_redirect(get_term_link($qo), 301);
					}
				}

			}
		}
	});