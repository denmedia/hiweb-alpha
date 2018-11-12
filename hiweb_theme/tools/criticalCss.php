<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 31/10/2018
	 * Time: 22:56
	 */

	namespace hiweb_theme\tools;


	use hiweb\arrays;
	use hiweb\cache;
	use hiweb\files\file;
	use hiweb\path;
	use hiweb_theme\includes;


	class criticalCss{

		/** @var array */
		static private $css_files;
		static private $css_merge_content;
		/** @var bool */
		static private $is_cache_exists;
		static private $cache_dir;
		static $current_theme_file = 'index.php';
		static private $cache_styles_group = 'hiweb-theme-template-css';


		static function init( $cache_dir = null ){
			if( !is_string( $cache_dir ) ){
				$cache_dir = get_stylesheet_directory() . '/critical-css';
			}
			self::$cache_dir = $cache_dir;
			mkdir( self::$cache_dir );
			///save current file
			add_filter( 'template_include', function( $template ){
				self::$current_theme_file = $template;
				return $template;
			} );
			///
			add_action( 'wp_head', function(){
				if( self::is_cache_exists() ){
					?>
					<!--Critical CSS-->
					<style><?= file_get_contents( self::$cache_dir . '/' . self::get_current_template_hash_id() . '.css' ) ?></style>
					<?php
				} else {
					?>
					<script>
                        var hiweb_theme_current_template_hash_id = "<?=self::get_current_template_hash_id()?>";
                        var hiweb_theme_critical_offset = 1000;
					</script>
					<?php
					includes::defer_script_file( 'critical' );
				}
			} );
			///
			add_action( 'wp_footer', function(){
				if( !self::is_cache_exists() ){
					cache::set( self::get_current_template_hash_id(), [ self::get_css_hash_id(), self::get_current_files( true ) ], self::$cache_styles_group );
				}
			}, 999999999 );
		}


		/**
		 * @param string $css
		 * @return array
		 */
		static function parseCss( $css ){
			preg_match_all( '/(?ims)([a-z0-9\s\.\:#_\-@,\*]+)\{([^\}]*)\}/', $css, $arr );
			$result = [];
			foreach( $arr[0] as $i => $x ){
				$selector = trim( $arr[1][ $i ] );
				$rules = explode( ';', trim( $arr[2][ $i ] ) );
				$rules_arr = [];
				foreach( $rules as $strRule ){
					if( !empty( $strRule ) ){
						$rule = explode( ":", $strRule );
						$rules_arr[ trim( $rule[0] ) ] = trim( $rule[1] );
					}
				}

				$selectors = explode( ',', trim( $selector ) );
				foreach( $selectors as $strSel ){
					$result[ trim( $strSel ) ] = $rules_arr;
				}
			}
			return $result;
		}


		/**
		 * @return bool
		 */
		static function is_cache_exists(){
			if( !is_bool( self::$is_cache_exists ) ){
				self::$is_cache_exists = file_exists( self::$cache_dir . '/' . self::get_current_template_hash_id() . '.css' );
			}
			return self::$is_cache_exists;
		}


		/**
		 * @param bool $return_only_path
		 * @return \hiweb\files\file[]|string[]
		 */
		static function get_current_files( $return_only_path = false ){
			if( !is_array( self::$css_files ) ){
				self::$css_files = [];
				if( is_array( wp_styles()->done ) ) foreach( wp_styles()->done as $style_id ){
					$style_src = wp_styles()->registered[ $style_id ]->src;
					$style_path = \hiweb\path::url_to_path( $style_src );
					$style_file = \hiweb\file( $style_path );
					if( $style_file->is_exists_and_readable() ){
						self::$css_files[ $style_src ] = $return_only_path ? $style_file->path : $style_file->path;
					}
				}
			}
			return self::$css_files;
		}


		/**
		 * @param bool $use_cached_files
		 * @param null $template_hash_id
		 * @return string
		 */
		static function get_css_merge_content( $use_cached_files = true, $template_hash_id = null ){
			if( !is_string( $template_hash_id ) ){
				$template_hash_id = self::get_current_template_hash_id();
			}
			if( $use_cached_files ){
				$files = [];
				$file_paths = arrays::get_value_by_key( cache::get( $template_hash_id, self::$cache_styles_group ), 1 );
				if( is_array( $file_paths ) ) foreach( $file_paths as $path ){
					$file = \hiweb\file( $path );
					if( $file instanceof file && $file->is_exists_and_readable() ){
						$files[] = $file;
					}
				}
			} else {
				$files = self::get_current_files();
			}
			///
			if( !is_string( self::$css_merge_content ) ){
				foreach( $files as $style_file ){
					if( $style_file instanceof file ) self::$css_merge_content .= $style_file->get_content();
				}
			}
			return self::$css_merge_content;
		}


		/**
		 * @param $template_hash_id
		 * @param $selectors
		 * @return array|string
		 */
		static function make_critical_cache_file( $template_hash_id, $selectors ){
			$full_css = self::get_css_merge_content( true, $template_hash_id );
			if( trim( $full_css ) == '' ) return '';
			///
			$parseCss = self::parseCss( $full_css );
			if( count( $parseCss ) == 0 ) return '';
			///
			$criticalCss = [];
			foreach( $selectors as $selector ){
				if( array_key_exists( $selector, $parseCss ) ){
					$style_block = [];
					foreach( $parseCss[ $selector ] as $key => $val ){
						$style_block[] = $key . ':' . $val;
					}
					$criticalCss[] = $selector . '{' . implode( ';', $style_block ) . '}';
				}
			}
			$criticalCss = implode( '', $criticalCss );
			///
			file_put_contents( self::$cache_dir . '/' . $template_hash_id . '.css', $criticalCss );
			return $criticalCss;
		}


		/**
		 * @return string
		 */
		static function get_css_hash_id(){
			$R = [];
			foreach( self::get_current_files() as $style_file ){
				$R[] = $style_file->path . '-' . $style_file->filemtime;
			}
			return md5( implode( '|', $R ) );
		}


		/**
		 * Warning! Function must call only after hook 'template_include'
		 * @return string
		 */
		static function get_current_template_hash_id(){
			//if(!did_action('template_include')) console_warn('Внимание! Метод ['.__METHOD__.'] вызван раньше хука [template_include]');
			return md5( self::$current_theme_file );
		}

	}