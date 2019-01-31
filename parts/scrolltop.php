<?php

	use theme\scrolltop;


	$icon_class = get_field( 'icon', scrolltop::$admin_menu_slug );
	$scroll_speed = (int)get_field( 'scroll-speed', scrolltop::$admin_menu_slug );
	$fade_speed = (int)get_field( 'fade-speed', scrolltop::$admin_menu_slug );
	$scroll_offset = (int)get_field( 'scroll-offset', scrolltop::$admin_menu_slug );
	$target_selector = get_field( 'target_selector', scrolltop::$admin_menu_slug );
?>
<div class="<?= scrolltop::get_class() ?>" data-scroll-speed="<?= $scroll_speed ?>" data-fade-speed="<?= $fade_speed ?>" data-scroll-offset="<?= $scroll_offset ?>" data-target="<?= $target_selector ?>">
	<span class="<?= $icon_class ?>"></span>
</div>