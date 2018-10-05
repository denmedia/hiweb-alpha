<!DOCTYPE html>
<html>
<head>
	<?php get_template_part( HIWEB_THEME_PARTS.'/head/meta_viewport' ); ?>
	<title><?= wp_title() ?></title>

	<?php
		if( is_array( hiweb_theme\head::$favicon_png_context ) ){
			$favicon_png_x512 = get_image( get_field( hiweb_theme\head::$favicon_png_context[0], hiweb_theme\head::$favicon_png_context[1] ) );
			if( $favicon_png_x512->is_attachment_exists() ){
				?>
				<link rel="icon" type="image/png" href="<?= $favicon_png_x512->get_original_src() ?>"/>
				<link rel='apple-touch-icon' type='image/png' href='<?= $favicon_png_x512->get_src( [ 57, 57 ], 0 ) ?>'> <!-- iPhone -->
				<link rel='apple-touch-icon' type='image/png' sizes='72x72' href='<?= $favicon_png_x512->get_src( [ 72, 72 ], 0 ) ?>'> <!-- iPad -->
				<link rel='apple-touch-icon' type='image/png' sizes='114x114' href='<?= $favicon_png_x512->get_src( [ 114, 114 ], 0 ) ?>'> <!-- iPhone4 -->
				<?php
			} else {
				?>
				<link rel="icon" type="image/png" href="<?= \hiweb_theme\head::$default_favicon_url ?>"/>
				<?php
			}
		}

		if( is_array( hiweb_theme\head::$favicon_ico_context ) ){
			$favicon_ico = hiweb\file( get_field( hiweb_theme\head::$favicon_ico_context[0], hiweb_theme\head::$favicon_ico_context[1] ) );
			if( $favicon_png_x512->is_attachment_exists() ){
				?>
				<link rel="shortcut icon" type="image/x-icon" href="<?= $favicon_ico->url ?>"/>
				<?php
			}
		}
	?>
	<?php if( \hiweb_theme\head::$use_wp_head ) wp_head() ?>
</head>