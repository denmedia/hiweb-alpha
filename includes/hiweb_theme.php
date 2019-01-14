<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 11:34
	 */


	class hiweb_theme{

		/** @var \hiweb_theme\widgets\nav_menu[] */
		private static $nav_menus = [];
		/** @var array \hiweb_theme\modules\mmenu[] */
		private static $mmenus = [];
		/** @var \hiweb_theme\widgets\gallery[] */
		private static $galleries = [];
		/** @var \hiweb_theme\header[] */
		private static $headers = [];


		/**
		 * Send html content mail
		 * @param string $to - оставьте поле пустым, чтобы писбмо было отправлено супер-администратору сайта
		 * @param        $subject
		 * @param        $content
		 */
		static function do_mail( $to = '', $subject = '', $content = '' ){
			if( !is_string( $to ) || trim( $to ) == '' ){
				$to = get_bloginfo( 'admin_email' );
				if( !filter_var( $to, FILTER_VALIDATE_EMAIL ) ){
					$to = get_option( 'admin_email' );
				}
			}
			$headers = [ 'From: ' . get_bloginfo( 'name' ) . ' <noreply@' . $_SERVER['SERVER_NAME'] . '>' ];
			$headers[] = 'Reply-To: noreply@' . $_SERVER['SERVER_NAME'] . '';
			add_filter( 'wp_mail_content_type', function(){ return "text/html"; } );
			wp_mail( $to, html_entity_decode( $subject ), $content, $headers );
		}


		/**
		 * @param string $id
		 * @return \hiweb_theme\widgets\slider\slider
		 */
		static function slider($id = 'default') {
			return \hiweb_theme\widgets\sliders::slider($id);
		}


		/**
		 * @param string $nav_location
		 * @return \hiweb_theme\widgets\nav_menu
		 */
		static function nav_menu( $nav_location = 'header' ){
			if( !array_key_exists( $nav_location, self::$nav_menus ) ){
				self::$nav_menus[ $nav_location ] = new \hiweb_theme\widgets\nav_menu( $nav_location );
			}
			return self::$nav_menus[ $nav_location ];
		}


		/**
		 * @param string $nav_location
		 * @return \hiweb_theme\widgets\mmenu
		 */
		static function mmenus( $nav_location = 'mobile-menu' ){
			if( !array_key_exists( $nav_location, self::$mmenus ) ){
				$mmenu = new \hiweb_theme\widgets\mmenu( $nav_location );
				$mmenu->init();
				self::$mmenus[ $nav_location ] = $mmenu;
			}
			return self::$mmenus[ $nav_location ];
		}


		/**
		 * @param null $id
		 * @return \hiweb_theme\widgets\gallery
		 */
		static function gallery( $id = null ){
			if( is_null( $id ) )
				$id = \hiweb\strings::rand();
			if( !array_key_exists( $id, self::$galleries ) ){
				self::$galleries[ $id ] = new \hiweb_theme\widgets\gallery( $id );
			}
			return self::$galleries[ $id ];
		}


		/**
		 * @param string $id
		 * @return \hiweb_theme\header
		 */
		static function header( $id = 'default' ){
			if( !array_key_exists( $id, self::$headers ) ){
				self::$headers[ $id ] = new \hiweb_theme\header( $id );
			}
			return self::$headers[ $id ];
		}


		static function init_scrolltop(){
			hiweb_theme\widgets\scrolltop::init();
		}


		/**
		 * Инициализация виджета форм
		 */
		static function init_forms(){
			hiweb_theme\widgets\forms::init();
		}


		/**
		 * @param $form_id
		 * @return \hiweb_theme\widgets\forms\form
		 */
		static function form( $form_id ){
			return \hiweb_theme\widgets\forms::get( $form_id );
		}


		static function init_breadcrumbs(){
			\hiweb_theme\widgets\breadcrumbs::init();
		}


		static function the_breadcrumbs(){
			\hiweb_theme\widgets\breadcrumbs::the();
		}


		/**
		 *
		 */
		static function init_criticalCss(){
			\hiweb_theme\tools\criticalCss::init();
		}


		static function init_languages(){
			hiweb_theme\tools\languages::init();
		}


		/**
		 * Включить поддержку ервиса MailChimp формами (автоматически включает формы)
		 */
		static function init_forms_mailchimp(){
			self::init_forms();
			\hiweb_theme\widgets\forms::init_mailchimp();
		}


		/**
		 * Включить поддержку кэша страниц
		 */
		static function init_pagesCache(){
			\hiweb_theme\tools\pagesCache::init();
		}


		/**
		 * Включить отложеную загрузку изображений
		 */
		static function init_imagesDefer(){
			\hiweb_theme\tools\imagesDefer::init();
		}


		/**
		 * Вколючить PWA
		 */
		static function init_pwa(){
			\hiweb_theme\tools\pwa::init();
		}

	}