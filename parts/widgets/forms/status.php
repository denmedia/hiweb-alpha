<div class="hiweb-theme-widget-form-status-wrap" data-form-status-id="<?= get_the_form_id() ?>">
	<div class="hiweb-theme-widget-form-status">
		<div class="icon wait">
			<i class="<?= get_the_form()->get_status_icon( 'process' ) ?>"></i>
		</div>
		<div class="icon success">
			<i class="<?= get_the_form()->get_status_icon( 'success' ) ?>"></i>
		</div>
		<div class="icon warn">
			<i class="<?= get_the_form()->get_status_icon( 'warn' ) ?>"></i>
		</div>
		<div class="icon error">
			<i class="<?= get_the_form()->get_status_icon( 'error' ) ?>"></i>
		</div>
		<div class="message">
			<?= get_the_form()->get_status_message( 'process' ) ?>
		</div>
		<div class="status-close">
			<button data-form-status-close>OK</button>
		</div>
	</div>
</div>