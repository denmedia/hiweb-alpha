<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-20
	 * Time: 01:34
	 */

	namespace theme\includes;


	use hiweb\context;


	class admin extends includes{

		/**
		 * @param       $filePathOrUrl
		 * @param bool  $in_footer
		 * @param array $deeps
		 * @return bool|\hiweb\css\file
		 */
		static function css( $filePathOrUrl, $in_footer = false, $deeps = [] ){
			if( !context::is_admin_page() ) return false;
			return parent::css( $filePathOrUrl, $in_footer, $deeps );
		}


		/**
		 * @param       $jsPathOrURL
		 * @param array $deeps
		 * @param bool  $inFooter
		 * @return bool|\hiweb\js\file|null
		 */
		static function js( $jsPathOrURL, $deeps = [], $inFooter = true ){
			if( !context::is_admin_page() ) return false;
			return parent::js( $jsPathOrURL, $deeps, $inFooter );
		}


		static function jquery( $include_migrate_js = false ){
			wp_enqueue_script( 'jquery-core' );
		}


	}