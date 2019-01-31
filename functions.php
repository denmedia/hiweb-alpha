<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 19:43
	 */

	require_once __DIR__ . '/hiweb-core-3/hiweb-core-3.php';
	require_once __DIR__ . '/include/defines.php';
	require_once __DIR__ . '/include/autoload.php';

	hiweb\paths::get( __DIR__ . '/theme' )->include_files_by_name( [ 'init.php', 'functions.php' ] );

	theme\migration::init();
	theme\cyr3lat::init();
	theme\html_layout::init();
	theme\error_404::init();
	theme\forms::init();

	theme\includes\admin::css( HIWEB_THEME_ASSETS_DIR . '/css/admin.css' );