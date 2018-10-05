<?php

	if( \hiweb_theme\footer::$use_wp_footer ) wp_footer();

	get_template_part( HIWEB_THEME_PARTS.'/body/sufix' );