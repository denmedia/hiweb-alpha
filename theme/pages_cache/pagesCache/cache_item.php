<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 07/12/2018
	 * Time: 23:26
	 */

	namespace theme\tools\pagesCache;


	use hiweb\urls;
	use hiweb\vendors\php_html_css_js_minifier;


	class cache_item{


		private $uri;
		private $cache_dir_name = 'hiweb-theme-pages-cache';
		private $content;
		private $force_renew = false;
		private $force_disable = false;


		public function __construct( $uri ){
			$this->uri = tools::filter_url( $uri );
		}


		/**
		 * @return bool|string
		 */
		public function get_uri(){
			return $this->uri;
		}


		public function force_disable(){
			$this->force_disable = true;
		}


		/**
		 * @return string
		 */
		protected function get_dir(){
			return tools::base_dir() . '/wp-content/cache/' . $this->cache_dir_name;
		}


		/**
		 * @return string
		 */
		protected function get_current_page_id(){
			$R = tools::sanitize_id( ltrim( $this->uri,'/' ) );
			if( $R == '' )
				$R = '_';
			return $R;
		}


		/**
		 * @return string
		 */
		public function get_file_path(){
			$R = $this->get_dir() . '/' . $this->get_current_page_id() . '.html';
			return $R;
		}


		public function flush(){
			if( $this->is_exists() ){
				return @unlink( $this->get_file_path() );
			}
			return false;
		}


		/**
		 * Возвращает true, если файл кэша существует и читабелен
		 * @return bool
		 */
		public function is_exists(){
			return file_exists( $this->get_file_path() ) && is_file( $this->get_file_path() ) && is_readable( $this->get_file_path() );
		}


		/**
		 * Кэш разрешен для использования. Возвращает TRUE, если кэш включен, URL разрешен и он актуален
		 * @param bool $return_error_no - вместо BOOL возращать номер ошибки: -1 - кэш выключен, -2 - URl не разрешен, -3 - время кэша вышло
		 * @return bool
		 */
		public function is_allowed_use( $return_error_no = false ){
			if( $return_error_no ){
				if( !options::is_enable() )
					return - 1;
				if( !options::is_allow_url( tools::get_request_uri() ) )
					return - 2;
				if( !$this->get_left_time() > 0 )
					return - 3;
				if( isset( $_GET['nocache'] ) || isset( $_POST['nocache'] ) )
					return - 4;
				return true;
			}
			return ( options::is_enable() && options::is_allow_url( tools::get_request_uri() ) && $this->get_left_time() > 0 && !isset( $_GET['nocache'] ) && !isset( $_POST['nocache'] ) );
		}


		/**
		 * @param bool $ignore_expires - игнорировать актуальность
		 * @return bool
		 */
		public function is_allowed_create( $ignore_expires = false ){
			return ( options::is_enable() && ( !$this->is_exists() || $this->get_left_time() < 0 || $this->force_renew || $ignore_expires ) && options::is_allow_url( $this->uri ) );
		}


		/**
		 * Возвращает количество секунд остатка жизни кэша, если он существует. Значение меньше нуля в случае уже не актуального кэша
		 * @return int
		 */
		public function get_left_time(){
			$R = 0;
			if( $this->is_exists() ){
				$filemtime = intval( filemtime( $this->get_file_path() ) );
				$current_time = microtime( true );
				$left_to_life = intval( options::get( 'life-time', 3600 ) );
				$R = ( $filemtime + $left_to_life ) - $current_time;
			}
			return intval( $R );
		}


		/**
		 * Создать кэш
		 * @param string $content
		 * @param bool   $force_create - создать принудительнокэш, даже если файл существует
		 * @return bool|int
		 */
		public function make_from_content( $content = '', $force_create = true ){
			if( $this->force_disable )
				return 0;
			if( trim( $content ) == '' )
				return - 1;
			///minify html
			$content = php_html_css_js_minifier::minify_html( $content );
			///
			if( $this->is_exists() && !$force_create )
				return - 2;
			if( $this->is_exists() && $force_create ){
				$this->flush();
			}
			///Make cache dir
			if( !file_exists( $this->get_dir() ) ){
				mkdir( $this->get_dir(), 0755, true );
			}
			///
			queue::add_url( $this->uri, - 1 );
			return file_put_contents( $this->get_file_path(), $content );
		}


		/**
		 * Update Page Cache
		 * @param bool $ignore_expires
		 * @return bool|int
		 */
		public function make( $ignore_expires = false ){
			if( !$this->is_allowed_create( $ignore_expires ) )
				return - 1;
			if( $this->get_left_time() < 1 || $ignore_expires ){
				$url = urls::get( $this->uri );
				$this->flush();
				$response = wp_remote_get( $url->get( false ) );
				if( array_key_exists( 'body', $response ) ){
					return $this->make_from_content( $response['body'], true );
				} else {
					queue::remove_url( $this->uri );
				}
			}
			return - 2;
		}


		/**
		 * @return string
		 */
		public function get_content(){
			if( !is_string( $this->content ) ){
				$this->content = '';
				if( $this->is_exists() ){
					$content = file_get_contents( $this->get_file_path() );
					if( is_string( $content ) )
						$this->content = $content;
				}
			}
			return $this->content;
		}


	}