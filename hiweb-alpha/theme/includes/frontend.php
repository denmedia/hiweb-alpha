<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 01:31
	 */


	namespace theme\includes;


	use hiweb\context;
	use hiweb\js\file;


	class frontend extends includes{

		static $use_wp_block_library = false;
		static $use_wp_jquery_core = false;

		/**
		 * @param       $filePathOrUrl
		 * @param bool  $in_footer
		 * @param array $deeps
		 * @return bool|\hiweb\css\file
		 */
		static function css( $filePathOrUrl, $in_footer = false, $deeps = [] ){
			if( !context::is_frontend_page() )
				return false;
			return parent::css( $filePathOrUrl, $in_footer, $deeps );
		}


		/**
		 * @param       $jsPathOrURL
		 * @param array $deeps
		 * @param bool  $inFooter
		 * @return bool|file|null
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			if( !context::is_frontend_page() )
				return false;
			return parent::js( $jsPathOrURL, $deeps, $inFooter );
		}


		static function wp_block_library(){
			self::$use_wp_block_library = true;
		}

	}