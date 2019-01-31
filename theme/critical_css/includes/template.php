<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 24/11/2018
	 * Time: 15:54
	 */

	namespace theme\critical_css\includes;


	use hiweb\cache;
	use hiweb\files;


	class template{

		private $template_file_path;
		private $style_paths;
		private $template_filemtime;
		private $cache_data;
		private $cache_group = 'hiweb_theme_tools_criticalCss_template_styles';


		public function __construct( $template_file_path ){
			$this->template_file_path = $template_file_path;
			$this->template_filemtime = filemtime( $template_file_path );
		}


		/**
		 * Return true if template php file is exists
		 * @return bool
		 */
		public function is_template_exists(){
			return is_file( $this->template_file_path ) && is_readable( $this->template_file_path );
		}


		/**
		 * @return mixed
		 */
		public function get_template_file_path(){
			return $this->template_file_path;
		}


		/**
		 * Set style file paths
		 * @param array $style_paths
		 */
		public function set_files( $style_paths ){
			$style_paths_filtered = [];
			if( is_array( $style_paths ) ) foreach( $style_paths as $path ){
				$style_file = files::get( $path );
				if( $style_file->extension() == 'css' && $style_file->is_readable() ){
					$style_paths_filtered[] = [ 'path' => $style_file->get_path(), 'filemtime' => filemtime( $style_file->get_path() ), 'url' => $style_file->get_url() ];
				}
			}
			$cache_data = [ 'id' => $this->get_id(), 'template_file_path' => $this->template_file_path, 'filemtime' => $this->template_filemtime, 'styles' => $style_paths_filtered ];
			cache::set( $this->get_id(), $cache_data, $this->cache_group );
		}


		/**
		 * @return string
		 */
		public function get_id(){
			return md5( $this->template_file_path );
		}


		/**
		 * @return bool|null
		 */
		public function is_cache_exists(){
			return cache::is_exists( $this->get_id(), true, $this->cache_group );
		}


		/**
		 * @return array|mixed
		 */
		public function get_cache_data(){
			if( !is_array( $this->cache_data ) ){
				$this->cache_data = [];
				if( $this->is_cache_exists() ){
					$this->cache_data = cache::get( $this->get_id(), $this->cache_group );
				}
			}
			return $this->cache_data;
		}


		/**
		 * @return array|mixed
		 */
		public function get_cached_files(){
			$cache_data = $this->get_cache_data();
			if( array_key_exists( 'styles', $cache_data ) && is_array( $cache_data['styles'] ) ) return $cache_data['styles'];
			return [];
		}


		/**
		 * @return array
		 */
		public function get_style_paths(){
			if( !is_array( $this->style_paths ) ){
				$this->style_paths = [];
				if( $this->is_cache_exists() ){
					foreach( $this->get_cached_files() as $file_data ){
						$this->style_paths[ $file_data['url'] ] = $file_data['path'];
					}
				}
			}
			return $this->style_paths;
		}


	}