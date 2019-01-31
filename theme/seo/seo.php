<?php
	/**
	 * Created by PhpStorm.
	 * User: denisivanin
	 * Date: 2019-01-21
	 * Time: 11:18
	 */

	namespace theme;


	class seo{

		static $admin_menu_main = 'hiweb-seo-main';
		static $admin_menu_main_parent = 'options-general.php';
		/** @var \hiweb\admin\pages\page */
		static $admin_menu_main_page;

		static function init(){
			require_once __DIR__.'/options.php';
			require_once __DIR__.'/hooks.php';
			require_once __DIR__.'/post-type-meta.php';
		}

	}