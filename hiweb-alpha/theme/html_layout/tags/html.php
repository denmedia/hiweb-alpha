<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 02:07
	 */

	namespace theme\html_layout\tags;


	use hiweb\arrays\array_;


	class html{

		static $tags;
		static $use_lang_locale = true;


		/**
		 * Print <!DOCTYPE html><html ...>
		 */
		static function the_before(){
			do_action( '\theme\html_layout\html::the_before-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/html-before' );
			do_action( '\theme\html_layout\html::the_before-after' );
		}


		/**
		 * Print </html>
		 */
		static function the_after(){
			do_action( '\theme\html_layout\html::the_after-before' );
			get_template_part( HIWEB_THEME_PARTS . '/html_layout/html-after' );
			do_action( '\theme\html_layout\html::the_after-after' );
		}


		/**
		 * @return array_
		 */
		static function get_tags_array(){
			if( !self::$tags instanceof array_ ){
				if( self::$use_lang_locale ){
					self::$tags['lang'] = get_locale();
				}
				self::$tags = new array_( self::$tags );
			}
			return self::$tags;
		}


		/**
		 * @return string
		 */
		static function get_tags(){
			return self::get_tags_array()->get_param_html_tags();
		}


	}