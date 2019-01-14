<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 07.10.2018
	 * Time: 22:03
	 */

	namespace hiweb_theme;


	use hiweb\strings;


	class header{

		public $layout = 'default';
		public $logotypePathOrId = 0;
		public $logotypeHrefToHome = true;
		/** @var string|array */
		public $logotype_size = 'thumbnail';
		public $nav_location = 'header';
		public $use_sticky = true;
		protected $wrap_id = '';
		protected $id = '';
		protected $root_classes = [ 'mh-head' ];
		protected $root_tags = [];


		public function __construct( $id = 'header-main' ){
			if( is_null( $id ) )
				$id = strings::rand();
			$this->id = $id;
		}


		/**
		 * @return null|string
		 */
		public function get_id(){
			return $this->id;
		}


		/**
		 * @param $class
		 * @return $this
		 */
		public function add_class( $class ){
			$this->root_classes[] = $class;
			return $this;
		}


		/**
		 * @param $tagName
		 * @param $value
		 * @return $this
		 */
		public function add_tag( $tagName, $value ){
			$this->root_tags[ $tagName ] = htmlentities( $value );
			return $this;
		}


		public function get_class(){
			return 'class="' . implode( ' ', $this->root_classes ) . '"';
		}


		public function get_tags(){
			$R = [];
			foreach( $this->root_tags as $tagName => $tagValue ){
				$R[] = $tagName . '="' . htmlentities( $tagValue ) . '"';
			}
			return implode( ' ', $R );
		}


		/**
		 * @param string $nav_location
		 * @return widgets\nav_menu
		 */
		public function get_navigate($nav_location = 'header'){
			$navigate = \hiweb_theme::nav_menu( $nav_location );
			$navigate->ul_classes[] = 'nav nav-fill';
			$navigate->item_classes[] = 'nav-item';
			return $navigate;
		}


		/**
		 * @return string
		 */
		public function get_burger_button(){
			return \hiweb_theme::mmenus( $this->nav_location )->get_burger_button();
		}


		/**
		 * @param null|string|array $size - 'thumbnail' | [200,80]
		 * @return string
		 */
		public function get_logotype( $size = null ){
			if( !$this->logotypePathOrId )
				$this->logotypePathOrId = get_field( 'logo', 'header' );
			$R = get_image( $this->logotypePathOrId )->html_picture( is_null( $size ) ? $this->logotype_size : $size );
			if( $this->logotypeHrefToHome ){
				$R = '<a href="' . get_home_url() . '">' . $R . '</a>';
			}
			return $R;
		}


		public function the(){
			$this->add_tag( 'id', $this->id );
			if( $this->use_sticky ){
				includes::jquery_mhead();
				$this->add_tag( 'data-use-stick', '' );
			}
			get_template_part( HIWEB_THEME_PARTS . '/header/layout', $this->layout );

			if( $this->use_sticky ){
				includes::defer_script_file( 'header' );
			}
		}


		/**
		 * @return string
		 */
		public function get(){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


		/**
		 * @return string
		 */
		public function __toString(){
			return $this->get();
		}

	}