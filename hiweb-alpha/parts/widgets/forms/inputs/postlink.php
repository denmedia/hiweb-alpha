<?php

	$input = get_the_form()->get_the_input();

	$input->the_prefix();
	if( $input->get_data( 'show' ) ){
		$wp_post = get_post( get_the_ID() );
		if( $wp_post instanceof \WP_Post ){

			?>
			<div class="post-thumbnail"><?= get_image( get_post_thumbnail_id( $wp_post ) )->html( [ 200, 200 ] ) ?></div>
			<div class="post-title"><?= $wp_post->post_title ?></div>
			<?php
		}
	}
?><input tabindex="" type="hidden" name="<?= $input->get_name() ?>" value="<?= get_the_ID() ?>"/>
<?php
	$input->the_sufix();