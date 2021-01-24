<?php

require_once __DIR__ . '/hiweb-core-4/hiweb-core-4.php';
require_once __DIR__ . '/defines.php';
require_once __DIR__ . '/autoload.php';
///Init Components
get_file(HIWEB_THEME_COMPONENTS_DIR)->include_files_by_name([ 'global_functions.php' ], 1);
get_file(HIWEB_THEME_COMPONENTS_DIR)->include_files_by_name([ 'init.php' ], 1);

///Include Admin CSS
include_admin_css(HIWEB_THEME_ASSETS_DIR . '/css/admin.css');