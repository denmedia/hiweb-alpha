<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/11/2018
	 * Time: 15:55
	 */

	namespace theme\includes\critical_css;


	use hiweb\cache;
	use hiweb\paths;
	use hiweb\strings;
	use theme\critical_css;


	class styles{

		private $template_hash;
		private $cache_group = 'hiweb_theme_tools_criticalCss_template_styles';
		private $merge_content;


		/**
		 * styles constructor.
		 * @param $template_hash
		 */
		public function __construct( $template_hash ){
			$this->template_hash = $template_hash;
		}


		/**
		 * @return array|mixed
		 */
		public function get_template_cache_data(){
			return cache::get( $this->template_hash, $this->cache_group );
		}


		/**
		 * @return template
		 */
		public function get_template(){
			$cache_data = $this->get_template_cache_data();
			return critical_css::get_template( $cache_data['template_file_path'] );
		}


		/**
		 * @param        $css_string
		 * @return mixed
		 */
		public function parseCss( $css_string ){
			preg_match_all( '/(?<pattern>[a-z0-9\s\.\:#_\-@,>=\[\"\\\'\^\+~\]\*\(\)]+)\{[^\}]+\}\s*\}?/im', $css_string, $matches );
			$css_string = implode( '', $matches[0] );
			$lines = [];
			$selector_name = '';
			$selector_content = '';
			$level = 0;
			for( $n = 0; $n < strlen( $css_string ); $n ++ ){
				$symb = $css_string[ $n ];
				if( $symb == '{' ) $level ++; elseif( $symb == '}' ) {
					$level --;
					if( $level == 0 ){
						$lines[ trim( $selector_name ) ][] = trim( $selector_content ) . '}';
						$selector_name = '';
						$selector_content = '';
					}
				} else {
					if( $level == 0 ){
						$selector_name .= $symb;
					}
				}
				if( $level != 0 ) $selector_content .= $symb;
			}
			$R = [];
			foreach( $lines as $selector => $rules ){
				foreach( $rules as $content ){
					if( strpos( $selector, '@' ) === 0 ){
						$sub_selectors = current( self::parseCss( trim( $content, '{}' ) . '}' ) );
						foreach( $sub_selectors as $sub_selector => $sub_properties ){
							if( array_key_exists( $selector, $R ) && array_key_exists( $sub_selector, $R[ $selector ] ) ){
								$R[ $selector ][ $sub_selector ] = array_merge( $R[ $selector ][ $sub_selector ], $sub_properties );
							} else {
								$R[ $selector ][ $sub_selector ] = $sub_properties;
							}
						}
					} else {
						$content_explode = explode( ';', trim( $content, '{}' ) );
						foreach( $content_explode as $property ){
							$property_explode = explode( ':', trim( $property ) );
							if( count( $property_explode ) == 2 ){
								$R[''][ $selector ][ trim( $property_explode[0] ) ] = trim( $property_explode[1] );
							}
						}
					}
				}
			}
			return $R;
		}


		/**
		 * Return merged content of style files
		 * @return string
		 */
		public function get_merge_styles_content(){
			if( !is_string( $this->merge_content ) ){
				$this->merge_content = '';
				$files = $this->get_template()->get_style_paths();
				foreach( $files as $url => $path ){
					if( strpos( '/wp-includes/', $path ) === false && file_exists( $path ) && is_file( $path ) && is_readable( $path ) ){
						$this->merge_content .= file_get_contents( $path );
					}
				}
			}
			return $this->merge_content;
		}


		/**
		 * Make critical CSS FROM Critical HTML
		 * @param string $cHTML
		 * @return string
		 */
		public function get_critical_css( $cHTML ){
			if( $this->is_cache_exists() ){
				return $this->get_cache();
			} else {
				$source_style = $this->get_merge_styles_content();
				$source_style_parse = $this->parseCss( $source_style );
				$cCSS = $this->get_cCSS_from_cHTML( $cHTML, $source_style_parse, $finded_styles );
				$this->set_cache( $cCSS, '.css' );
				$this->set_cache( $cHTML, '.chtml.html' );
				return $cCSS;
			}
		}


		/**
		 * @param string $cHTML
		 * @param array  $parseCSS
		 * @param array  $parse_finded_css
		 * @return string
		 */
		private function get_cCSS_from_cHTML( $cHTML, $parseCSS = [], &$parse_finded_css = [] ){
			if( !is_array( $parseCSS ) || count( $parseCSS ) == 0 ) return '';
			///
			require_once HIWEB_DIR_VENDORS . '/phpQuery.php';
			///
			$R = '';
			$html = \phpQuery::newDocumentHTML( $cHTML );
			foreach( $parseCSS as $media_selector => $selectors ){
				foreach( $selectors as $selector => $properties ){
					if( trim( $selector ) == '' || count( $properties ) == 0 ) continue;
					if( pq( $selector )->length > 0 ){
						if( $media_selector != '' ){
							$R .= $media_selector . '{';
						}
						$R .= $selector . '{';
						foreach( $properties as $key => $val ){
							$R .= $key . ':' . $val . ';';
						}
						$R .= '}';
						if( $media_selector != '' ){
							$R .= '}';
						}
					}
				}
			}
			return $R;
		}


		/**
		 * @param string $sufix
		 * @return string
		 */
		public function get_cache_file( $sufix = '.css' ){
			return criticalCss::get_cache_dir() . '/' . strings::sanitize_id( ltrim( paths::get( $this->get_template()->get_template_file_path() )->get_path_relative(), '/' ) ) . $sufix;
		}


		/**
		 * @param string $file_sufix
		 * @return bool
		 */
		public function is_cache_exists( $file_sufix = '.css' ){
			$cache_file = $this->get_cache_file( $file_sufix );
			return file_exists( $cache_file ) && is_file( $cache_file ) && is_readable( $cache_file );
		}


		/**
		 * @param        $content
		 * @param string $file_sufix
		 * @return bool|int
		 */
		public function set_cache( $content, $file_sufix = '.css' ){
			return file_put_contents( $this->get_cache_file( $file_sufix ), $content );
		}


		/**
		 * @param string $file_sufix
		 * @return bool|string
		 */
		public function get_cache( $file_sufix = '.css' ){
			if( !$this->is_cache_exists( $file_sufix ) ) return '';
			return file_get_contents( $this->get_cache_file( $file_sufix ) );
		}

	}