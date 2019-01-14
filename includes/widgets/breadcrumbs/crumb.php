<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 11:06
	 */

	namespace hiweb_theme\widgets\breadcrumbs;


	use hiweb\arrays;
	use hiweb\themes;
	use hiweb_theme\widgets\breadcrumbs;


	class crumb{

		protected $queried_object;
		protected $parent_object = false;
		protected $title;
		protected $link;
		protected $active = false;


		/**
		 * crumb constructor.
		 * @param null|string|\WP_Post|\WP_Post_Type|\WP_Term|\WP_Taxonomy $queried_object - null => автоопределение текущей страницы, '' - пустая строка означает домашнюю страницу, олибо объекты WP_post...означают текущую страницу
		 */
		public function __construct( $queried_object = null ){
			if( is_null( $queried_object ) ){
				$this->queried_object = get_queried_object();
				$this->active = true;
			} else {
				$this->queried_object = $queried_object;
			}
			///
			$this->init();
			///
		}


		public function init(){
			///HOME
			if( $this->queried_object === '' ){
				$this->title = get_field( 'home-text', breadcrumbs::$admin_options_slug );
				if( $this->title == '' ){
					$this->title = get_bloginfo( 'name' );
				}
				if( get_field( 'home-icon', breadcrumbs::$admin_options_slug ) != '' ){
					$this->title = '<i class="' . get_field( 'home-icon', breadcrumbs::$admin_options_slug ) . '"></i>' . ( $this->title != '' ? ' ' . $this->title : '' );
				}
				$this->link = get_field( 'home-href', breadcrumbs::$admin_options_slug );
				$this->link = $this->link == '' ? get_home_url() : $this->link;
			} ///POSTS
			elseif( $this->queried_object instanceof \WP_Post ) {
				$this->title = get_the_title( $this->queried_object );
				$this->link = get_permalink( $this->queried_object );
				///PAGE
				if( $this->queried_object->post_type == 'page' ){
					if( $this->queried_object->post_parent != 0 ){
						$this->parent_object = get_post( $this->queried_object->post_parent );
						return;
					}
					$this->parent_object = '';
				} ///POSTs
				else {
					//post taxonomy
					$post_taxonomies = get_object_taxonomies( $this->queried_object );
					foreach( $post_taxonomies as $taxonomy_name ){
						$terms = get_the_terms( $this->queried_object, $taxonomy_name );
						if( is_array( $terms ) && count( $terms ) > 0 ){
							foreach( $terms as $wp_term ){
								$this->parent_object = $wp_term;
								return;
							}
						}
					}
					//post parent
					if( $this->queried_object->post_parent != 0 ){
						$this->parent_object = get_post( $this->queried_object->post_parent );
						return;
					} elseif( get_queried_object()->post_type == 'post' && themes::get()->get_blog_page() instanceof \WP_Post ) {
						$this->parent_object = themes::get()->get_blog_page();
						return;
					}
					//post type
					$this->parent_object = get_post_type_object( $this->queried_object->post_type );
					return;
				}
			} ///TERMS
			elseif( $this->queried_object instanceof \WP_Term ) {
				$this->title = $this->queried_object->name;
				$this->link = get_term_link( $this->queried_object );
				if( $this->queried_object->parent != 0 ){
					$this->parent_object = get_term( $this->queried_object->parent, $this->queried_object->taxonomy );
					return;
				}
				if( get_queried_object() instanceof \WP_Post ){
					if( get_queried_object()->post_type != 'page' && get_queried_object()->post_type != 'post' ){
						$this->parent_object = get_post_type_object( get_queried_object()->post_type );
						return;
					} elseif( get_queried_object()->post_type == 'post' && themes::get()->get_blog_page() instanceof \WP_Post ) {
						$this->parent_object = themes::get()->get_blog_page();
						return;
					}
				}
				if( arrays::in_array( $this->queried_object->taxonomy, get_object_taxonomies( 'post' ) )  && themes::get()->get_blog_page() instanceof \WP_Post ){
					$this->parent_object = themes::get()->get_blog_page();
					return;
				}
				$this->parent_object = '';
			} elseif( $this->queried_object instanceof \WP_Post_Type ) {
				$this->title = $this->queried_object->label;
				$this->link = get_post_type_archive_link( $this->queried_object->name );
				$this->link = $this->link == '' ? false : $this->link;
				$this->parent_object = '';
				return;
			}
			return false;
		}


		/**
		 * @return array
		 */
		public function get_data(){
			return [
				'title' => $this->title,
				'link' => $this->link,
				'active' => $this->active,
				'parent_object' => $this->parent_object
			];
		}


		/**
		 * @return bool
		 */
		public function is_active(){
			return $this->active;
		}


		/**
		 * @return mixed
		 */
		public function get_title(){
			return $this->title;
		}


		/**
		 * @return mixed
		 */
		public function get_link(){
			return $this->link;
		}


		public function the(){
			ob_start();
			get_template_part( HIWEB_THEME_PARTS . '/widgets/breadcrumbs/item-prefix' );
			///
			get_template_part( HIWEB_THEME_PARTS . '/widgets/breadcrumbs/item-title', ( $this->active || $this->link === false ) ? '' : 'link' );
			///
			get_template_part( HIWEB_THEME_PARTS . '/widgets/breadcrumbs/item-sufix' );
			echo strtr( ob_get_clean(), [ '{link}' => $this->link, '{title}' => $this->title, '{active-class}' => $this->active ? 'active' : '' ] );
		}


		/**
		 * @return bool
		 */
		public function is_front_page(){
			return is_string( $this->queried_object ) && trim( $this->queried_object ) === '';
		}


		/**
		 * @return bool
		 */
		public function is_page(){
			return $this->queried_object instanceof \WP_Post && $this->queried_object->post_type == 'page';
		}


		/**
		 * @return bool
		 */
		public function is_single(){
			return $this->queried_object instanceof \WP_Post && $this->queried_object->post_type == 'page';
		}


		/**
		 * @return bool
		 */
		public function is_post(){
			return $this->queried_object instanceof \WP_Post;
		}


		/**
		 * @return bool
		 */
		public function is_archive(){
			return $this->queried_object instanceof \WP_Term;
		}


		/**
		 * @return mixed
		 */
		public function get_parent_object(){
			return $this->parent_object;
		}


		/**
		 * @return bool|crumb
		 */
		public function get_parent_crumb(){
			$parent_object = $this->get_parent_object();
			if( $parent_object === false ) return false; else return new crumb( $parent_object );
		}

	}