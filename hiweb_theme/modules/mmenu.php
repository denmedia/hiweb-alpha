<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 9:42
	 */

	namespace hiweb_theme\modules;


	use hiweb\strings;
	use hiweb_theme\includes;
	use hiweb_theme\modules\nav_menu;


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


		public function hooks(){
			includes::mmenu();
			includes::touchswipe();
			add_action( 'hiweb_theme_body_prefix_after', function(){
				get_template_part( HIWEB_THEME_PARTS . '/modules/mmenu/body_prefix_after' );
				$this->nav_menu()->root_classes[] = 'mm-menu_offcanvas';
				$this->nav_menu()->the();
			} );
			add_action( 'hiweb_theme_body_sufix_before', function(){
				get_template_part( HIWEB_THEME_PARTS . '/modules/mmenu/body_sufix_before' );
			} );
			add_action( 'hiweb_theme_body_sufix_after', function(){
				?>
				<script>
                    jQuery(document).ready(function () {
                        $("#<?=$this->id?>").mmenu({
                            slidingSubmenus: <?=( $this->slidingSubmenus ? 'true' : 'false' )?>,
                            hooks: {
                                'close:after': function () {
                                    $('.hamburger[href="#<?=$this->id?>"]').removeClass('is-active');
                                }
                            }
                        }, {
                            language: "ru"
                        });
                        if (typeof $('body').swipe === 'function') {
                            $('.mm-panels').swipe({
                                //excludedElements: '.owl-carousel, input, form, button, .fancybox-inner',
                                swipeLeft: function () {
                                    $("#<?=$this->id?>").data("mmenu").close();
                                },
                            });
                        }
                    });
				</script>
				<?php
			} );
			return $this;
		}


		public function the_burger_button(){
			includes::hamburgers();
			if( !$this->burger_button instanceof hamburgers ){
				$this->burger_button = new hamburgers( '#' . $this->id );
			}
			$this->burger_button->the();
		}

	}