<form id="hiweb-theme-widgets-form-<?= \hiweb\strings::rand() ?>" class="hiweb-theme-widget-form" data-form-id="<?= get_the_form_id() ?>" action="<?= get_the_form()->get_action_url() ?>" method="<?= get_the_form()->get_method() ?>">
	<?php
		if( have_form_inputs() ){
			while( have_form_inputs() ){
				the_form_input();
				the_form_input_html();
			}
		}
	?>
</form>
<?php

	get_template_part( HIWEB_THEME_PARTS . '/widgets/forms/status' );