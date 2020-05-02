<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 19:43
	 */
	
	use hiweb\components\Post_Duplicator\Post_Duplicator;
	
	
	require_once __DIR__ . '/hiweb-core-4/hiweb-core-4.php';
	require_once __DIR__ . '/include/defines.php';
	require_once __DIR__ . '/include/autoload.php';
	
	get_path( __DIR__ . '/theme' )->File()->include_files_by_name( [ 'init.php', 'functions.php' ] );
	
	theme\migration::init();
	theme\cyr3lat::init();
	theme\html_layout::init();
	theme\error_404::init();
	theme\pwa::init();
	Post_Duplicator::init();
	theme\forms::init();
	theme\recaptcha::init();
	
	\hiweb\components\Includes\IncludesFactory_AdminPage::css( HIWEB_THEME_ASSETS_DIR . '/css/admin.css' );
	//theme\includes\admin::css( HIWEB_THEME_ASSETS_DIR . '/css/admin.css' );
