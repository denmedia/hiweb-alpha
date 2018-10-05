<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 9:22
	 */

	namespace hiweb_theme\modules;


	use hiweb_theme\includes;


	class hamburgers{

		private $link = '#mobile-menu';

		public function __construct($button_link = '#mobile-menu'){
			includes::css( 'hamburgers/hamburgers.min.css' );
			includes::js( 'vendors/hamburgers/hamburders.min.js' );
			$this->link = $button_link;
		}


		public function the(){
			global $hiweb_theme_module_hamburgers_link;
			$hiweb_theme_module_hamburgers_link = $this->link;
			get_template_part( HIWEB_THEME_PARTS . '/modules/hamburgers/button' );
		}

	}