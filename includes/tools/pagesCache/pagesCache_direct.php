<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 06/12/2018
	 * Time: 13:38
	 */

	namespace hiweb_theme\tools\pagesCache;


	/**
	 * Класс для прямой работы с кэшем из index-hiweb-cache.php
	 * Class direct_index
	 * @package hiweb_theme\tools\pagesCache
	 */
	class pagesCache_direct{

		private static $current_cache;
		private static $cache_start_query = false;


		/**
		 * @return cache_item
		 */
		static function get_current_cache(){
			if( !self::$current_cache instanceof cache_item ){
				self::$current_cache = new cache_item( tools::get_request_uri() );
			}
			return self::$current_cache;
		}

		/**
		 * @return bool
		 */
		static function get_cache_start_query(){
			return self::$cache_start_query;
		}


		static function the_start(){
			require_once __DIR__ . '/tools.php';
			require_once __DIR__ . '/options.php';
			require_once __DIR__ . '/indexInject.php';
			require_once __DIR__ . '/cache_item.php';
			//
			if( self::get_current_cache()->is_allowed_for_create() || (isset($_GET['nocache']) && $_GET['nocache'] == '1') ){
				self::$cache_start_query = true;
				ob_start();
			} elseif( self::get_current_cache()->is_allowed_for_use() ) {
				?><!--hiWeb Pages Cache: current cache start, time left: <?= round(self::get_current_cache()->get_left_time() / 60,  1) ?>min, use '?nocache' for force disable cache--><?= self::get_current_cache()->get_content(); ?><!--hiWeb Pages Cache: current cache end--><?php
				///
				die;
			}
		}


		static function the_end(){

			if( self::$cache_start_query){
				$R = ob_get_clean();
				echo $R;
				?><!--\hiweb_theme\tools\pagesCache\pagesCache_direct::the_end--><?php
				if(trim($R) != '' && (options::is_allow_url( tools::get_request_uri() ) || (isset($_GET['nocache']) && $_GET['nocache'] == '1')) && http_response_code() == 200 ){
					$B = self::get_current_cache()->make_from_content( $R );
					if( $B === true || $B > 0 ){
						?>
						<script>console.info('<?=addslashes(__METHOD__)?>: cache is created.')</script><?php
					} else {
						?>
						<script>console.error('<?=addslashes(__METHOD__)?>: error while cache is created [<?=self::get_current_cache()->get_file_path()?> - strlen([<?=strlen($R)?>]) - <?=$B?>]')</script><?php
					}
				}
			}

		}


	}