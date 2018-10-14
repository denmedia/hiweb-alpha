<a href="#hiweb-theme-widgets-form-<?= get_the_form()->get_object_id() ?>" class="<?= get_the_form()->the_fancybox_button_classes() ?>" data-fancybox data-touch="false" data-widget-form-modal-open><?= get_the_form()->the_fancybox_button_html() ?></a>
<div class="hiweb-theme-widget-form-modal-wrap" style="display: none;">
	<div class="hiweb-theme-widget-form-modal" id="hiweb-theme-widgets-form-<?= get_the_form()->get_object_id() ?>" data-form-id="<?= get_the_form()->get_id() ?>" data-form-object-id="<?= get_the_form()->get_object_id() ?>">
		<?php get_the_form()->the() ?>
	</div>
</div>