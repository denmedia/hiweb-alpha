<form id="hiweb-theme-widgets-form-<?= get_the_form()->get_object_id() ?>" class="hiweb-theme-widget-form" data-form-id="<?= get_the_form_id() ?>" data-form-object-id="<?= get_the_form()->get_object_id() ?>" action="<?= get_the_form()->get_action_url() ?>" method="<?= get_the_form()->get_method() ?>">
	<div class="hiweb-theme-widget-form-inside">
		<input type="hidden" name="hiweb-theme-widget-form-id" value="<?= get_the_form_id() ?>">
		<?php
			if( have_form_inputs() ){
				while( have_form_inputs() ){
					the_form_input();
					the_form_input_html();
				}
			}
		?>
		<?php \hiweb_theme\widgets\forms\recaptcha::the_input() ?>
	</div>
	<?php get_template_part( HIWEB_THEME_PARTS . '/widgets/forms/status' ); ?>
</form>