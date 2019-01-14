<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 23:18
	 */

	namespace hiweb_theme;


	use hiweb\context;
	use hiweb\css;
	use hiweb\js\file;
	use hiweb\paths;
	use hiweb_theme\tools\criticalCss;


	class includes{

		static $defer_script_files = [];
		static $async_script_files = [];

		static $wp_block_library = false;


		/**
		 * @param        $filePathOrUrl
		 * @param bool   $in_footer
		 * @param array  $deeps
		 * @return bool|css\file|null
		 */
		static function css( $filePathOrUrl, $in_footer = false, $deeps = [] ){
			if( !context::is_frontend_page() )
				return null;
			if( is_null( $in_footer ) )
				$in_footer = criticalCss::is_init();
			//
			$search_paths = [
				$filePathOrUrl,
				get_stylesheet_directory() . '/' . $filePathOrUrl,
				get_stylesheet_directory() . '/' . $filePathOrUrl . '.min.css',
				get_stylesheet_directory() . '/' . $filePathOrUrl . '.css',
				get_template_directory() . '/' . $filePathOrUrl,
				get_template_directory() . '/' . $filePathOrUrl . '.min.css',
				get_template_directory() . '/' . $filePathOrUrl . '.css',
				HIWEB_THEME_VENDORS_DIR . '/' . $filePathOrUrl,
				HIWEB_THEME_VENDORS_DIR . '/' . $filePathOrUrl . '.min.css',
				HIWEB_THEME_VENDORS_DIR . '/' . $filePathOrUrl . '.css',
				HIWEB_DIR_VENDORS . '/' . $filePathOrUrl,
				HIWEB_DIR_VENDORS . '/' . $filePathOrUrl . '.min.css',
				HIWEB_DIR_VENDORS . '/' . $filePathOrUrl . '.css'
			];
			foreach( $search_paths as $path ){
				if( file_exists( $path ) && is_file( $path ) ){
					$css = css::add( $path );
					//$css->rel()->preload();
					if( $in_footer )
						$css->put_to_footer();
					if( !get_array( $deeps )->is_empty() )
						$css->add_deeps( $deeps );
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
		 * @return bool|\hiweb\js\file|null
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			if( !context::is_frontend_page() )
				return null;
			//
			$search_paths = [
				$jsPathOrURL,
				get_stylesheet_directory() . '/' . $jsPathOrURL,
				get_stylesheet_directory() . '/' . $jsPathOrURL . '.min.js',
				get_stylesheet_directory() . '/' . $jsPathOrURL . '.js',
				get_template_directory() . '/' . $jsPathOrURL,
				get_template_directory() . '/' . $jsPathOrURL . '.min.js',
				get_template_directory() . '/' . $jsPathOrURL . '.js',
				HIWEB_THEME_VENDORS_DIR . '/' . $jsPathOrURL,
				HIWEB_THEME_VENDORS_DIR . '/' . $jsPathOrURL . '.min.js',
				HIWEB_THEME_VENDORS_DIR . '/' . $jsPathOrURL . '.js',
				HIWEB_DIR_VENDORS . '/' . $jsPathOrURL,
				HIWEB_DIR_VENDORS . '/' . $jsPathOrURL . '.min.js',
				HIWEB_DIR_VENDORS . '/' . $jsPathOrURL . '.js'
			];
			foreach( $search_paths as $path ){
				$path = paths::get( $path );
				if( $path->is_url() || ( $path->is_readable() && $path->is_file() ) ){
					return \hiweb\js( $path->get(), $deeps, $inFooter );
				}
			}
			console_error( 'Не удалось подключить JS файл [' . $jsPathOrURL . ']' );
			return false;
		}


		/**
		 * @param string $include_name - script name inside hiweb_theme/includes/rest-{$include_name}.min.js
		 * @param bool   $add_unique
		 */
		static function defer_script_file( $include_name, $add_unique = true ){
			if( $add_unique ){
				self::$defer_script_files[ $include_name ] = $include_name;
			} else {
				self::$defer_script_files[] = $include_name;
			}
		}


		static function async_script_file( $include_name, $add_unique = true ){
			if( $add_unique ){
				self::$async_script_files[ $include_name ] = $include_name;
			} else {
				self::$async_script_files[] = $include_name;
			}
		}


		/**
		 * vendors/animate-css/animate.min.css
		 */
		static function animate_css(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/animate-css/animate.min.css' );
		}


		/**
		 * @param bool $include_migrate_js
		 * @return bool
		 */
		static function jquery( $include_migrate_js = false ){
			$R = self::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-3.3.1.min.js' );
			if( $include_migrate_js ){
				self::js( HIWEB_THEME_VENDORS_DIR . '/jquery3/jquery-migrate-1.4.1.min.js' );
			}
			return $R instanceof file ? $R->handle() : false;
		}


		static function bootstrap( $include_js = false, $include_reboot_css = false ){
			self::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap-grid.min.css' );
			self::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap.min.css' );
			if( $include_reboot_css ){
				self::css( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/css/bootstrap-reboot.min.css' );
			}
			if( $include_js ){
				self::js( HIWEB_THEME_VENDORS_DIR . '/bootstrap4/js/bootstrap.min.js', [ self::jquery() ] );
			}
		}


		static function hamburgers(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburders.min.js', [ self::jquery() ] );
			self::css( HIWEB_THEME_VENDORS_DIR . '/hamburgers/hamburgers.min.css' );
		}


		static function fancybox(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/fancybox3/jquery.fancybox.min.css' );
			self::js( HIWEB_THEME_VENDORS_DIR . '/fancybox3/jquery.fancybox.min.js', [ self::jquery() ] );
		}


		static function jquery_mmenu(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/jquery.mmenu/jquery.mmenu.all.min.css', false );
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mmenu/jquery.mmenu.all.min.js', [ self::jquery() ] );
		}


		static function jquery_touchswipe(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.touchSwipe/jquery.touchSwipe.min.js', [ self::jquery() ] );
		}


		static function fontawesome( $use_js = true ){
			if( $use_js ){
				self::js( HIWEB_DIR_VENDORS . '/font-awesome-5/js/all.min.js' );
			} else {
				self::css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/all.min.css' );
			}
		}


		static function owl_carousel(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/owl-carousel/assets/owl.carousel.min.css' );
			self::css( HIWEB_THEME_VENDORS_DIR . '/owl-carousel/assets/owl.theme.default.min.css' );
			self::js( HIWEB_THEME_VENDORS_DIR . '/owl-carousel/owl.carousel.min.js', [ self::jquery() ] );
		}


		static function jquery_sticky(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.sticky/jquery.sticky.min.js', [ self::jquery( true ) ] );
		}


		static function jquery_mhead(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/jquery.mhead/jquery.mhead.min.css' );
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mhead/jquery.mhead.min.js', [ self::jquery() ] );
		}


		static function isotope(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/isotope.pkgd/isotope.pkgd.min.js', [ self::jquery() ] );
		}


		static function wp_default_css(){
			self::css( HIWEB_THEME_VENDORS_DIR . '/wp-default.min.css' );
		}


		/**
		 * vendors/jquery.form/jquery.form.min.js
		 */
		static function jquery_form(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.form/jquery.form.min.js', [ self::jquery() ] );
		}


		static function jquery_mask(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.mask/jquery.mask.min.js', [ self::jquery() ] );
		}


		static function stellarnav(){
			includes::css( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.css' );
			includes::js( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.js', [ self::jquery() ] );
		}


		/**
		 * vendors/parallaxie/parallaxie.min.js
		 */
		static function parallaxie(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/parallaxie/parallaxie.min.js', [ self::jquery() ] );
		}


		/**
		 * Плагин прикрепления блока HTML внутри другого блока, включая его перемещения во время скролла в рамках родительского блока
		 * vendors/jquery.pin/jquery.pin.min.js
		 */
		static function jquery_pin(){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.pin/jquery.pin.min.js', [ self::jquery() ] );
		}


		/**
		 * vendors/jquery.simplePagination/jquery.simplePagination.js
		 * @param bool $includeCss
		 */
		static function jquery_simplePagination( $includeCss = false ){
			self::js( HIWEB_THEME_VENDORS_DIR . '/jquery.simplePagination/jquery.simplePagination.min.js' );
			if( $includeCss )
				self::css( HIWEB_THEME_VENDORS_DIR . '/jquery.simplePagination/simplePagination.css' );
		}


		static function wp_block_library(){
			self::$wp_block_library = true;
		}

	}