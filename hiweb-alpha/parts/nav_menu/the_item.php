<?php

	use hiweb\urls;
	use theme\structures;


	$active = structures::get()->has_object( theme\nav_menu::the_item() ) || urls::get()->is_dirs_intersect( theme\nav_menu::the_item()->url );
?>
<li class="<?= implode( ' ', theme\nav_menu::the_instance()->item_classes ) ?><?= $active ? ' ' . theme\nav_menu::the_instance()->item_class_active : '' ?>">
	<?php
		if( theme\nav_menu::the_item()->url == '#' ){
			?>
			<a class="<?= implode( ' ', theme\nav_menu::the_instance()->link_classes ) ?><?= $active ? ' ' . theme\nav_menu::the_instance()->item_class_active : '' ?>"><?= theme\nav_menu::the_item()->title ?></a>
			<?php
		} else {
			?>
			<a class="<?= implode( ' ', theme\nav_menu::the_instance()->link_classes ) ?><?= $active ? ' ' . theme\nav_menu::the_instance()->item_class_active : '' ?>" href="<?= theme\nav_menu::the_item()->url ?>"><?= theme\nav_menu::the_item()->title ?></a>
			<?php
		}
	?>
	<?php theme\nav_menu::the_instance()->the_list( theme\nav_menu::the_item()->ID, theme\nav_menu::the_item_depth() + 1 ); ?>
</li>