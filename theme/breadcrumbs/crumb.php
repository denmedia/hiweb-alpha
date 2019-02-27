<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/10/2018
	 * Time: 11:06
	 */

	namespace theme\breadcrumbs;


	use theme\breadcrumbs;
	use theme\structures\structure;


	class crumb extends structure{

		protected $queried_object;
		protected $parent_object = false;
		protected $title;
		protected $link;
		protected $active = false;


		public function __construct( $object ){
			parent::__construct( $object );
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
		 * @param bool $force_raw
		 * @return mixed|string
		 */
		public function get_title( $force_raw = true ){
			if( $this->get_id() == '' && get_field( 'home-text', breadcrumbs::$admin_options_slug ) != '' ){
				return get_field( 'home-text', breadcrumbs::$admin_options_slug );
			}
			return parent::get_title( $force_raw );
		}


		/**
		 * @return mixed
		 */
		public function get_link(){
			return $this->get_url();
		}


		public function the(){
			ob_start();
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-prefix' );
			///
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-title', ( $this->active || $this->get_link() === false ) ? '' : 'link' );
			///
			get_template_part( HIWEB_THEME_PARTS . '/breadcrumbs/item-sufix' );
			echo strtr( ob_get_clean(), [ '{link}' => $this->get_link(), '{title}' => $this->get_title(), '{active-class}' => $this->active ? 'active' : '' ] );
		}


		/**
		 * @return mixed
		 */
		public function get_parent_object(){
			return reset( $this->get_parent_wp_objects() );
		}


		/**
		 * @return bool|crumb
		 */
		public function get_parent_crumb(){
			$parent_object = $this->get_parent_object();
			if( $parent_object === false )
				$R = false; else $R = new crumb( $this->get_parent_object() );
			return $R;
		}

	}