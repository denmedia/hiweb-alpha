<?php

	use hiweb_theme\widgets\bootstrap;


	$header = hiweb_theme::header();

	$wrap = bootstrap::wrap( 'test' );
	$wrap->add_class( 'py-2' );

	$row = $wrap->add_row();
	$row->ALIGN_ITEMS()->stretch();

	$col = $row->add_col();
	$col->content( $header->get_logotype() );

	$col = $row->add_col();
	$col->fill();
	$col->add_class( 'd-none d-lg-block' );
	$col->content( $header->get_navigate() );

	$col = $row->add_col();
	$col->fill();
	$col->add_class( 'd-block d-lg-none text-left order-first' );
	$col->content( $header->get_burger_button() );

?>
<header <?= $header->get_class() ?> <?= $header->get_tags() ?>>
	<?php $wrap->the() ?>
</header>
