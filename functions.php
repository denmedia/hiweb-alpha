<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 19:43
	 */

	require_once __DIR__ . '/hiweb-core-3/hiweb-core-3.php';

	if( !defined( 'HIWEB_THEME_DIR' ) ) define( 'HIWEB_THEME_DIR', __DIR__ );
	if( !defined( 'HIWEB_THEME_URL' ) ) define( 'HIWEB_THEME_URL', get_path( HIWEB_THEME_DIR )->get_url() );
	if( !defined( 'HIWEB_THEME_ASSETS_DIR' ) ) define( 'HIWEB_THEME_ASSETS_DIR', __DIR__ . '/assets' );
	if( !defined( 'HIWEB_THEME_ASSETS_URL' ) ) define( 'HIWEB_THEME_ASSETS_URL', get_path( HIWEB_THEME_ASSETS_DIR )->get_url() );
	if( !defined( 'HIWEB_THEME_VENDORS_DIR' ) ) define( 'HIWEB_THEME_VENDORS_DIR', __DIR__ . '/vendors' );
	if( !defined( 'HIWEB_THEME_VENDORS_URL' ) ) define( 'HIWEB_THEME_VENDORS_URL', get_path( HIWEB_THEME_VENDORS_DIR )->get_url() );
	if( !defined( 'HIWEB_THEME_INCLUDE_DIR' ) ) define( 'HIWEB_THEME_INCLUDE_DIR', HIWEB_THEME_DIR . '/includes' );
	if( !defined( 'HIWEB_THEME_MODULES_DIR' ) ) define( 'HIWEB_THEME_MODULES_DIR', HIWEB_THEME_DIR . '/modules' );
	if( !defined( 'HIWEB_THEME_PARTS' ) ) define( 'HIWEB_THEME_PARTS', 'parts' );

	get_path( HIWEB_THEME_INCLUDE_DIR )->include_files( 'php' );

	require_once HIWEB_THEME_INCLUDE_DIR . '/-hooks.php';
	require_once HIWEB_THEME_INCLUDE_DIR . '/-404.php';