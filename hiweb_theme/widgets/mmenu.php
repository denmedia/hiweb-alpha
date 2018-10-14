<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 9:42
	 */

	namespace hiweb_theme\widgets;


	use hiweb\strings;
	use hiweb_theme\includes;
	use hiweb_theme\widgets\nav_menu;


	class mmenu{

		/** @var string Name of nav location in WP */
		public $nav_location = 'mobile-menu';
		public $nav_menu_depth = 5;
		public $slidingSubmenus = true;
		private $nav_menu;
		/** @var hamburgers */
		private $burger_button;
		private $id;


		public function __construct( $nav_location ){
			if( !empty( $nav_location ) ){
				$this->nav_location = $nav_location;
			}
			$this->id = strings::rand();
		}


		/**
		 * @return nav_menu
		 */
		public function nav_menu(){
			if( !$this->nav_menu instanceof nav_menu ){
				$this->nav_menu = new nav_menu( $this->nav_location );
				$this->nav_menu->id = $this->id;
				$this->nav_menu->use_stellarnav = false;
				$this->nav_menu->depth = $this->nav_menu_depth;
			}
			return $this->nav_menu;
		}


		public function init(){
			includes::jquery_mmenu();
			includes::jquery_touchswipe();
			add_action( 'hiweb_theme_body_prefix_after', function(){
				get_template_part( HIWEB_THEME_PARTS . '/modules/mmenu/body_prefix_after' );
				$this->nav_menu()->root_classes[] = 'mm-menu_offcanvas';
				$this->nav_menu()->the();
			} );
			add_action( 'hiweb_theme_body_sufix_before', function(){
				get_template_part( HIWEB_THEME_PARTS . '/modules/mmenu/body_sufix_before' );
			} );
			includes::defer_script_file( 'mmenu' );
			return $this;
		}


		public function the_burger_button(){
			includes::hamburgers();
			if( !$this->burger_button instanceof hamburgers ){
				$this->burger_button = new hamburgers( '#' . $this->id );
			}
			$this->burger_button->the();
		}


		public function get_burger_button(){
			ob_start();
			$this->the_burger_button();
			return ob_get_clean();
		}

	}