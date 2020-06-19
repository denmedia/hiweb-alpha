<?php
	
	add_action( 'admin_notices', function(){
		?>
		<style id="hiweb-components-adminnotices-inline-styles"><?= file_get_contents( __DIR__ . '/inline-styles.css' ); ?></style>
		<div id="hiweb-components-adminnotices-wrap"></div>
		<?php
	} );