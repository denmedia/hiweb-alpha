<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 02:10
	 */

	namespace theme\html_layout\tags;


	use hiweb\arrays\array_;


	class body{

		/** @var array_ */
		static private $classes = [];
		/** @var array_ */
		static private $tags = [];
		/** @var bool */
		static $use_wp_class = true;
		static $use_wp_footer = true;


		/**
		 * Print <body ...>
		 */
		static function the_before(){
			do_action( '\theme\html_layout\body::the_before-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/body-before' );
			do_action( '\theme\html_layout\body::the_before-after' );
		}


		/**
		 * Print </body>
		 */
		static function the_after(){
			do_action( '\theme\html_layout\body::the_after-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/body-after' );
			do_action( '\theme\html_layout\body::the_after-after' );
		}


		/**
		 * @return array|array_
		 */
		static function get_class_array(){
			if( !self::$classes instanceof array_ ){
				self::$classes = new array_();
			}
			return self::$classes;
		}


		/**
		 * @return array_
		 */
		static function get_tags_array(){
			if(!self::$tags instanceof array_){
				self::$tags = new array_();
			}
			return self::$tags;
		}


		/**
		 *Print body classe tag
		 */
		static function the_classes(){
			if( self::$use_wp_class ){
				body_class( self::get_class_array()->get() );
			} else {
				echo implode( ' ', self::get_class_array()->get() );
			}
		}


		/**
		 * Print body tag params
		 */
		static function the_tags(){
			if(self::$tags instanceof array_){
				echo self::$tags->get_param_html_tags();
			}
		}

	}