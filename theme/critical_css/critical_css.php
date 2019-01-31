<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 31/10/2018
	 * Time: 22:56
	 */

	namespace theme;


	use hiweb\context;
	use theme\critical_css\includes\template;
	use theme\includes\critical_css\styles;
	use theme\includes\frontend;


	class critical_css{

		/** @var bool */
		static private $init = false;
		/** @var bool */
		static private $current_theme_template_file;
		/** @var template[] */
		static private $template_styles = [];
		/** @var styles[] */
		static private $styles = [];


		static function init(){
			if( !self::$init ){
				self::$init = true;
				require_once __DIR__ . '/includes/template.php';
				require_once __DIR__ . '/includes/styles.php';
				require_once __DIR__ . '/hooks.php';
				///
				frontend::jquery();
				///
				//cache dir
				if( !file_exists( self::get_cache_dir() ) ){
					@mkdir( self::get_cache_dir(), 0644, true );
				}
			}
		}


		/**
		 * @return bool
		 */
		static function is_init(){
			return self::$init;
		}


		static function hooks_wp_head(){
			if( critical_css::get_styles()->is_cache_exists() ){
				if( trim( self::get_styles()->get_cache() ) != '' ){
					?>
					<!--Critical CSS-->
					<style type="text/css"><?= self::get_styles()->get_cache() ?></style>
					<script data-critical-defer-scripts>var hiweb_theme_critical_defer_styles = <?= json_encode( array_keys( self::get_template()->get_style_paths() ) ) ?></script>
					<?php
				}
			} else {
				//pagesCache::force_stop_make_cache( 'critical css is created process...' );//TODO
				?>
				<script>var hiweb_theme_current_template_hash_id = "<?=self::get_current_theme_template_id()?>";</script><?php
			}
			frontend::js( __DIR__ . '/critical_css.min.js', frontend::jquery() );
		}


		/**
		 * @return string
		 */
		static function get_cache_dir(){
			return WP_CONTENT_DIR . '/cache/hiweb-theme-tools-critical-css';
		}


		/**
		 * @param string $template
		 * @return string
		 */
		static function _set_current_theme_template_file( $template = '/index.php' ){
			self::$current_theme_template_file = $template;
			return $template;
		}


		/**
		 * Return current template php file id
		 * Warning! Function must call only after hook 'template_include'
		 * @param bool $return_md5hash - return md5 hash php file name
		 * @return string
		 */
		static function get_current_theme_template_id( $return_md5hash = true ){
			//if(!did_action('template_include')) console_warn('Внимание! Метод ['.__METHOD__.'] вызван раньше хука [template_include]');
			return $return_md5hash ? md5( self::$current_theme_template_file ) : self::$current_theme_template_file;
		}


		/**
		 * @param null $template_file_path
		 * @return template
		 */
		static function get_template( $template_file_path = null ){
			if( !is_string( $template_file_path ) || $template_file_path == '' ){
				$template_file_path = self::$current_theme_template_file;
			}
			if( !array_key_exists( $template_file_path, self::$template_styles ) ){
				self::$template_styles[ $template_file_path ] = new template( $template_file_path );
			}
			return self::$template_styles[ $template_file_path ];
		}


		/**
		 * add_action( 'shutdown', '\hiweb_theme\tools\criticalCss::hook_shutdown', 999999999 );
		 */
		static function hook_shutdown(){
			if( context::is_frontend_page() && self::get_current_theme_template_id() != '' ){
				$current_template = self::get_template();
				if( $current_template->is_cache_exists() ){
					//do nothing
				} else {
					global $wp_styles;
					$R = [];
					if( is_array( $wp_styles->done ) ){
						foreach( $wp_styles->done as $style_id ){
							if( array_key_exists( $style_id, $wp_styles->registered ) ){
								$R[ $style_id ] = $wp_styles->registered[ $style_id ]->src;
							}
						}
					}
					critical_css::get_template()->set_files( $R );
				}
			}
		}


		/**
		 * @param null $template_file_hash
		 * @return styles
		 */
		static function get_styles( $template_file_hash = null ){
			if( !is_string( $template_file_hash ) || $template_file_hash == '' ){
				$template_file_hash = self::get_current_theme_template_id();
			}
			if( !array_key_exists( $template_file_hash, self::$styles ) ){
				self::$styles[ $template_file_hash ] = new styles( $template_file_hash );
			}
			return self::$styles[ $template_file_hash ];
		}


		static function hook_make_ccss(){
			return self::get_styles( $_POST['hash'] )->get_critical_css( stripslashes( $_POST['chtml'] ) );
		}

	}