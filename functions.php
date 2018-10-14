<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04.10.2018
	 * Time: 19:43
	 */

	require_once __DIR__ . '/hiweb-core-3/hiweb-core-3.php';

	if( !defined( 'HIWEB_THEME_DIR' ) ) define( 'HIWEB_THEME_DIR', __DIR__ );
	if( !defined( 'HIWEB_THEME_URL' ) ) define( 'HIWEB_THEME_URL', \hiweb\path::path_to_url( HIWEB_THEME_DIR ) );
	if( !defined( 'HIWEB_THEME_ASSETS_DIR' ) ) define( 'HIWEB_THEME_ASSETS_DIR', __DIR__ . '/assets' );
	if( !defined( 'HIWEB_THEME_ASSETS_URL' ) ) define( 'HIWEB_THEME_ASSETS_URL', \hiweb\path::path_to_url( HIWEB_THEME_ASSETS_DIR ) );
	if( !defined( 'HIWEB_THEME_VENDORS_DIR' ) ) define( 'HIWEB_THEME_VENDORS_DIR', __DIR__ . '/vendors' );
	if( !defined( 'HIWEB_THEME_VENDORS_URL' ) ) define( 'HIWEB_THEME_VENDORS_URL', \hiweb\path::path_to_url( HIWEB_THEME_VENDORS_DIR ) );
	if( !defined( 'HIWEB_THEME_INCLUDE_DIR' ) ) define( 'HIWEB_THEME_INCLUDE_DIR', HIWEB_THEME_DIR . '/hiweb_theme' );
	if( !defined( 'HIWEB_THEME_MODULES_DIR' ) ) define( 'HIWEB_THEME_MODULES_DIR', HIWEB_THEME_DIR . '/modules' );
	if( !defined( 'HIWEB_THEME_PARTS' ) ) define( 'HIWEB_THEME_PARTS', 'parts' );

	\hiweb\path::include_dir( HIWEB_THEME_INCLUDE_DIR, [ 'php' ] );

	require_once HIWEB_THEME_INCLUDE_DIR . '/-hooks.php';