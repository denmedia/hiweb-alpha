<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 01:19
	 */

	namespace theme\includes;


	use hiweb\css;
	use hiweb\js;
	use hiweb\js\file;
	use hiweb\paths;
	use hiweb\urls;


	class includes{


		static private function _get_search_paths( $fileNameOrPath, $extension = 'css' ){
			return [
				$fileNameOrPath,
				get_stylesheet_directory() . '/' . $fileNameOrPath,
				get_stylesheet_directory() . '/' . $fileNameOrPath . '.min.' . $extension,
				get_stylesheet_directory() . '/' . $fileNameOrPath . '.' . $extension,
				get_template_directory() . '/' . $fileNameOrPath,
				get_template_directory() . '/' . $fileNameOrPath . '.min.' . $extension,
				get_template_directory() . '/' . $fileNameOrPath . '.' . $extension,
				HIWEB_THEME_VENDORS_DIR . '/' . $fileNameOrPath,
				HIWEB_THEME_VENDORS_DIR . '/' . $fileNameOrPath . '.min.' . $extension,
				HIWEB_THEME_VENDORS_DIR . '/' . $fileNameOrPath . '.' . $extension,
				HIWEB_DIR_VENDORS . '/' . $fileNameOrPath,
				HIWEB_DIR_VENDORS . '/' . $fileNameOrPath . '.min.' . $extension,
				HIWEB_DIR_VENDORS . '/' . $fileNameOrPath . '.' . $extension
			];
		}


		static function css( $filePathOrUrl, $in_footer = false, $deeps = [] ){
			///
			if( urls::is_url( $filePathOrUrl ) ){
				return css::add( $filePathOrUrl );
			}
			///
			foreach( self::_get_search_paths( $filePathOrUrl, 'css' ) as $path ){
				if( file_exists( $path ) && is_file( $path ) ){
					$css = css::add( $path );
					//$css->rel()->preload();
					if( $in_footer ) $css->put_to_footer();
					if( !get_array( $deeps )->is_empty() ) $css->add_deeps( $deeps );
					return $css;
				}
			}
			console_error( 'Не удалось подключить CSS файл [' . $filePathOrUrl . ']' );
			return false;
		}


		/**
		 * @param       $jsPathOrURL
		 * @param array $deeps
		 * @param bool  $inFooter
		 * @return bool|file|null
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			///
			if( urls::is_url( $jsPathOrURL ) ){
				$js = js::add( $jsPathOrURL );
				$js->set_defer();
				if( is_array( $deeps ) ) $js->add_deeps( $deeps );
				if( $inFooter ) $js->put_to_footer();
				return $js;
			}
			///
			foreach( self::_get_search_paths( $jsPathOrURL, 'js' ) as $path ){
				$path = paths::get( $path );
				if( $path->is_url() || ( $path->is_readable() && $path->is_file() ) ){
					$js = js::add( $path->get() );
					$js->set_defer();
					if( is_array( $deeps ) ) $js->add_deeps( $deeps );
					if( $inFooter ) $js->put_to_footer();
					return $js;
				}
			}
			console_error( 'Не удалось подключить JS файл [' . $jsPathOrURL . ']' );
			return false;
		}


		/**
		 * vendors/animate-css/animate.min.css
		 */
		static function animate_css(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/animate-css/animate.min.css' );
		}


		/**
		 * @param bool $include_migrate_js
		 * @return bool
		 */
		static function jquery( $include_migrate_js = false ){
			$R = static::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-3.3.1.min.js' );
			if( $include_migrate_js ){
				static::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-migrate-1.4.1.min.js' );
			}
			return $R instanceof file ? $R->handle() : false;
		}


		static function bootstrap( $include_js = false, $include_reboot_css = false ){
			static::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap-grid.min.css' );
			static::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap.min.css' );
			if( $include_reboot_css ){
				static::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap-reboot.min.css' );
			}
			if( $include_js ){
				static::js( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/js/bootstrap.min.js', [ self::jquery() ] );
			}
		}


		/**
		 * wp-content/themes/hiweb-alpha/assets/css/bootstrap-additions.css
		 */
		static function bootstrap_addition(){
			static::css(HIWEB_THEME_ASSETS_DIR.'/css/bootstrap-additions.css');
		}


		static function hamburgers(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburders.min.js', [ self::jquery() ] );
			static::css( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburgers.min.css' );
		}


		static function fancybox(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/fancybox3/jquery.fancybox.min.css' );
			static::js( HIWEB_THEME_VENDORS_DIR . '/fancybox3/jquery.fancybox.min.js', [ self::jquery() ] );
		}


		static function jquery_mmenu(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/jquery.mmenu/jquery.mmenu.all.min.css', false );
			$js = static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mmenu/jquery.mmenu.all.min.js', [ self::jquery() ] );
			if( $js instanceof file ) return $js->handle();
			return false;
		}


		static function jquery_touchswipe(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.touchSwipe/jquery.touchSwipe.min.js', [ self::jquery() ] );
		}


		static function fontawesome( $use_js = false ){
			if( $use_js ){
				static::js( HIWEB_DIR_VENDORS . '/font-awesome-5/js/all.min.js' );
			} else {
				static::css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/all.min.css' );
			}
		}


		/**
		 * @return string|null
		 */
		static function owl_carousel(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/owl-carousel/assets/owl.carousel.min.css' );
			static::css( HIWEB_THEME_VENDORS_DIR . '/owl-carousel/assets/owl.theme.default.min.css' );
			$R = static::js( HIWEB_THEME_VENDORS_DIR . '/owl-carousel/owl.carousel.min.js', [ self::jquery() ] );
			return $R instanceof \hiweb\files\file ? $R->handle() : null;
		}


		static function jquery_sticky(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.sticky/jquery.sticky.min.js', [ self::jquery( true ) ] );
		}


		static function jquery_mhead(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/jquery.mhead/jquery.mhead.min.css' );
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mhead/jquery.mhead.min.js', [ self::jquery() ] );
		}


		/**
		 * @return bool|string
		 */
		static function isotope(){
			$R = static::js( HIWEB_THEME_VENDORS_DIR . '/isotope.pkgd/isotope.pkgd.min.js', [ self::jquery() ] );
			if( $R instanceof \hiweb\files\file ){
				return $R->handle();
			}
			return false;
		}


		static function wp_default_css(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/wp-default.min.css' );
		}


		/**
		 * vendors/jquery.form/jquery.form.min.js
		 */
		static function jquery_form(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.form/jquery.form.min.js', [ self::jquery() ] );
		}


		static function jquery_mask(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mask/jquery.mask.min.js', [ self::jquery() ] );
		}


		static function stellarnav(){
			static::css( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.css' );
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.js', [ self::jquery() ] );
		}


		/**
		 * vendors/parallaxie/parallaxie.min.js
		 * @return string js handler
		 */
		static function jquery_parallaxie(){
			$R = static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.parallaxie/jquery.parallax.min.js', [ self::jquery() ] );
			if( $R instanceof file ){
				return $R->handle();
			}
			return false;
		}


		/**
		 * Плагин прикрепления блока HTML внутри другого блока, включая его перемещения во время скролла в рамках родительского блока
		 * vendors/jquery.pin/jquery.pin.min.js
		 */
		static function jquery_pin(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.pin/jquery.pin.min.js', [ self::jquery() ] );
		}


		/**
		 * vendors/jquery.simplePagination/jquery.simplePagination.js
		 * @param bool $includeCss
		 */
		static function jquery_simplePagination( $includeCss = false ){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.simplePagination/jquery.simplePagination.min.js' );
			if( $includeCss ) static::css( HIWEB_THEME_VENDORS_DIR . '/jquery.simplePagination/simplePagination.css' );
		}


		static function wowjs_animation(){
			static::animate_css();
			static::js( HIWEB_THEME_VENDORS_DIR . '/wow-js/wow.min.js' );
			static $init = false;
			if( !$init ){
				$init = true;
				add_action( '\theme\html_layout\body::the_after-before', function(){
					?>
					<script>var wow = new WOW(
                            {
                                boxClass: 'wow',      // animated element css class (default is wow)
                                animateClass: 'animated', // animation css class (default is animated)
                                offset: 0,          // distance to the element when triggering the animation (default is 0)
                                mobile: true,       // trigger animations on mobile devices (default is true)
                                live: true,       // act on asynchronously loaded content (default is true)
                                callback: function (box) {
                                    // the callback is fired every time an animation is started
                                    // the argument that is passed in is the DOM node being animated
                                },
                                scrollContainer: null,    // optional scroll container selector, otherwise use window,
                                resetAnimation: true,     // reset animation on end (default is true)
                            }
                        );
                        wow.init();</script><?php
				} );
			}
		}


		/**
		 * vendors/jquery.autocomplete/jquery.autocomplete.min.js
		 * @return bool|file|null
		 */
		static function jquery_autocomplete(){
			return static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.autocomplete/jquery.autocomplete.min.js', static::jquery() );
		}


		/**
		 * vendors/jquery.badonkatrunc/jquery.badonkatrunc.min.js
		 */
		static function jquery_badoncatrunc(){
			static::js( HIWEB_THEME_VENDORS_DIR . '/jquery.badonkatrunc/jquery.badonkatrunc.min.js', static::jquery() );
		}

	}