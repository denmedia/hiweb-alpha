<div class="hiweb-theme-widget-form-status-wrap" data-form-status-id="<?= get_the_form_id() ?>">
	<div class="hiweb-theme-widget-form-status">
		<div class="icon wait">
			<i class="<?= get_field( 'icon-process', \hiweb_theme\widgets\forms::$options_name ) ?>"></i>
		</div>
		<div class="icon success">
			<i class="<?= get_field( 'icon-success', \hiweb_theme\widgets\forms::$options_name ) ?>"></i>
		</div>
		<div class="icon warn">
			<i class="<?= get_field( 'icon-warn', \hiweb_theme\widgets\forms::$options_name ) ?>"></i>
		</div>
		<div class="icon error">
			<i class="<?= get_field( 'icon-error', \hiweb_theme\widgets\forms::$options_name ) ?>"></i>
		</div>
		<div class="message">
			<?=get_field('text-process', \hiweb_theme\widgets\forms::$options_name)?>
		</div>
		<div class="status-close">
			<button data-form-status-close>OK</button>
		</div>
	</div>
</div>